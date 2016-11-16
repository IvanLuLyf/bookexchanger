<?php
require 'functions.php';
if(maoo_user_id()) :
    if($_POST['id']>0) :
        $id = $_POST['id'];
        if($_POST['page']['title'] && $_POST['page']['content']) :
            $date['title'] = maoo_remove_html($_POST['page']['title'],'all');
            $date['content'] = maoo_remove_html($_POST['page']['content'],'all');
            foreach($redis->zrange('term:post',0,-1) as $title) :
				if($redis->zscore('term:post',$title)==$id) :
				$redis->zrem('term:post',$title);
				endif;
            endforeach;
            $redis->zadd('term:post',$id,$date['title']);
            $redis->hset('term:post:'.$id,'title',$date['title']);
            $redis->hset('term:post:'.$id,'content',$date['content']);
            $url = $redis->get('site_url').'?m=post&a=term&id='.$id.'&done=编辑话题成功';
        else :
            $url = $redis->get('site_url').'?m=post&a=term&id='.$id.'&done=请设置话题名称和介绍';
        endif;
    else :
        if($_POST['page']['title'] && $_POST['page']['content']) :
            $user_id = maoo_user_id();
            $id = $redis->incr('term_id_incr:post');
            $redis->zadd('term:post',$id,$_POST['page']['title']);
            $date['title'] = maoo_remove_html($_POST['page']['title'],'all');
            $date['content'] = maoo_remove_html($_POST['page']['content'],'all');
            $date['author'] = $user_id;
            $redis->hmset('term:post:'.$id,$date);
            $redis->sadd('user_post_term_id:'.$user_id,$id);
            $text = '我创建了一个话题《<a href="'.maoo_url('post','term',array('id'=>$id)).'">'.$redis->hget('term:post:'.$id,'title').'</a>》';
            maoo_add_message($user_id,$text);
            $url = $redis->get('site_url').'?m=post&a=term&id='.$id.'&done=新建话题成功';
        else :
            $url = $redis->get('site_url').'?m=post&a=publish&done=请设置话题名称和介绍';
        endif;
    endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>

