<?php
require 'functions.php';
if(maoo_user_id()) :
    $user_id = maoo_user_id();
	if($_GET['type']=='qq') :
        $oid = $redis->hget('user:'.$user_id,'connect_qq');
		$redis->zrem('user:connect:qq',$oid);
        $redis->hset('user:'.$user_id,'connect_qq','');
        $url = $redis->get('site_url').'?m=user&a=set&done=解除绑定QQ账号成功';
    elseif($_GET['type']=='weibo') :
        $oid = $redis->hget('user:'.$user_id,'connect_weibo');
		$redis->zrem('user:connect:weibo',$oid);
        $redis->hset('user:'.$user_id,'connect_weibo','');
        $url = $redis->get('site_url').'?m=user&a=set&done=解除绑定微博账号成功';
	else :
		$url = $redis->get('site_url').'?m=user&a=set&done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
