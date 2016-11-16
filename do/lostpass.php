<?php  
require 'functions.php';
if($_POST['user_name']!='' && $_POST['user_pass']!='' && $_POST['user_question']>0 && $_POST['user_answer']!='') :
	$user_name = $_POST['user_name'];
	$id = $redis->zscore('user_id_name',$user_name);
	if($id>0) :
		$user_question = $redis->hget('user:'.$id,'user_question');
		$user_answer = $redis->hget('user:'.$id,'user_answer');
		if($user_question==$_POST['user_question'] && $user_answer==$_POST['user_answer']) :
			$redis->hset('user:'.$id,'user_pass',sha1($_POST['user_pass']));
			$url = $redis->get('site_url').'?m=user&a=login&done=请使用新密码登录网站';
		else :
			$url = $redis->get('site_url').'?m=user&a=lostpass&done=安全信息不正确';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=lostpass&done=用户不存在';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=lostpass&done=必须完整填写各项信息';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>