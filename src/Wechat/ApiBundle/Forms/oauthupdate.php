<?php

namespace Wechat\ApiBundle\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;

class oauthupdate extends FormRequest{
  public function rule(){
    return array(
      'id' => new Assert\NotBlank(),
      // 'redirect_url' => new Assert\Url(),
      'callback_url' => new Assert\NotBlank(),
      // 'scope' => new Assert\Choice(array('snsapi_userinfo','snsapi_base')),
      'name' => new Assert\NotBlank(),
    );
  }

  public function FormName(){
    return 'POST';
  }

  public function DoData(){
    if($this->Confirm()){
      return array('code' => '11' ,'msg' => 'your input error');
    }
    return $this->dealData();
  }

  public function dealData(){
    $oauthfile = $this->container->get('my.dataSql')->checkoauth(array('id' => intval($this->getdata['id'])));
    if(!$oauthfile)
      return array('code' => '9' ,'msg' => 'this oauth not exists');
    $filename = "upload/oauth/".$oauthfile.".php";
    $fs = new Filesystem();
    $datain = array(
      'name' => $this->getdata['name'],
      // 'redirect_url' => $this->getdata['redirect_url'],
      'callback_url' => $this->getdata['callback_url'],
      // 'scope' => $this->getdata['scope'],
      'oauthfile' => $oauthfile,
    );
    // if(!$fs->exists(dirname($filename)))
    //   $fs->mkdir(dirname($filename));
    // if($fs->exists($filename))
    //   $fs->remove($filename);
    // ob_start();
    // print "<?php\n";
    // print "return ";
    // var_export($datain);
    // $string = ob_get_contents().";";
    // ob_end_clean();
    // $fs->dumpFile($filename, $string);
    $this->container->get('my.dataSql')->oauthupdate(intval($this->getdata['id']), array(
      'name' => $this->getdata['name'],
      // 'redirect_url' => $this->getdata['redirect_url'],
      'callback_url' => $this->getdata['callback_url'],
      // 'scope' => $this->getdata['scope'],
    ));
    return array('code' => '10' ,'msg' => 'update success');
  }
}
