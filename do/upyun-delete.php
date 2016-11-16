<?php

require 'functions.php';
header("Content-Type: text/html; charset=UTF-8");
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
    $img = str_replace($redis->get('upyun_url'),'',$_POST['img']);
    if($img) :
        require_once('upyun.class.php');
        $upyun = new UpYun($redis->get('upyun_bucket'), $redis->get('upyun_user'), $redis->get('upyun_pwd'));
        $upyun->delete($img);
        $redis->zrem('site_img_list',$_POST['img']);
        $url = $redis->get('site_url').'?m=admin&a=image&done=删除图片成功';
    else :
        $url = $redis->get('site_url').'?m=admin&a=image&done=参数错误';
    endif;
else :
    $url = $redis->get('site_url').'?done=您没有权限这么做';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
