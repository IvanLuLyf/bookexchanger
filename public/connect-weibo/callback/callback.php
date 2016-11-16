<?php
session_start();
include_once __DIR__.'/../config.php';
include_once __DIR__.'/../saetv2.ex.class.php';

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );

	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$ms  = $c->home_timeline(); // done
	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
	$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

	if(maoo_user_id()) :
		if($uid>0) :
			$user_id = maoo_user_id();
			$redis->zadd('user:connect:weibo',$user_id,$uid);
			$redis->hset('user:'.$user_id,'connect_weibo',$uid);
			$url = $redis->get('site_url').'?m=user&a=set&done=绑定新浪微博账号成功';
		else :
				$url = $redis->get('site_url').'?m=user&a=set&done=新浪微博授权失败';
		endif;
	else :
		if($uid>0) :
			$user_id = $redis->zscore('user:connect:weibo',$uid);
		endif;
		if($user_id>0) :
			$date['user_login_date'] = strtotime("now");
			$redis->hmset('user:'.$user_id,$date);
			$_SESSION['user_name'] = $redis->hget('user:'.$user_id,'user_name');
			$_SESSION['user_pass'] = $redis->hget('user:'.$user_id,'user_pass');
			$user_level = $redis->hget('user:'.$user_id,'user_level');
			$url = $redis->get('site_url').'?m=user&a=index&id='.$user_id;
		else :
			if($uid>0) :
				$_SESSION['connect_weibo'] = $uid;
				$url = maoo_url('user','register',array('done'=>'使用新浪微博账号'.$user_message['screen_name'].'注册本站账号并绑定','noreferer'=>'yes'));
			else :
				$url = maoo_url('user','register',array('done'=>'新浪微博授权失败','noreferer'=>'yes'));
			endif;
		endif;
	endif;
	Header("Location:$url");
} else {
?>
授权失败。
<?php
}
?>
