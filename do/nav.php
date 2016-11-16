<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_POST['type']==1) :
		$id = $redis->incr('nav:id_incr');
		if($_POST['page']>0) :
			$data['link'] = maoo_url('index','page',array('id'=>$_POST['page']));
			$data['text'] = $redis->hget('post:page:'.$_POST['page'],'title');
		elseif($_POST['page']=='a') :
			$data['link'] = $redis->get('site_url');
			$data['text'] = '首页';
		elseif($_POST['page']=='b') :
			$data['link'] = maoo_url('post','latest');
			$data['text'] = '最新';
		elseif($_POST['page']=='c') :
			$data['link'] = maoo_url('post','topic');
			$data['text'] = '话题';
		elseif($_POST['page']=='d') :
			$data['link'] = maoo_url('index','authors');
			$data['text'] = '作者';
		elseif($_POST['page']=='e') :
			$data['link'] = maoo_url('bbs','index');
			$data['text'] = '社区';
		elseif($_POST['page']=='f') :
			$data['link'] = maoo_url('pro','index');
			$data['text'] = '商品';
		elseif($_POST['page']=='g') :
			$data['link'] = maoo_url('pro','imgrank');
			$data['text'] = '晒单';
		elseif($_POST['page']=='h') :
			$data['link'] = maoo_url('deal','index');
			$data['text'] = '众筹';
		endif;
		if($data) :
			if($_POST['number']>0) :
				$number = $_POST['number'];
			else :
				$number = 1;
			endif;
			$redis->hmset('nav:'.$id,$data);
			$redis->zadd('nav:list',$number,$id);
			$url = $redis->get('site_url').'?m=admin&a=nav&done=添加导航成功';
		else :
			$url = $redis->get('site_url').'?m=admin&a=nav&done=页面参数错误';
		endif;
	elseif($_POST['type']==2) :
		if($_POST['link'] && $_POST['text']) :
			$id = $redis->incr('nav:id_incr');
			$data['link'] = $_POST['link'];
			$data['text'] = $_POST['text'];
			if($_POST['number']>0) :
				$number = $_POST['number'];
			else :
				$number = 1;
			endif;
			$redis->hmset('nav:'.$id,$data);
			$redis->zadd('nav:list',$number,$id);
			$url = $redis->get('site_url').'?m=admin&a=nav&done=添加导航成功';
		else :
			$url = $redis->get('site_url').'?m=admin&a=nav&done=链接和文字必须填写';
		endif;
	elseif($_POST['type']==3) :
		if($_POST['id']>0) :
			if($_POST['link'] && $_POST['text']) :
				$data['link'] = $_POST['link'];
				$data['text'] = $_POST['text'];
				if($_POST['number']>0) :
					$number = $_POST['number'];
				else :
					$number = 1;
				endif;
				if($redis->exists('nav:'.$_POST['id'])) :
					$redis->hmset('nav:'.$_POST['id'],$data);
					$redis->zadd('nav:list',$number,$_POST['id']);
					$url = $redis->get('site_url').'?m=admin&a=nav&done=编辑导航成功';
				else :
					$url = $redis->get('site_url').'?m=admin&a=nav&done=参数错误';
				endif;
			else :
				$url = $redis->get('site_url').'?m=admin&a=nav&done=链接和文字必须填写';
			endif;
		else :
			$url = $redis->get('site_url').'?m=admin&a=nav&done=参数错误';
		endif;
	elseif($_GET['del']>0) :
		$redis->del('nav:'.$_GET['del']);
		$redis->zrem('nav:list',$_GET['del']);
		$url = $redis->get('site_url').'?m=admin&a=nav&done=移除导航成功';
	else :
		$url = $redis->get('site_url').'?m=admin&a=nav&done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
