<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	if($_POST['text'] && $_POST['pid']>0) :
		$pid = $_POST['pid'];
		$content = maoo_remove_html($_POST['text'],'all');
		$comment['content'] = $content;
		$comment['type'] = 'activity';
		$comment['post'] = $pid;
		$comment['author'] = $user_id;
		$comment['date'] = strtotime("now");
		$id = $redis->incr('comment_id_incr');
		$redis->hmset('comment:'.$id,$comment);
		$redis->sadd('comment_id',$id);
		$redis->sadd('user_comment_id:'.$user_id,$id);
		$redis->sadd('activity_comment_id:'.$pid,$id);
        $err = array(
            'code'=>3,
            'des'=>'评论成功',
            'user'=> '<a href="'.maoo_url('user','index',array('id'=>$user_id)).'">'.maoo_user_display_name($user_id).'</a>',
            'content'=>$content
        );
    else :
        $err = array(
            'code'=>2,
            'des'=>'请填写评论内容'
        );
	endif;
else :
    $err = array(
        'code'=>1,
        'des'=>'请先登录'
    );
endif;
echo json_encode($err);
?>
