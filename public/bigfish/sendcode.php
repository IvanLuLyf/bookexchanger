<?php
include "send.php";
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	foreach($redis->zrange('user_id_phone',0,-1) as $RecNum) :
		$SmsFreeSignName = '活动验证';
		$SmsParam = '{"version":"V6.0.9"}';
		$SmsTemplateCode = 'SMS_5071393';
		if(maoo_dayu_send_message($RecNum,$SmsFreeSignName,$SmsParam,$SmsTemplateCode)) :
			echo '验证码发送成功<br>';
		else :
			echo '验证码发送失败：'.$RecNum.'<br>';
		endif;
	endforeach;
else :
	echo '请在登录后操作';
endif;
?>
