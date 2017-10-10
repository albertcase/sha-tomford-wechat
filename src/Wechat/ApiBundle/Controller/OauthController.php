<?php

namespace Wechat\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OauthController extends Controller
{

  //授权callback
  public function authorizeCallbackAction(Request $request)
  {
      $code = $request->query->get('code');
      $callback_url = urldecode($request->query->get('redirect_uri'));
      if($code){
          $access_token = $this->container->get('my.Wechat')->getoauth2token($code);
          $param = array();
          if(isset($access_token['openid'])) {
            if($access_token['scope'] == 'snsapi_base') {
              $param['openid'] = $access_token['openid'];
            } 
            if($access_token['scope'] == 'snsapi_userinfo') {
              $param['openid'] = $access_token['openid'];
              $param['access_token'] = $access_token['access_token'];
            }
          }
          $url = $this->generateRedirectUrl($callback_url, $param);
          return $this->redirect($url);
      } else {

      }
      return new Response('code is null');
  }

  // 授权接口
  public function authorizeApiAction(Request $request)
  {
      $scope = $request->query->get('scope', 'snsapi_base');
      $callback_url = urldecode($request->query->get('redirect_uri'));

      # 没有回调地址报错
      if(!$callback_url) {
        return new Response('param redirect_uri not exists');
      }
      # checkdomain
      $checkdomainSevice = $this->get('check.domain');
      if($checkdomainSevice->checkDomainAllow($callback_url)) {
          $goto = $this->generateUrl('wechat_callback_api', array('redirect_uri' => urlencode($callback_url)), UrlGeneratorInterface::ABSOLUTE_URL);
          return $this->redirect($this->get('my.Wechat')->getoauth2url(urlencode($goto), $scope));
      }
      return new Response('the redirect_uri url is not be allowed');
  }

  /**
   * Generate redirect uri
   */
  private function generateRedirectUrl($url, $param) {
    $parse_url = parse_url(urldecode($url));
    $base = $parse_url['scheme'] . '://' . $parse_url['host'] . $parse_url['path'];
    if(isset($parse_url['query'])) {
      parse_str($parse_url['query'], $query);
      $param = array_merge($query, $param);
    }
    return $base . '?' . http_build_query($param);
  }

  public function addAction()
  {
    $form = $this->container->get('form.oauthadd');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function listAction()
  {
    $form = $this->container->get('form.oauthlist');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function infoAction()
  {
    $form = $this->container->get('form.oauthinfo');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function updateAction()
  {
    $form = $this->container->get('form.oauthupdate');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function deleteAction()
  {
    $form = $this->container->get('form.oauthdelete');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }
}
