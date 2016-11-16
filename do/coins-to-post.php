<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	if($_GET['pid']>0) :
		$pid = $_GET['pid'];
		$author = $redis->hget('post:'.$pid,'author');
		if($author>0 && $author!=$user_id) :
			$coins = maoo_user_coins($user_id);
			$coins2 = maoo_user_coins($author);
			$post_coins = $redis->hget('post:'.$pid,'coins');
			if($coins>=$post_coins) :
				$redis->hset('user:'.$user_id,'coins',$coins-$post_coins);
				$coinsobj->des = '购买隐藏内容';
				$coinsobj->post_id = $pid;
				$coinsobj->coins = -$post_coins;
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
				$redis->sadd('post:'.$pid.':paycoins',$user_id);
				$redis->hset('user:'.$author,'coins',$coins2+$post_coins);
				$coinsobj->des = '出售隐藏内容';
				$coinsobj->post_id = $pid;
				$coinsobj->coins = $post_coins;
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$author,serialize($coinsobj));
				$url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=支付成功，可查看本文隐藏内容#post-entry-content2';
			else :
				$url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=您的积分不足，无法完成此次支付#post-entry-content2';
			endif;
		else:
			$url = $redis->get('site_url').'?done=参数错误1';
		endif;
	else:
		$url = $redis->get('site_url').'?done=参数错误2';
	endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
