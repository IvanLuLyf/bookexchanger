<?php
require 'functions.php';
if($_GET['id']>0) :
	$id = $_GET['id'];
	$user_id = maoo_user_id();
	if($user_id>0) :
		if($redis->hget('user:'.$user_id,'user_level')==10) :
			$redis->del('post:'.$id);
			$redis->srem('del_post_id',$id);
			$url = $redis->get('site_url').'?m=admin&a=deletedposts&done=彻底删除成功';
		else :
			$url = $redis->get('site_url').'?done=你没有权限这么做';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
	endif;
else :
	$url = $redis->get('site_url').'?done=参数错误';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
