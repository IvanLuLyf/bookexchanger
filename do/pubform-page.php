<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
			$_POST['page']['content'] = maoo_str_replace_base64($_POST['page']['content']);
			if($_POST['id']) : //编辑
				$id = $_POST['id'];
				$redis->hmset('post:page:'.$id,$_POST['page']);
                if($_POST['page']['rank']>0) :
				    $redis->zadd('post_id:page:rank',$_POST['page']['rank'],$id);
                else :
                    $redis->zrem('post_id:page:rank',$id);
                endif;
			else : //新建
				$id = $redis->incr('post_id_incr:page');
				$redis->hmset('post:page:'.$id,$_POST['page']);
				$redis->sadd('post_id:page',$id);
                if($_POST['page']['rank']>0) :
				    $redis->zadd('post_id:page:rank',$_POST['page']['rank'],$id);
                endif;
				$redis->lpush('new_post_id:page',$id);
				$redis->ltrim('new_post_id:page',0,1199);
			endif;
			$url = $redis->get('site_url').'?m=admin&a=page&done=发布成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>

