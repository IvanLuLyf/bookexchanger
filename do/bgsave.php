<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	$redis->bgrewriteaof();
	$redis->bgsave();
	if($redis->get('upyun')==2) :
		require_once('upyun.class.php');
		$curDateTime = date("ymdH");
		$upyun = new UpYun($redis->get('upyun_bucket'), $redis->get('upyun_user'), $redis->get('upyun_pwd'));
		$fh = fopen(ROOT_PATH.'/../../redis-3.0.2/src/appendonly.aof', 'rb');
		$rsp = $upyun->writeFile('/aof/'.$curDateTime.'.aof', $fh, True);
		fclose($fh);
		$fh1 = fopen(ROOT_PATH.'/../../redis-3.0.2/src/dump.rdb', 'rb');
		$rsp1 = $upyun->writeFile('/aof/'.$curDateTime.'.rdb', $fh1, True);
		fclose($fh1);
		$text = '已将网站数据备份至又拍云';
	else :
		$text = '已后台运行备份';
	endif;
	$url = $redis->get('site_url').'?m=admin&a=index&done='.$text;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
