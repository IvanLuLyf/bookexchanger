<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_POST['type']==1) :
		if($_POST['link'] && $_POST['text']) :
			$id = $redis->incr('link:id_incr');
			$data['link'] = $_POST['link'];
			$data['text'] = $_POST['text'];
			if($_POST['number']>0) :
				$number = $_POST['number'];
			else :
				$number = 1;
			endif;
			$redis->hmset('link:'.$id,$data);
			$redis->zadd('link:list',$number,$id);
			$url = $redis->get('site_url').'?m=admin&a=link&done=添加友情链接成功';
		else :
			$url = $redis->get('site_url').'?m=admin&a=link&done=链接和文字必须填写';
		endif;
	elseif($_POST['type']==2) :
		if($_POST['id']>0) :
			if($_POST['link'] && $_POST['text']) :
				$data['link'] = $_POST['link'];
				$data['text'] = $_POST['text'];
				if($_POST['number']>0) :
					$number = $_POST['number'];
				else :
					$number = 1;
				endif;
				if($redis->exists('link:'.$_POST['id'])) :
					$redis->hmset('link:'.$_POST['id'],$data);
					$redis->zadd('link:list',$number,$_POST['id']);
					$url = $redis->get('site_url').'?m=admin&a=link&done=编辑友情链接成功';
				else :
					$url = $redis->get('site_url').'?m=admin&a=link&done=参数错误';
				endif;
			else :
				$url = $redis->get('site_url').'?m=admin&a=link&done=链接和文字必须填写';
			endif;
		else :
			$url = $redis->get('site_url').'?m=admin&a=link&done=参数错误';
		endif;
	elseif($_GET['del']>0) :
		$redis->del('link:'.$_GET['del']);
		$redis->zrem('link:list',$_GET['del']);
		$url = $redis->get('site_url').'?m=admin&a=link&done=移除友情链接成功';
	else :
		$url = $redis->get('site_url').'?m=admin&a=link&done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
