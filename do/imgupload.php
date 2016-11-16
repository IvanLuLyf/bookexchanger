<?php

require 'functions.php';
header("Content-Type: text/html; charset=UTF-8");
if(maoo_user_id()) :
  //echo maoo_save_img_base64($_POST['image']);
  $curDateTime = date("YmdHis");
  $ymd = date("Ym");
  $randNum = rand(1000, 9999);
  $out_trade_no = $curDateTime . $randNum;

  $ext = explode('.',$_FILES['file']['name']);
  $count = count($ext);
  $attached_type = $ext[$count-1];

  $fileName = ROOT_PATH.'/upload/image/'.$ymd.'/'.$out_trade_no.'.'.$attached_type; // 获取需要创建的文件名称
  $fileName_true = '/upload/image/'.$ymd.'/'.$out_trade_no.'.'.$attached_type;

  $location =  $_FILES["file"]["tmp_name"];

  $filetype = array('png','gif','jpg','jpeg');

  if(in_array($attached_type,$filetype)) :

  if (!is_dir(ROOT_PATH.'/upload/image/')){
    mkdir(ROOT_PATH.'/upload/image/', 0777); // 使用最大权限0777创建文件
  };

  if (!is_dir(ROOT_PATH.'/upload/image/'.$ymd.'/')){
    mkdir(ROOT_PATH.'/upload/image/'.$ymd.'/', 0777); // 使用最大权限0777创建文件
  };

  if (!file_exists($fileName)) { // 如果不存在则创建
    // 检测是否有权限操作
    if (!is_writeable($fileName)) {
      @chmod($fileName, 0777); // 如果无权限，则修改为0777最大权限
    };
    // 最终将d写入文件即可
    move_uploaded_file($location,$fileName);
  };

  if($redis->get('upyun')==2) {
    require_once('upyun.class.php');
    $upyun = new UpYun($redis->get('upyun_bucket'), $redis->get('upyun_user'), $redis->get('upyun_pwd'));
    $fh = fopen($fileName, 'rb');
      $rsp = $upyun->writeFile('/img/'.$ymd.'/'.$out_trade_no.'.'.$attached_type, $fh, True);
      fclose($fh);
      $file_url = $redis->get('upyun_url').'/img/'.$ymd.'/'.$out_trade_no.'.'.$attached_type;
  } elseif($redis->get('qiniu')==2) {
    require_once __DIR__.'/qiniu/engine/upload.php';
    $file_url = $redis->get('qiniu_url').'/'.$out_trade_no.'.'.$attached_type;
  } else {
    $file_url = $redis->get('site_url').$fileName_true;
  };
  $redis->zadd('site_img_list',$curDateTime,$file_url);
  echo $file_url;

  else :

  echo $redis->get('site_url').'/public/img/upload.jpg';

  endif;

endif;
?>
