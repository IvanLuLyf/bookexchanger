<?php
require __DIR__.'/../../do/functions.php';

if(maoo_dayu()) :
	include "TopSdk.php";

	function maoo_dayu_send_message($RecNum,$SmsFreeSignName,$SmsParam,$SmsTemplateCode) {
		global $redis;
		$c = new TopClient;
		$c->appkey = $redis->get('user:connect:dayu:appkey');
		$c->secretKey = $redis->get('user:connect:dayu:secretkey');
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		//$req->setExtend("123456");
		$req->setSmsType("normal");
		$req->setSmsFreeSignName($SmsFreeSignName);
		$req->setSmsParam($SmsParam);
		$req->setRecNum($RecNum);
		$req->setSmsTemplateCode($SmsTemplateCode);
		$resp = $c->execute($req);
		if($resp->result->success) :
			return true;
		else :
			return false;
		endif;
	}
endif;
?>
