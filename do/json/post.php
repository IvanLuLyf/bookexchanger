<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if($_GET['id']>0) :
	$id = $_GET['id'];
    $author = $redis->hget('post:'.$id,'author');
	$post->title = $redis->hget('post:'.$id,'title');
	$post->content = $redis->hget('post:'.$id,'content');
    $post->fmimg = maoo_fmimg($id);
	$post->time = maoo_format_date($redis->hget('post:'.$id,'date'));
	$post->views = maoo_get_views($id);
	$post->userName = maoo_user_display_name($author);
	$post->userAvatar = maoo_user_avatar($author);
    $post->likeCount = maoo_like_count($id);
    $post->topicID = $redis->hget('post:'.$id,'topic');
    $post->topicTitle = $redis->hget('topic:'.$redis->hget('post:'.$id,'topic'),'title');
	$post->error = 0;
	echo json_encode($post);
else :
	$post->error = 1;
	echo json_encode($post);
endif;
?>