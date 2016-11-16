<?php
include "send.php";
if(maoo_user_id()) :
	$RecNum = $_POST['phone'];
	$SmsFreeSignName = '身份验证';
	$_SESSION['dayu_code'] = maoo_rand(6);
	$SmsParam = '{"code":"'.$_SESSION['dayu_code'].'","product":"来自 '.$redis->get('site_name').' 的"}';
	$SmsTemplateCode = 'SMS_2200347';
	if(($redis->hget('user:'.maoo_user_id(),'sendtimes')-strtotime("now"))<0) :
		if(maoo_dayu_send_message($RecNum,$SmsFreeSignName,$SmsParam,$SmsTemplateCode)) :
			$redis->hset('user:'.maoo_user_id,'sendtimes',strtotime("now")+60);
			echo '验证码发送成功';
		else :
			echo '验证码发送失败';
		endif;
	else :
		$been = $redis->hget('user:'.maoo_user_id,'sendtimes')-strtotime("now");
		echo '您必须等待 '.$been.'秒 才可以发送验证码';
	endif;
else :
	echo '请在登录后操作';
endif;
?>
