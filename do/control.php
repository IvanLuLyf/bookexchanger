<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	$redis->set('site_name',$_POST['name']);
	$redis->set('site_url',$_POST['url']);
	$redis->set('hometheme',$_POST['hometheme']);
	$redis->set('theme',$_POST['theme']);
	$redis->set('promod',$_POST['promod']);
	$redis->set('bbsmod',$_POST['bbsmod']);
	$redis->set('dealmod',$_POST['dealmod']);
    if($_POST['page_size']>0) :
	   $redis->set('page_size',$_POST['page_size']);
    endif;
	$redis->set('topic_permission',$_POST['topic_permission']);
	$redis->set('topic_number',$_POST['topic_number']);
	$redis->set('site:signbg1',$_POST['signbg1']);
	$redis->set('site:signbg2',$_POST['signbg2']);
	$redis->set('site:signbg3',$_POST['signbg3']);
	$redis->set('statistical_code',$_POST['statistical_code']);
	$redis->set('upyun',$_POST['upyun']);
	$redis->set('upyun_bucket',$_POST['upyun_bucket']);
	$redis->set('upyun_user',$_POST['upyun_user']);
	$redis->set('upyun_pwd',$_POST['upyun_pwd']);
	$redis->set('upyun_url',$_POST['upyun_url']);
	$redis->set('qiniu',$_POST['qiniu']);
	$redis->set('qiniu_bucket',$_POST['qiniu_bucket']);
	$redis->set('qiniu_ak',$_POST['qiniu_ak']);
	$redis->set('qiniu_sk',$_POST['qiniu_sk']);
	$redis->set('qiniu_url',$_POST['qiniu_url']);
	$redis->set('rewrite',$_POST['rewrite']);
	$url = $redis->get('site_url').'?m=admin&a=index&done=设置修改成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
