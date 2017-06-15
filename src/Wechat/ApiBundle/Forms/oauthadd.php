<?php

namespace Wechat\ApiBundle\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;

class oauthadd extends FormRequest{
  public function rule(){
    return array(
      //  'redirect_url' => new Assert\NotBlank(),
       'callback_url' => new Assert\NotBlank(),
      //  'scope' => new Assert\Choice(array('snsapi_userinfo','snsapi_base')),
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
    $datain = array(
      'name' => $this->getdata['name'],
      // 'redirect_url' => $this->getdata['redirect_url'],
      'callback_url' => $this->getdata['callback_url'],
      // 'scope' => $this->getdata['scope'],
      'oauthfile' => uniqid(),
    );
    $this->container->get('my.dataSql')->addoauth($datain);
    // $filename = "upload/oauth/".$datain['oauthfile'].".php";
    // $fs = new Filesystem();
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
    return array('code' => '10' ,'msg' => 'build success');
  }
}
