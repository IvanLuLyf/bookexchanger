<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
    $cart = $_POST['id'];
	$redis->hset('cart:'.$cart,'update',$_POST['content']);
	$user_id = $redis->hget('cart:'.$cart,'user_id');
	$pro_id = $redis->hget('cart:'.$cart,'pro_id');
    $text = '我购买的商品：《<a href="'.maoo_url('user','order').'">'.$redis->hget('pro:'.$pro_id,'title').'</a>》有了新的动态：'.$_POST['content'];
    maoo_add_message($user_id,$text,true);
	$url = $redis->get('site_url').'?m=admin&a=order&done=设置成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
