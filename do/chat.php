<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
else :
	if($_SESSION['guest']=='') :
		$_SESSION['guest'] = rand(1000,9999);
	endif;
	$user_id = -$_SESSION['guest'];
endif;
if($_POST['text']) :
	$comment['content'] = str_replace('"','~',$_POST['text']);
	$comment['author'] = $user_id;
	$comment['date'] = strtotime("now");
	if($_SESSION['chat']<($comment['date']-5)) :
		$id = $redis->incr('chat_id_incr');
		$redis->lpush('chat_id',$id);
		$redis->hmset('chat:'.$id,$comment);
		$redis->expire('chat:'.$id,86400*5);
		$_SESSION['chat'] = $comment['date'];
	endif;
endif;
$db = $redis->lrange('chat_id',0,9);
$redis->ltrim('chat_id', 0, 9);
$user_id = '';
$chat_array = array();
foreach($db as $comment) :
	$user_id = $redis->hget('chat:'.$comment,'author');
	if($user_id!='') :
		if($user_id>0) :
			$comjson->userLink = maoo_url('user','index',array('id'=>$user_id));
			$comjson->content = $redis->hget('chat:'.$comment,'content');
			$comjson->userName = maoo_user_display_name($user_id);
			$comjson->userAvatar = maoo_user_avatar($user_id);
			$comjson->time = maoo_format_date($redis->hget('chat:'.$comment,'date'));
		else :
			$comjson->userLink = 'javascript:;';
			$comjson->content = $redis->hget('chat:'.$comment,'content');
			$comjson->userName = '游客'.$user_id;
			$comjson->userAvatar = $redis->get('site_url').'/public/img/avatar.png';
			$comjson->time = maoo_format_date($redis->hget('chat:'.$comment,'date'));
		endif;
		array_push($chat_array,$comjson);
		unset($comjson);
	endif;
endforeach;
echo json_encode($chat_array);
?>