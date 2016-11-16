<?php
require 'functions.php';
if(maoo_user_id()) :
	if(sha1($_POST['pass1'])==$redis->hget('user:'.maoo_user_id(),'user_pass')) :
		if($_POST['pass2']==$_POST['pass3']) :
			$redis->hset('user:'.maoo_user_id(),'user_pass',sha1($_POST['pass2']));
			$_SESSION['user_pass'] = sha1($_POST['pass2']);
			$url = $redis->get('site_url').'?m=user&a=pass&done=密码修改成功';
		else :
			$url = $redis->get('site_url').'?m=user&a=pass&done=两次新密码输入不一致';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=pass&done=当前密码输入不正确';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登陆';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
