<?php
require 'functions.php';
$t = $_GET['t'];
$p = $_GET['id'];
if($redis->hget('term:post:'.$t,'author')==maoo_user_id()) :
    $redis->srem('term_post_id:'.$t,$p);
    $redis->hset('post:'.$p,'term','');
	$url = $redis->get('site_url').'?m=post&a=term&id='.$t.'&done=设置成功';
else :
	$url = $redis->get('site_url').'?done=您无权进行此操作';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
