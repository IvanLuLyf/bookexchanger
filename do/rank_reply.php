<?php 
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')>7) :
	if($_POST['id']>0) :
		$id = $_POST['id'];
		$pro_id = $redis->hget('cart:rank:'.$id,'pro_id');
		if($pro_id>0) :
			if($_POST['content']) :
				$reply = maoo_remove_html($_POST['content']);
				$redis->hset('cart:rank:'.$id,'reply',$reply);
				$url = $redis->get('site_url').'?m=pro&a=single&id='.$pro_id.'&done=回复成功#rank-'.$id;
			else :
				$url = $redis->get('site_url').'?m=pro&a=single&id='.$pro_id.'&done=回复内容必须填写#rank-'.$id;
			endif;
		else :
			$url = $redis->get('site_url').'?done=参数错误';
		endif;
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
Header("Location:$url");
?>