<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_POST['title'] && $_POST['type']) :
		$id = $redis->incr('term_id_incr:'.$_POST['type']);
		$redis->zadd('term:'.$_POST['type'],$id,$_POST['title']) ;
        $redis->hset('term:'.$_POST['type'].':'.$id,'title',$_POST['title']);
        $redis->hset('term:'.$_POST['type'].':'.$id,'author',maoo_user_id());
        $redis->sadd('user_post_term_id:'.$user_id,maoo_user_id());
		$url = $redis->get('site_url').'?m=admin&a=term&done=新建分类成功';
	else :
		$url = $redis->get('site_url').'?m=admin&a=publish&done=请设置标题和类型';
	endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>

