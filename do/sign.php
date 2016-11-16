<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	$redis->set('user:connect:qq:appid',$_POST['qq_appid']);
	$redis->set('user:connect:qq:appkey',$_POST['qq_appkey']);
	$redis->set('user:connect:weibo:appkey',$_POST['weibo_appkey']);
	$redis->set('user:connect:weibo:appsecret',$_POST['weibo_appsecret']);
	$redis->set('user:connect:dayu:appkey',$_POST['dayu_appkey']);
	$redis->set('user:connect:dayu:secretkey',$_POST['dayu_secretkey']);
	$redis->set('user:connect:dayu:reglock',$_POST['dayu_reglock']);
	$url = $redis->get('site_url').'?m=admin&a=sign&done=设置成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
