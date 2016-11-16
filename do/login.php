<?php
require 'functions.php';
$_SESSION['user_referer'] = $_POST['user_referer'];
if($_POST['user_name'] && $_POST['user_pass']) :
	$user_name = $_POST['user_name'];
	$user_pass = sha1($_POST['user_pass']);
	$idname = $redis->zscore('user_id_name',$user_name);
	$idphone = $redis->zscore('user_id_phone',$user_name);
	if($idname>0) :
		$id = $idname;
	elseif($idphone>0) :
		$id = $idphone;
		$user_name = $redis->hget('user:'.$id,'user_name');
	endif;
	if($id>0) :
		$user_pass_true = $redis->hget('user:'.$id,'user_pass');
		if($user_pass==$user_pass_true) :
			$date['user_login_date'] = strtotime("now");
            $date['user_last_ip'] = maoo_user_ip();
			//积分开始
			$user_coins_date = $redis->hget('user:'.$id,'user_coins_date')+86400;
			if($user_coins_date<$date['user_login_date']) :
				$coins = maoo_user_coins($id);
				$redis->hset('user:'.$id,'coins',$coins+maoo_coins_every_day());
				$redis->hset('user:'.$id,'user_coins_date',$date['user_login_date']);
				$coinsobj->user_id = $id;
				$coinsobj->des = '登录';
				$coinsobj->coins = maoo_coins_every_day();
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$id,serialize($coinsobj));
			endif;
			//积分结束
			$redis->hmset('user:'.$id,$date);
			$_SESSION['user_name'] = $user_name;
			$_SESSION['user_pass'] = $user_pass;
			$user_level = $redis->hget('user:'.$id,'user_level');
            $redis->zadd('user_rank_list',strtotime("now"),$id);
			if($_POST['user_referer']) {
				$url = $_POST['user_referer'];
			} elseif($user_level==10) {
				$url = $redis->get('site_url').'?m=admin&a=index';
			} else {
				$url = $redis->get('site_url').'?m=user&a=index&id='.$id;
			};
		else :
			$url = $redis->get('site_url').'?m=user&a=login&done=密码不正确&referer=1';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=login&done=用户不存在&referer=1';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=必须填写用户名和密码&referer=1';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
