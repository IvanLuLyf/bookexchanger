<?php
require 'functions.php';
if(maoo_user_id()) :
	$redis->hset('user:'.maoo_user_id(),'add_name',maoo_remove_html($_POST['page']['add_name'],'all'));
    $redis->hset('user:'.maoo_user_id(),'add_province',maoo_remove_html($_POST['province'],'all'));
    $redis->hset('user:'.maoo_user_id(),'add_city',maoo_remove_html($_POST['city'],'all'));
    $redis->hset('user:'.maoo_user_id(),'add_area',maoo_remove_html($_POST['area'],'all'));
    $redis->hset('user:'.maoo_user_id(),'add_address',maoo_remove_html($_POST['page']['add_address'],'all'));
    $redis->hset('user:'.maoo_user_id(),'add_phone',maoo_remove_html($_POST['page']['add_phone'],'all'));
	$url = $redis->get('site_url').'?m=user&a=order&done=默认收货地址设置成功';
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登陆';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
