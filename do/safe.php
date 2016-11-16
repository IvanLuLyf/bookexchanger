<?php
require 'functions.php';
if(maoo_user_id()) :
	$user['user_question'] = $_POST['question'];
	$user['user_answer'] = $_POST['answer'];
	if($user['user_question']==1 || $user['user_question']==2 || $user['user_question']==3 || $user['user_question']==4 || $user['user_question']==5) :
		if($user['user_answer']!='') :
			$redis->hmset('user:'.maoo_user_id(),$user);
			$url = $redis->get('site_url').'?m=user&a=pass&done=安全问题设置成功';
		else :
			$url = $redis->get('site_url').'?m=user&a=pass&done=安全答案不能为空';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=pass&done=安全问题参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登陆';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
