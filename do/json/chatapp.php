<?php
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
else :
	if($_SESSION['guest']=='') :
		$_SESSION['guest'] = rand(1000,9999);
	endif;
	$user_id = -$_SESSION['guest'];
endif;
if($_GET['text']) :
	$comment['content'] = str_replace('"','~',maoo_remove_html($_GET['text'],'all')).' - 来自<a target="_blank" href="http://www.mao10.com/?m=post&a=single&id=28">Mao10CMS Android 客户端</a>';
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
$chat_array = array();
$db = $redis->lrange('chat_id',0,9);
$user_id = '';
foreach($db as $comment) :
	$user_id = $redis->hget('chat:'.$comment,'author');
	if($user_id!='') :
		if($user_id>0) :
			$chat['id'] = $comment;
			$chat['userLink'] = maoo_url('user','index',array('id'=>$user_id));
			$chat['content'] = maoo_remove_html($redis->hget('chat:'.$comment,'content'),'all');
			$chat['userName'] = maoo_user_display_name($user_id);
			$chat['userAvatar'] = maoo_user_avatar($user_id);
			$chat['time'] = maoo_format_date($redis->hget('chat:'.$comment,'date'));
		else :
			$chat['id'] = $comment;
			$chat['userLink'] = 'javascript:;';
			$chat['content'] = maoo_remove_html($redis->hget('chat:'.$comment,'content'),'all');
			$chat['userName'] = '游客'.$user_id;
			$chat['userAvatar'] = $redis->get('site_url').'/public/img/avatar.png';
			$chat['time'] = maoo_format_date($redis->hget('chat:'.$comment,'date'));
		endif;
		array_push($chat_array, $chat);
		unset($chat);
	endif;
endforeach;
echo json_encode($chat_array);
?>

