<?php  
require 'functions.php';
if(maoo_user_id()) :
	if(is_numeric($_POST['id'])) :
		if($_POST['content']) :
			$id = $redis->incr('comment_id_incr');
			$comment['content'] = $_POST['content'];
			$comment['post'] = $_POST['id'];
			$comment['user'] = maoo_user_id();
			$comment['date'] = strtotime("now");
			$redis->hmset('comment:'.$id,$comment);
			$redis->sadd('comment_id',$id); //全部评论列表
			$redis->sadd('post_comment_id:'.$_POST['id'],$id); //文章评论列表
			$redis->sadd('user_comment_id:'.$comment['user'],$id); //用户评论列表
			$url = $redis->get('site_url').'?m=post&a=single&id='.$_POST['id'].'&done=评论成功#comment-'.$id;
		else :
			$url = $redis->get('site_url').'?m=post&a=single&id='.$_POST['id'].'&done=请填写评论内容#comment';
		endif;
	else :
		$url = $redis->get('site_url').'?done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
Header("Location:$url");
?>