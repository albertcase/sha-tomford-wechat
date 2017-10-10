<?php

namespace Wechat\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{

	const ENCRYPT_KEY = 'CYN6LEYUSZ2HJE2F';

	const ENCRYPT_IV = 'URY6L8JA4WN2SEJL';

	public function retrieveAccessTokenAction($key) {
		if($key != self::ENCRYPT_KEY) {
			$re = array('status' => 'failed', 'errormsg' => 'encrypt key is wrong');
			return new JsonResponse($re);
		}
		$wehcat = $this->container->get('my.Wechat'); 
		$access_token = $wehcat->getAccessToken();
		$data = base64_encode($this->aes128_cbc_encrypt(self::ENCRYPT_KEY, $access_token, self::ENCRYPT_IV));
		$re = array('status' => 'success', 'data' => $data);
		return new JsonResponse($re);
	}

	public function testRetrieveAction() {
		$key = 'CYN6LEYUSZ2HJE2F';
		$iv = 'URY6L8JA4WN2SEJL';
		$return = file_get_contents('http://valentinowechat.samesamechina.com/wechat/retrieve/access_token/CYN6LEYUSZ2HJE2F');
		$return = json_decode($return);
		var_dump($return);
		if($return->status == 'success') {
			$string = base64_decode($return->data, TRUE);
			$access_token = $this->aes128_cbc_decrypt($key, $string, $iv);
			var_dump($access_token);exit;
			return $access_token;
		} else {
			return FALSE;
		}
	}

	public function aes128_cbc_encrypt($key, $data, $iv) {
		if(16 !== strlen($key)) $key = hash('MD5', $key, true);
		if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
		$padding = 16 - (strlen($data) % 16);
		$data .= str_repeat(chr($padding), $padding);
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	}

	public function aes128_cbc_decrypt($key, $data, $iv) {
	  if(16 !== strlen($key)) $key = hash('MD5', $key, true);
	  if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
	  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	  $padding = ord($data[strlen($data) - 1]);
	  return substr($data, 0, -$padding);
	}
    /**
     * 查询模版消息状态
     * 
     */
    public function tmpMsgResAction() 
    {
        if(!isset($_GET['msgid'])) {
            return new JsonResponse(array("code"=>0, "msg"=>"undefine error"));
        }
        $msgId = $_GET['msgid'];
        $myDb = $this->container->get('my.dataSql');
        $tmpMsgId = $this->checkTmpMsg($msgId, $myDb);

        # msgid不存在
        if(!$tmpMsgId || empty($msgId)) {
            return new JsonResponse(array("code"=>105, "msg"=>"msgid is not exist"));
        }

        $msgData = $this->getMsgData($msgId, $myDb);
        if($msgData) {
            $msgRs = array(
                "code" => 200,
                "msgid" => $msgData['msgid'],
                "sended" => true,
                "status" => $msgData['wechat_msg_status'],
                "template_id" => $msgData['template_id'],
                "touser" => $msgData['touser'],
                "intime" => $msgData['create_time'],
                "finishtime" => date("Y-m-d H:i:s", $msgData['wechat_msg_ftime']),
            );
            return new JsonResponse($msgRs);
        } else {
            return new JsonResponse(array("code"=>0, "msg"=>"undefine error"));
        }
    }

	/**
     * 模版消息接口
     * 1.判断调用者是否合法
     * 2.记录原始调用日志
     * 3.解析API参数并验证参数合法性
     *
     */
    public function tmpMsgAction() {
        # 验证API用户
        if(!isset($_GET['access_token'])) {
            return new JsonResponse(array("code"=>0, "msg"=>"undefine error"));
        }
        $accessToken = $_GET['access_token'];
        $myDb = $this->container->get('my.dataSql');
        $apiAccount = $this->checkApiUser($accessToken, $myDb);

        # API用户错误
        if(!$apiAccount || empty($accessToken)) {
            return new JsonResponse(array("code"=>101, "msg"=>"wrong access_token"));
        }

        # 验证API字段
        $postData = file_get_contents("php://input");

        # 记录API请求日志
        $this->setApiLog($myDb, $apiAccount, 'tmp', $postData);

        $apiData = json_decode($postData, 1);

        # API参数错误
        $apiDataRule = array(
            'touser' => 'string|notnull',
            'template_id' => 'string|notnull',
            'url' => 'string',
            'topcolor' => 'string|notnull',
            'data' => 'array',
        );
        if(!$apiData || !$this->apiDataValidate($apiDataRule ,$apiData)) {
            return new JsonResponse(array("code"=>102, "msg"=>"parameter error, please check your post data format"));
        }

        $wehcat = $this->container->get('my.Wechat');
        $wechatAccessToken = $wehcat->getAccessToken();

        # 验证用户是否关注
        $subscribe = $this->checkSubscribe($wechatAccessToken, $apiData['touser']);
        // if(!$subscribe) {
        //     return new JsonResponse(array("code"=>100, "msg"=>"the user is not follower"));
        // }

        # 验证模版ID
        $apiTmpId = $this->checkTmplateId($myDb, $apiData['template_id']);
        if(!$apiTmpId || !$this->tmplateLoad($myDb, $apiAccount, $apiTmpId)) {
            return new JsonResponse(array("code"=>103, "msg"=>"you have no authority to access this template message id"));
        }

        # 存储模版消息
        $msgId = $this->insertTmpMsg($myDb, $apiAccount, $apiData);
        if(!$msgId) {
            return new JsonResponse(array("code"=>0, "msg"=>"undefine error"));
        }

        # 模版消息发送成功！更改本地模版消息的发送状态和记录微信返回的msgid
        # 模版消息发送失败！记录微信返回的errcode和errmsg
        $sendRes = $this->sendTmpMsg($wechatAccessToken, json_encode($apiData), 1);
        if($sendRes['errcode'] == 0) {
            $this->setTmpMsgStatus($myDb, 1, $sendRes, $msgId);
        } else {
            $this->setTmpMsgStatus($myDb, 0, $sendRes, $msgId);
        }

        return new JsonResponse(array("code"=>200, "msg"=>"success", "msgid"=>$msgId));
    }

    /**
     * 验证用户是否关注
     */
    private function checkSubscribe($accessToken, $openid) {
        $apiUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openid}&lang=zh_CN";
        $res = $this->post_data($apiUrl, array(), 'GET');
        if(isset($res['subscribe']) && $res['subscribe'] == 1){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证模版ID是否存在
     */
    private function checkTmplateId($db, $tid) {
        return $db->checkTmplateId($tid);
    }

    /**
     * 验证当前访问API用户是否有权限使用该模版ID
     */
    private function tmplateLoad($db, $uid, $tid) {
        return $db->tmplateLoad($uid, $tid);
    }

    /**
     * send Wechat tmpMsg
     * @param $data   json     wechatAPIData
     */
    public function sendTmpMsg($accessToken, $data) {
        $apiUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
        return $this->post_data($apiUrl, $data, 'POST');
    }

    /**
     * 修改模版消息的状态
     * 
     */
    public function setTmpMsgStatus($db, $status, $data, $msgid) {
        $updata = array(
            "msg_status" => 1,
            "wechat_msg_errcode" => $data['errcode'],
            "wechat_msg_errmsg" => $data['errmsg'],
        );
        if($status == 1) {
            $updata['wechat_msgid'] = $data['msgid'];
        } 
        $db->updateTmpMsg($updata, $msgid);
    }

    /**
     * 验证接口调用者
     * @param $apiToken  API调用标识
     * @param $apiType   API类型[tmp: 模版消息,]
     * @param $db        存储类型[mysql, redis]
     * @return bool
     */
    public function checkApiUser($accessToken, $db) {
        return $db->checkApiUser($accessToken);
    }

    /**
     * 验证模版消息
     * @param $msgId     模版消息ID
     * @param $db        存储类型[mysql, redis]
     * @return bool
     */
    private function checkTmpMsg($msgId, $db) {
        return $db->checkTmpMsg($msgId);
    }

    /**
     * 查询模版消息
     * @param $msgId     模版消息ID
     */
    private function getMsgData($msgId, $db) {
        return $db->getMsgData($msgId);
    }

    /**
     * 记录接口最原始的日志
     * @param $db       存储类型[mysql, redis]
     * @param $type     API类型[tmp: 模版消息,]
     * @param $user     接口调用者
     * @param $data     接口日志数据
     */
    private function setApiLog($db, $account, $type, $data){
        $insertData = array(
            'account_id' => $account,
            'data' => $data,
            'type' => $type,
            'created' => date("Y-m-d H:i:s"),
        );
        return $db->insertApiLog($insertData);
    }

    /**
     * 保存模版消息
     * @param $db       存储类型[mysql, redis]
     * @param $type     API类型[tmp: 模版消息,]
     * @param $user     接口调用者
     * @param $data     接口日志数据
     */
    public function insertTmpMsg($db, $account_id, $data){
        $insertData = array(
            'msgid' => $this->create_uuid(),
            'account_id' => $account_id,
            'touser' => $data['touser'],
            'template_id' => $data['template_id'],
            'url' => $data['url'],
            'topcolor' => $data['topcolor'],
            'data' => json_encode($data['data'], 1),
            'create_time' => date("Y-m-d H:i:s"),
        );
        if($db->saveTmpMsg($insertData)) {
            return $insertData['msgid'];
        } else {
            return false;
        }
    }

    /**
     * 生成UUID
     */
    private function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }

    /**
     * 验证API字段
     * @param  array    $rule     字段验证规则
     * @param  array    $data     字段数组
     * @return boolean  $return   验证是否通过
     */
    private function apiDataValidate($rule, $data) {
        $return = TRUE;
        foreach ($rule as $rk=>$rv) {
            # 字符串
            if($rv == 'string') {
                if(isset($data[$rk]) && !is_string($data[$rk])) {
                    $return = FALSE;
                    break;
                }
            }
            # 非空字符串
            if($rv == 'string|notnull') {
                if(!isset($data[$rk]) || $data[$rk] == '' || !is_string($data[$rk])) {
                    $return = FALSE;
                    break;
                }
            }
            # 数组
            if($rv == 'array') {
                if(!isset($data[$rk]) || $data[$rk] == '' || !is_array($data[$rk])) {
                    $return = FALSE;
                    break;
                }
            }
        }
        return $return;
    }

    /**
     * Wechat POST
     */
    private function post_data($url, $param, $method) {

        $header [] = "content-type: application/json; charset=UTF-8";
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, $method );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
//        curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $param );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $res = curl_exec ( $ch );
        curl_close ( $ch );
        return json_decode ( $res, true );
    }
}
