<?php
include "send.php";
if(maoo_user_id()) :
	$phone = $_POST['phone'];
	$code = $_POST['code'];
	$user_id = maoo_user_id();
	if($phone && $code) :
		if($code==$_SESSION['dayu_code']) :
			if($redis->hget('user:'.$user_id,'phone')) :
				$redis->zrem('user_id_phone',$redis->hget('user:'.$user_id,'phone'));
			else :
				$redis->hset('user:'.$user_id,'coins',$coins+10);
				$text = '于'.date('Y-m-d H:i',strtotime("now")).'首次绑定手机，获得积分：10';
				$redis->lpush('coins:user:'.$user_id,$text);
			endif;
			$redis->hset('user:'.$user_id,'phone',$phone);
			$coins = maoo_user_coins($user_id);
			$redis->zadd('user_id_phone',$user_id,$phone);
			$url = $redis->get('site_url').'?m=user&a=set&done=绑定手机成功，获得10积分';
		else :
			$url = $redis->get('site_url').'?m=user&a=set&done=验证码不正确，请重新填写';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=set&done=手机号码和验证码必须填写';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
