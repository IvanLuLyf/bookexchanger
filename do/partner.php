<?php
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	$id = $_GET['id'];
	$topic_id = $_GET['topic_id'];
	if($topic_id>0 && $redis->hget('topic:'.$topic_id,'author')==$user_id) : 
		if($redis->hget('user:'.$id,'user_name')) :
			if($id!=$user_id) :
				$redis->sadd('topic_partner:'.$topic_id,$id);
				$redis->sadd('topic_partner_user:'.$id,$topic_id);
				$url = $redis->get('site_url').'?m=post&a=topicset&id='.$topic_id.'&step=2&done=邀请已送出';
			else :
				$url = $redis->get('site_url').'?m=post&a=topicset&id='.$topic_id.'&step=2&done=你不能邀请自己';
			endif;
		else :
			$url = $redis->get('site_url').'?m=post&a=topicset&id='.$topic_id.'&step=2&done=邀请的用户不存在';
		endif;
	else :
		$url = $redis->get('site_url').'?m=post&a=topicset&id='.$topic_id.'&step=2&done=您没有操作此话题的权限';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
