<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
    if($redis->hget('user:'.maoo_user_id(),'user_level')!=10) :
	foreach($_POST['page'] as $page_key=>$page_val) :
        $legal_keys = array('title','content','content2','tags','term','fmimg','coins','guess');
        if(!in_array($page_key,$legal_keys)) :
            unset($_POST['page'][$page_key]);
        endif;
	endforeach;
    endif;
	$_POST['page']['title'] = maoo_remove_html($_POST['page']['title'],'all');
	$_POST['page']['tags'] = maoo_remove_html($_POST['page']['tags'],'all');
	if($redis->hget('user:'.maoo_user_id(),'user_level')>7) :
        $_POST['page']['content'] = maoo_str_replace_base64($_POST['page']['content']);
	       $_POST['page']['content2'] = maoo_str_replace_base64($_POST['page']['content2']);
    else :
	   $_POST['page']['content'] = maoo_str_replace_base64(maoo_remove_html($_POST['page']['content']));
	   $_POST['page']['content2'] = maoo_str_replace_base64(maoo_remove_html($_POST['page']['content2']));
    endif;
    $_POST['page']['guess'] = maoo_serialize($_POST['page']['guess']);
	if($_POST['page']['content2']) :
		if($_POST['page']['coins']>0) :
			//nothing happened
		else :
			$_POST['page']['coins'] = 3;
		endif;
	else :
		unset($_POST['page']['coins']);
	endif;
	$fmimg = $_POST['page']['fmimg'];
	if($_POST['draft']==1) :
		$redis->multi();
		$redis->hset('user_draft_post:'.$user_id,'title',$_POST['page']['title']);
		$redis->hset('user_draft_post:'.$user_id,'content',$_POST['page']['content']);
		$redis->hset('user_draft_post:'.$user_id,'content2',$_POST['page']['content2']);
		$redis->hset('user_draft_post:'.$user_id,'tags',$_POST['page']['tags']);
		$redis->exec();
		$url = $redis->get('site_url').'?m=post&a=publish&done=保存草稿成功';
	elseif($_POST['draft']==2) :
		$redis->multi();
		$redis->hset('user_draft_post:'.$user_id,'title',$_POST['title']);
		$redis->hset('user_draft_post:'.$user_id,'content',$_POST['content']);
		$redis->hset('user_draft_post:'.$user_id,'content2',$_POST['page']['content2']);
		$redis->hset('user_draft_post:'.$user_id,'tags',$_POST['tags']);
		$redis->exec();
	else :
		if($_POST['page']['title'] && $_POST['page']['content'] && $_POST['page']['term']>0) :
			$_POST['page']['fmimg'] = maoo_remove_html($fmimg,'all');
			if($_POST['id']>0) : //编辑
				$id = $_POST['id'];
				$term_id = $redis->hget('post:'.$id,'term');
				
					
							if($term_id!=$_POST['page']['term']) :
								//移除、加入
								$redis->srem('term_post_id:'.$topic_id,$id);
								$redis->sadd('term_post_id:'.$_POST['page']['term'],$id);
							endif;
							//更新文章
							if($redis->hget('post:'.$id,'tags')!=$_POST['page']['tags']) :
								$tags1 = explode(' ',$redis->hget('post:'.$id,'tags'));
								foreach($tags1 as $tag) :
									if($tag) :
										$redis->srem('tag_post_id:'.$tag,$id);
									endif;
								endforeach;
								$tags = explode(' ',$_POST['page']['tags']);
								foreach($tags as $tag) :
									if($tag) :
										$redis->sadd('tag_post_id:'.$tag,$id);
									endif;
								endforeach;
							endif;
                            if($_POST['page']['rank']>0) :
                                
                            else :
                                $_POST['page']['rank'] = $redis->hget('post:'.$id,'date');
                            endif;
                            $redis->zadd('rank_list',$_POST['page']['rank'],$id);
							$redis->hmset('post:'.$id,$_POST['page']);
							$redis->del('user_draft_post:'.$user_id); //删除草稿
							$url = $redis->get('site_url').'?m=post&a=single&done=编辑成功&id='.$id;

			else : //新建
				$id = $redis->incr('post_id_incr');
				$_POST['page']['date'] = strtotime("now");
				$_POST['page']['author'] = $user_id;
                if($redis->hget('user:'.maoo_user_id(),'user_level')>7) :
				    $user_pubcan = $redis->hget('user:'.$user_id,'user_pubbbs_date')+10;
                else :
                    $user_pubcan = $redis->hget('user:'.$user_id,'user_pubbbs_date')+120;
                endif;
				if($user_pubcan<$_POST['page']['date']) :
					
						//将文章加入topic列表
							$redis->sadd('term_post_id:'.$_POST['page']['term'],$id);
							
							//标签
							$tags = explode(' ',$_POST['page']['tags']);
							foreach($tags as $tag) :
								if($tag) :
									$redis->sadd('tag_post_id:'.$tag,$id);
								endif;
							endforeach;
							$redis->sadd('post_id',$id);
							$redis->sadd('user_post_id:'.$_POST['page']['author'],$id);
                            if($_POST['page']['rank']>0) :
                                
                            else :
                                $_POST['page']['rank'] = $_POST['page']['date'];
                            endif;
							$redis->zadd('rank_list',$_POST['page']['rank'],$id);
							$redis->hmset('post:'.$id,$_POST['page']);
							$redis->del('user_draft_post:'.$user_id); //删除草稿
							$redis->hset('user:'.$user_id,'user_pub_date',$_POST['page']['date']);
							//消息
							$text = '我发表了文章《<a href="'.maoo_url('post','single',array('id'=>$id)).'">'.$redis->hget('post:'.$id,'title').'</a>》：'.maoo_cut_str(strip_tags($_POST['page']['content']),30);
                            maoo_add_message($user_id,$text);
                            $term_author = $redis->hget('term:post:'.$_POST['page']['term'],'author');
                            if($term_author>0 && $user_id!=$term_author) :
                                $text = '<a href="'.maoo_url('user','index',array('id'=>$user_id)).'">'.maoo_user_display_name($user_id).'</a>在我的话题《<a href="'.maoo_url('post','term',array('id'=>$_POST['page']['term'])).'">'.$redis->hget('term:post:'.$_POST['page']['term'],'title').'</a>》中发表了文章《<a href="'.maoo_url('post','single',array('id'=>$id)).'">'.$redis->hget('post:'.$id,'title').'</a>》：'.maoo_cut_str(strip_tags($_POST['page']['content']),30);
                                maoo_add_message($term_author,$text);
                            endif;
							$url = $redis->get('site_url').'?m=post&a=single&done=发布成功&id='.$id;
					
				else :
					$url = $redis->get('site_url').'?m=post&a=publish&done=发布文章间隔不得小于2分钟';
				endif;
			endif;
		else :
			if($_POST['id']>0) :
				$url = $redis->get('site_url').'?m=post&a=edit&done=必须设置标题、内容以及所属分类&id='.$_POST['id'];
			else :
				$url = $redis->get('site_url').'?m=post&a=publish&done=必须设置标题、内容以及所属分类';
			endif;
		endif;
	endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;

//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
