<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	$redis->hmset('ad',$_POST['page']);
	$url = $redis->get('site_url').'?m=admin&a=ad&done=设置成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
