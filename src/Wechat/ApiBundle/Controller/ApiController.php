<?php

namespace Wechat\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}
