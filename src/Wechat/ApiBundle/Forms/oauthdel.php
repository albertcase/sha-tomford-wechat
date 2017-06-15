<?php

namespace Wechat\ApiBundle\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;

class oauthdel extends FormRequest{
  public function rule(){
    return array(
      'id' => new Assert\NotBlank(),
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
    // $oauthfile = $this->container->get('my.dataSql')->checkoauth(array('id' => intval($this->getdata['id'])));
    // if(!$oauthfile)
    //   return array('code' => '9' ,'msg' => 'this oauth not exists');
    // $filename = "upload/oauth/".$oauthfile.".php";
    // $fs = new Filesystem();
    // if($fs->exists($filename))
    //   $fs->remove($filename);
    $this->container->get('my.dataSql')->oauthdel(array('id' => intval($this->getdata['id'])));
    return array('code' => '10' ,'msg' => 'delete success');
  }
}
