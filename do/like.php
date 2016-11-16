<?php  
require 'functions.php';
$like_user_id = maoo_user_id();
if(is_numeric($_GET['id']) && $like_user_id) :
	$id = $_GET['id'];
	$author_id = $redis->hget('post:'.$id,'author');
	if($like_user_id!=$author_id) :
		$user_like = $redis->sismember('user_like:'.$like_user_id,$id);
		if(!$user_like) :
			$topic_id = $redis->hget('post:'.$id,'topic');
			$redis->sadd('user_like:'.$like_user_id,$id);
			$like_count = maoo_like_count($id);
			$redis->hset('post:'.$id,'like_count',$like_count+1);
			$site_like_count = $redis->incr('post_like_count_incr');
			maoo_post_rank($id);
			//统计用户为话题收获的赞数量
			$redis->zadd('topic_like_count_to_user:'.$topic_id,1,$author_id);
		endif;
	endif;
endif;
?>