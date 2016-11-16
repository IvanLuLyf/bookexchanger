<?php
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	$id = $_POST['id'];
	if($id>0 && $redis->hget('topic:'.$id,'author')==$user_id) :
		if($_POST['user']) :
			$s = $_POST['user'];
			$step = 5;
			if(!$redis->exists('search:user:'.$s)) :
				foreach($redis->lrange('new_user_id',0,1199) as $s_page_id) :
					if(strstr($redis->hget('user:'.$s_page_id,'display_name'),$s) || strstr($redis->hget('user:'.$s_page_id,'user_name'),$s)) :
						$redis->sadd('search:user:'.$s,$s_page_id);
					endif;
				endforeach;
			endif;
			$redis->expire('search:user:'.$s,7200);
			$users = $redis->smembers('search:user:'.$s);
			$maoo_title = '查询：'.$s.' - '.$redis->hget('topic:'.$id,'title').' - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/default/post-topic-set.php';
		else :
			$url = $redis->get('site_url').'?m=post&a=topicset&id='.$id.'&step=2&done=请输入需要搜索的用户名';
		endif;
	else :
		$url = $redis->get('site_url').'?m=post&a=topicset&id='.$id.'&step=2&done=您没有操作此话题的权限';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<?php if($url!='') : ?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
<?php endif; ?>
