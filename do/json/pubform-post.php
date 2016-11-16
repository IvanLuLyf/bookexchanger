<?php
header('Access-Control-Allow-Origin: *');
require '../functions.php';
$user_name = $_POST['user_name'];
$user_pass = sha1($_POST['user_pass']);
$id = $redis->zscore('user_id_name',$user_name);
if($id>0) :
	$user_pass_true = $redis->hget('user:'.$id,'user_pass');
	if($user_pass==$user_pass_true) :
		$date['user_login_date'] = strtotime("now");
		$redis->hmset('user:'.$id,$date);
		$user_id = $id;
	endif;
endif;
if($user_id>0) :
	$_POST['page']['title'] = maoo_remove_html($_POST['title']);
	$_POST['page']['content'] = maoo_str_replace_base64($_POST['content']);
	$_POST['page']['topic'] = $_POST['topic'];
	$_POST['page']['tags'] = $_POST['tags'];
	$fmimg = $_POST['fmimg'];
	if($_POST['draft']==1) :
		$redis->hset('user_draft_post:'.$user_id,'title',$_POST['page']['title']);
		$redis->hset('user_draft_post:'.$user_id,'content',$_POST['page']['content']);
		$redis->hset('user_draft_post:'.$user_id,'fmimg',$fmimg);
		$redis->hset('user_draft_post:'.$user_id,'tags',$_POST['page']['tags']);
		$redis->hset('user_draft_post:'.$user_id,'topic',$_POST['page']['topic']);
		$url = $redis->get('site_url').'?m=post&a=publish&done=保存草稿成功';
	elseif($_POST['draft']==2) :
		$redis->hset('user_draft_post:'.$user_id,'title',$_POST['title']);
		$redis->hset('user_draft_post:'.$user_id,'content',$_POST['content']);
		$redis->hset('user_draft_post:'.$user_id,'fmimg',$_POST['fmimg']);
		$redis->hset('user_draft_post:'.$user_id,'tags',$_POST['tags']);
		$redis->hset('user_draft_post:'.$user_id,'topic',$_POST['topic']);
	else :
		if($_POST['page']['title'] && $_POST['page']['content'] && $_POST['page']['topic']>0) :
			$_POST['page']['fmimg_full'] = maoo_save_img_base64(maoo_remove_html($fmimg));
			$_POST['page']['fmimg'] = maoo_save_img_base64(maoo_remove_html($fmimg),true,470,304);
			if($_POST['id']>0) : //编辑
				$id = $_POST['id'];
				$topic_id = $redis->hget('post:'.$id,'topic');
				if($redis->hget('post:'.$id,'permission')==3) : //投稿扔在审核中
					if($redis->hget('topic:'.$topic_id,'author')==$user_id) : //话题发起者审核文章
						$author = $redis->hget('post:'.$id,'author');
						$_POST['page']['rank'] = $redis->hget('user:'.$author,'rank1');
						$_POST['page']['permission'] = 31;
						$redis->hmset('post:'.$id,$_POST['page']);
						if($_POST['page']['topic']) :
							$redis->sadd('topic_post_id:'.$_POST['page']['topic'],$id);
						endif;
						$tags = explode(' ',$_POST['page']['tags']);
						foreach($tags as $tag) :
							if($tag) :
								$redis->sadd('tag_post_id:'.$tag,$id);
							endif;
						endforeach;
						$redis->sadd('post_id',$id);
						$redis->srem('con_topic_post_id:'.$topic_id,$id);
						$redis->sadd('user_post_id:'.$author,$id);
						$redis->zadd('rank_list',$_POST['page']['rank'],$id);
						//统计发文最多的作者
						if($redis->zscore('topic_post_count_to_user:'.$_POST['page']['topic'],$author)>0) :
							$redis->zincrby('topic_post_count_to_user:'.$_POST['page']['topic'],1,$author) ;
						else :
							$redis->zadd('topic_post_count_to_user:'.$_POST['page']['topic'],1,$author);
						endif;
						$url = '审核已通过';
					elseif($redis->hget('post:'.$id,'author')==$user_id) : //投稿者重新编辑文章
						$redis->hmset('post:'.$id,$_POST['page']);
						if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==3 && $redis->hget('topic:'.$_POST['page']['topic'],'author')!=$_POST['page']['author']) : //如果编辑的文章，此时提交的话题需审核投稿，并且当前操作者并非此话题的发起人
							if($topic_id!=$_POST['page']['topic']) : //如果提交的话题与之前投稿话题不同
								$redis->srem('con_topic_post_id:'.$topic_id,$id);
								$redis->sadd('con_topic_post_id:'.$_POST['page']['topic'],$id);
								$url = '投稿成功';
							else :
								$url = '编辑稿件成功';
							endif;
						else :
							$redis->hdel('post:'.$id,'permission');
							if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==2) :
								if($redis->sismember('topic_partner:'.$_POST['page']['topic'],$_POST['page']['author']) || $redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
									$pubcan = 1;
								endif;
							elseif($redis->hget('topic:'.$_POST['page']['topic'],'permission')==4) :
								if($redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
									$pubcan = 1;
								endif;
							else :
								$pubcan = 1;
							endif;
							if($pubcan==1) :
								$_POST['page']['rank'] = $redis->hget('post:'.$id,'rank');
								//topic rank核算
								$topic_rank_now = $redis->hget('topic:'.$_POST['page']['topic'],'rank');
								$topic_post_count = $redis->scard('topic_post_id:'.$_POST['page']['topic']);
								$topic_rank_new = round(($topic_rank_now*$topic_post_count+$_POST['page']['rank'])/($topic_post_count+1),0);
								$redis->hset('topic:'.$_POST['page']['topic'],'rank',$topic_rank_new);
								$redis->zrem('topic_rank_list',$_POST['page']['topic']);
								$redis->zadd('topic_rank_list',$topic_rank_new,$_POST['page']['topic']);
								//将文章加入topic列表
								$redis->srem('con_topic_post_id:'.$topic_id,$id);
								$redis->sadd('topic_post_id:'.$_POST['page']['topic'],$id);
								//标签
								$tags = explode(' ',$_POST['page']['tags']);
								foreach($tags as $tag) :
									if($tag) :
										$redis->sadd('tag_post_id:'.$tag,$id);
									endif;
								endforeach;
								$redis->sadd('post_id',$id);
								$redis->zadd('rank_list',$_POST['page']['rank'],$id);
								$url = '发布成功';
							else :
								$url = '您没有权限在此话题中发布文章';
							endif;
						endif;
					else :
						$url = '您没有权限编辑此文章';
					endif;
				//投稿仍在审核中 - 结束
				elseif($redis->hget('post:'.$id,'permission')==31) : //投稿已通过
					if($redis->hget('topic:'.$topic_id,'author')==$user_id) : //话题发起者编辑已投稿文章
						$redis->hmset('post:'.$id,$_POST['page']);
						if($redis->hget('post:'.$id,'topic')!=$_POST['page']['topic']) :
							$redis->srem('topic_post_id:'.$redis->hget('post:'.$id,'topic'),$id);
							$redis->sadd('topic_post_id:'.$_POST['page']['topic'],$id);
						endif;
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
						$url = '编辑完成';
					else :
						$url = '您没有权限编辑此文章'; //此处将来需允许投稿者提交修正版本
					endif;
				else :
					if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==3) : //提交的话题需审核投稿
						if($redis->hget('post:'.$id,'tags')) :
							$tags1 = explode(' ',$redis->hget('post:'.$id,'tags'));
							foreach($tags1 as $tag) :
								if($tag) :
									$redis->srem('tag_post_id:'.$tag,$id);
								endif;
							endforeach;
						endif;
						$redis->srem('topic_post_id:'.$topic_id,$id);
						$redis->srem('post_id',$id);
						$redis->zrem('rank_list',$id);
						$_POST['page']['permission'] = 3;
						$redis->sadd('con_topic_post_id:'.$_POST['page']['topic'],$id);
						$redis->hmset('post:'.$id,$_POST['page']);
						$url = '投稿成功';
					else :
						if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==2) :
							if($redis->sismember('topic_partner:'.$_POST['page']['topic'],$_POST['page']['author']) || $redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
								$pubcan = 1;
							endif;
						elseif($redis->hget('topic:'.$_POST['page']['topic'],'permission')==4) :
							if($redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
								$pubcan = 1;
							endif;
						else :
							$pubcan = 1;
						endif;
						if($pubcan==1) :
							$_POST['page']['rank'] = $redis->hget('post:'.$id,'rank');
							if($topic_id!=$_POST['page']['topic']) :
								//topic rank核算 - old
								$topic_rank_now_old = $redis->hget('topic:'.$topic_id,'rank');
								$topic_post_count_old = $redis->scard('topic_post_id:'.$topic_id);
								$topic_rank_new_old = round(($topic_rank_now_old*$topic_post_count_old-$_POST['page']['rank'])/($topic_post_count-1),0);
								$redis->hset('topic:'.$topic_id,'rank',$topic_rank_new_old);
								$redis->zrem('topic_rank_list',$topic_id);
								$redis->zadd('topic_rank_list',$topic_rank_new_old,$topic_id);
								//topic rank核算 - new
								$topic_rank_now = $redis->hget('topic:'.$_POST['page']['topic'],'rank');
								$topic_post_count = $redis->scard('topic_post_id:'.$_POST['page']['topic']);
								$topic_rank_new = round(($topic_rank_now*$topic_post_count+$_POST['page']['rank'])/($topic_post_count+1),0);
								$redis->hset('topic:'.$_POST['page']['topic'],'rank',$topic_rank_new);
								$redis->zrem('topic_rank_list',$_POST['page']['topic']);
								$redis->zadd('topic_rank_list',$topic_rank_new,$_POST['page']['topic']);
								//移除、加入
								$redis->srem('topic_post_id:'.$topic_id,$id);
								$redis->sadd('topic_post_id:'.$_POST['page']['topic'],$id);
								//统计发文最多的作者
								if($redis->zscore('topic_post_count_to_user:'.$topic_id,$author)>1) :
									$redis->zincrby('topic_post_count_to_user:'.$topic_id,-1,$author) ;
								else :
									$redis->zrem('topic_post_count_to_user:'.$topic_id,$author);
								endif;
								if($redis->zscore('topic_post_count_to_user:'.$_POST['page']['topic'],$author)>0) :
									$redis->zincrby('topic_post_count_to_user:'.$_POST['page']['topic'],1,$author) ;
								else :
									$redis->zadd('topic_post_count_to_user:'.$_POST['page']['topic'],1,$author);
								endif;
								//统计用户为话题收获的赞数量
								if(maoo_like_count($id)>0) :
									$like_count = maoo_like_count($id);
									$redis->zadd('topic_like_count_to_user:'.$topic_id,-$like_count,$author);
								endif;
							endif;
							//更新文章
							$redis->hmset('post:'.$id,$_POST['page']);
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
							$url = '编辑成功';
						else :
							$url = '您没有权限在此话题中发布文章';
						endif;
					endif;
				endif;
			else : //新建
				$id = $redis->incr('post_id_incr');
				$_POST['page']['date'] = strtotime("now");
				$_POST['page']['author'] = $user_id;
				$_POST['page']['rank'] = $redis->hget('user:'.$_POST['page']['author'],'rank1');
				$user_pubcan = $redis->hget('user:'.$user_id,'user_pub_date')+120;
				if($user_pubcan<$_POST['page']['date']) :
					if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==3 && $redis->hget('topic:'.$_POST['page']['topic'],'author')!=$_POST['page']['author']) :
						$_POST['page']['permission'] = 3;
						$redis->sadd('user_post_id:'.$_POST['page']['author'],$id);
						$redis->sadd('con_topic_post_id:'.$_POST['page']['topic'],$id);
						$redis->hmset('post:'.$id,$_POST['page']);
						$redis->del('user_draft_post:'.$user_id); //删除草稿
						$redis->hset('user:'.$user_id,'user_pub_date',$_POST['page']['date']);
						$url = '投稿成功';
					else :
						if($redis->hget('topic:'.$_POST['page']['topic'],'permission')==2) :
							if($redis->sismember('topic_partner:'.$_POST['page']['topic'],$_POST['page']['author']) || $redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
								$pubcan = 1;
							endif;
						elseif($redis->hget('topic:'.$_POST['page']['topic'],'permission')==4) :
							if($redis->hget('topic:'.$_POST['page']['topic'],'author')==$_POST['page']['author']) :
								$pubcan = 1;
							endif;
						else :
							$pubcan = 1;
						endif;
						if($pubcan==1) :
							//topic rank核算
							$topic_rank_now = $redis->hget('topic:'.$_POST['page']['topic'],'rank');
							$topic_post_count = $redis->scard('topic_post_id:'.$_POST['page']['topic']);
							$topic_rank_new = round(($topic_rank_now*$topic_post_count+$_POST['page']['rank'])/($topic_post_count+1),0);
							$redis->hset('topic:'.$_POST['page']['topic'],'rank',$topic_rank_new);
							$redis->zrem('topic_rank_list',$_POST['page']['topic']);
							$redis->zadd('topic_rank_list',$topic_rank_new,$_POST['page']['topic']);
							//将文章加入topic列表
							$redis->sadd('topic_post_id:'.$_POST['page']['topic'],$id);
							//统计发文最多的作者
							if($redis->zscore('topic_post_count_to_user:'.$_POST['page']['topic'],$user_id)>0) :
								$redis->zincrby('topic_post_count_to_user:'.$_POST['page']['topic'],1,$user_id) ;
							else :
								$redis->zadd('topic_post_count_to_user:'.$_POST['page']['topic'],1,$user_id);
							endif;
							//标签
							$tags = explode(' ',$_POST['page']['tags']);
							foreach($tags as $tag) :
								if($tag) :
									$redis->sadd('tag_post_id:'.$tag,$id);
								endif;
							endforeach;
							$redis->sadd('post_id',$id);
							$redis->sadd('user_post_id:'.$_POST['page']['author'],$id);
							$redis->zadd('rank_list',$_POST['page']['rank'],$id);
							$redis->hmset('post:'.$id,$_POST['page']);
							$redis->del('user_draft_post:'.$user_id); //删除草稿
							$redis->hset('user:'.$user_id,'user_pub_date',$_POST['page']['date']);
							$url = '发布成功';
						else :
							$url = '您没有权限在此话题中发布文章';
						endif;
					endif;
				else :
					$url = '发布文章间隔不得小于2分钟';
				endif;
			endif;
		else :
			if($_POST['id']>0) : 
				$url = '必须设置标题、内容以及所属话题';
			else :
				$url = '必须设置标题、内容以及所属话题';
			endif;
		endif;
	endif;
else :
	$url = '请先登录';
endif;
$data->error = $url;
$data->id = $id;
echo json_encode($data);