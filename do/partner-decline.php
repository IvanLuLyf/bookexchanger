<?php
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	$topic_id = $_GET['topic_id'];
	$redis->srem('topic_partner:'.$topic_id,$user_id);
	$redis->srem('topic_partner_user:'.$user_id,$topic_id);
	$url = $redis->get('site_url').'?m=user&a=topic&done=邀请已拒绝';
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>