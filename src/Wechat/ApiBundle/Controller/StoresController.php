<?php

namespace Wechat\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Filesystem;
use SimpleExcel\SimpleExcel;


class StoresController extends Controller
{
  public function addAction()
  {
    $form = $this->container->get('form.storesadd');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function listAction()
  {
    $form = $this->container->get('form.storeslist');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function infoAction()
  {
    $form = $this->container->get('form.storesinfo');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function updateAction()
  {
    $form = $this->container->get('form.storesupdate');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function deleteAction()
  {
    $form = $this->container->get('form.storesdelete');
    $data = $form->DoData();
    return  new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  public function importAction()
  {
      $tmp_file = $_FILES ['storesexcel'] ['tmp_name'];
      $file_types = explode ( ".", $_FILES ['storesexcel'] ['name'] );
      $file_type = $file_types [count ( $file_types ) - 1];
//      if($file_type != "xlsx" || $file_type != "xls" || $file_type != "csv") {
//          return array('code' => '7', 'msg' => 'this file is not a excel');
//      }
      $fs = new \Symfony\Component\Filesystem\Filesystem();
      if(!$fs->exists('upload/files/')){
          $fs->mkdir('upload/files/');
      }
      $newName = 'upload/files/storeexcel.' . $file_type;
      $fs->rename($_FILES ['storesexcel']['tmp_name'], $newName, true);

      $excel = new SimpleExcel('CSV');
      $excel->parser->loadFile($newName);

      echo $excel->parser->getCell(1, 1);

//      $excel->convertTo('JSON');
//      $excel->writer->addRow(array('add', 'another', 'row'));
//      $excel->writer->saveFile('example');
      echo 'upload ok';exit;
  }

}
