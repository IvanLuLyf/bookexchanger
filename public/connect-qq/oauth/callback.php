<?php
require_once __DIR__."/../API/qqConnectAPI.php";
$qc = new QC();
$acs = $qc->qq_callback();
$oid = $qc->get_openid();
$qc = new QC($acs,$oid);
$uinfo = $qc->get_user_info();

if(maoo_user_id()) :
	$user_id = maoo_user_id();
	$redis->zadd('user:connect:qq',$user_id,$oid);
	$redis->hset('user:'.$user_id,'connect_qq',$oid);
	$url = $redis->get('site_url').'?m=user&a=set&done=绑定QQ账号'.$uinfo["nickname"].'成功';
else :
	$user_id = $redis->zscore('user:connect:qq',$oid);
	if($user_id>0) :
		$date['user_login_date'] = strtotime("now");
		$redis->hmset('user:'.$user_id,$date);
		$_SESSION['user_name'] = $redis->hget('user:'.$user_id,'user_name');
		$_SESSION['user_pass'] = $redis->hget('user:'.$user_id,'user_pass');
		$user_level = $redis->hget('user:'.$user_id,'user_level');
		$url = $redis->get('site_url').'?m=user&a=index&id='.$user_id.'&done=使用QQ账号授权成功&nickname='.$uinfo["nickname"];
	else :
		$_SESSION['connect_qq'] = $oid;
		$url = maoo_url('user','register',array('done'=>'使用QQ账号注册本站账号并绑定','nickname'=>$uinfo["nickname"],'noreferer'=>'yes'));
	endif;
endif;
Header("Location:$url");