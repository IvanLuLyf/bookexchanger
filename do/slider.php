<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	$redis->set('slider_img:1',maoo_save_img_base64($_POST['slider_img_1']));
	$redis->set('slider_link:1',$_POST['slider_link_1']);
	$redis->set('slider_img:2',maoo_save_img_base64($_POST['slider_img_2']));
	$redis->set('slider_link:2',$_POST['slider_link_2']);
	$redis->set('slider_img:3',maoo_save_img_base64($_POST['slider_img_3']));
	$redis->set('slider_link:3',$_POST['slider_link_3']);
	$redis->set('slider_pro:img:1',maoo_save_img_base64($_POST['slider_pro_img_1']));
	$redis->set('slider_pro:link:1',$_POST['slider_pro_link_1']);
	$redis->set('slider_pro:img:2',maoo_save_img_base64($_POST['slider_pro_img_2']));
	$redis->set('slider_pro:link:2',$_POST['slider_pro_link_2']);
	$redis->set('slider_pro:img:3',maoo_save_img_base64($_POST['slider_pro_img_3']));
	$redis->set('slider_pro:link:3',$_POST['slider_pro_link_3']);
	$redis->set('slider_pro:img:4',maoo_save_img_base64($_POST['slider_pro_img_4']));
	$redis->set('slider_pro:link:4',$_POST['slider_pro_link_4']);
	$url = $redis->get('site_url').'?m=admin&a=slider&done=幻灯设置成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
