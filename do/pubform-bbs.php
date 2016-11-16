<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	foreach($_POST['page'] as $page_key=>$page_val) :
		$legal_keys = array('title','content','term');
		if(!in_array($page_key,$legal_keys)) :
			unset($_POST['page'][$page_key]);
		endif;
	endforeach;
	$_POST['page']['title'] = maoo_remove_html($_POST['page']['title'],'all');
	if($redis->hget('user:'.maoo_user_id(),'user_level')>7) :
        $_POST['page']['content'] = maoo_str_replace_base64($_POST['page']['content']);
    else :
	   $_POST['page']['content'] = maoo_str_replace_base64(maoo_remove_html($_POST['page']['content']));
    endif;
		if($_POST['page']['title'] && $_POST['page']['content'] && $_POST['page']['term']>0) :
			if($_POST['id']>0) : //编辑
				$id = $_POST['id'];
				$author = $redis->hget('bbs:'.$id,'author');
				if($redis->hget('user:'.maoo_user_id(),'user_level')>7 || maoo_user_id()==$author) :
					$term_id = $redis->hget('bbs:'.$id,'term');
					if($term_id!=$_POST['page']['term']) :
						$redis->srem('term_bbs_id:'.$term_id,$id);
                        $redis->zrem('date_term_bbs_id:'.$term_id,$id);
						$redis->sadd('term_bbs_id:'.$_POST['page']['term'],$id);
						$redis->zadd('date_term_bbs_id:'.$_POST['page']['term'],strtotime("now"),$id);
					endif;
					//更新文章
					$redis->hmset('bbs:'.$id,$_POST['page']);
					$url = $redis->get('site_url').'?m=bbs&a=single&done=编辑成功&id='.$id;
				else :
					$url = $redis->get('site_url').'?m=bbs&a=index&done=你没有权限这么做';
				endif;
			else : //新建
				$id = $redis->incr('bbs_id_incr');
				$_POST['page']['date'] = strtotime("now");
				$_POST['page']['author'] = $user_id;
				if($redis->hget('user:'.maoo_user_id(),'user_level')>7) :
				    $user_pubcan = $redis->hget('user:'.$user_id,'user_pubbbs_date')+10;
                else :
                    $user_pubcan = $redis->hget('user:'.$user_id,'user_pubbbs_date')+120;
                endif;
				if($user_pubcan<$_POST['page']['date']) :
					$redis->sadd('bbs_id',$id);
					$redis->sadd('user_bbs_id:'.$_POST['page']['author'],$id);
					$redis->sadd('term_bbs_id:'.$_POST['page']['term'],$id);
					$redis->zadd('date_bbs_id',$_POST['page']['date'],$id);
					$redis->zadd('date_term_bbs_id:'.$_POST['page']['term'],$_POST['page']['date'],$id);
					$redis->hmset('bbs:'.$id,$_POST['page']);
					$redis->hset('user:'.$user_id,'user_pubbbs_date',$_POST['page']['date']);
					//消息
					$text = '我发表了帖子《<a href="'.maoo_url('bbs','single',array('id'=>$id)).'">'.$redis->hget('bbs:'.$id,'title').'</a>》：'.maoo_cut_str(strip_tags($_POST['page']['content']),30);
					maoo_add_message($user_id,$text);
					$url = $redis->get('site_url').'?m=bbs&a=single&done=发布成功&id='.$id;
				else :
					$url = $redis->get('site_url').'?m=bbs&a=publish&done=发布文章间隔不得小于2分钟';
				endif;
			endif;
		else :
			if($_POST['id']>0) :
				$url = $redis->get('site_url').'?m=bbs&a=edit&done=必须设置标题、内容以及版块&id='.$_POST['id'];
			else :
				$url = $redis->get('site_url').'?m=bbs&a=publish&done=必须设置标题、内容以及版块';
			endif;
		endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
