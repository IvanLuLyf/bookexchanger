<?php
require 'functions.php';
if($_GET['id']>0 && $_GET['type']) :
	$id = $_GET['id'];
	$user_id = maoo_user_id();
	if($user_id>0) :
		$type = $_GET['type'];
		if($_GET['type']=='post') :
			$author_id = $redis->hget('post:'.$id,'author');
			if($author_id==$user_id || $redis->hget('user:'.$user_id,'user_level')==10) :
				//所有
				$redis->srem('post_id',$id);
				//已删除文章列表
				$redis->sadd('del_post_id',$id);
				//用户文章列表
				$redis->srem('user_post_id:'.$author_id,$id);
				//用户已删除文章列表
				$redis->sadd('del_user_post_id:'.$author_id,$id);
				//分类
				$topic_id = $redis->hget('post:'.$id,'term');
				$redis->srem('term_post_id:'.$topic_id,$id);
				//标签
				$tags = explode(' ',$redis->hget('post:'.$id,'tags'));
				foreach($tags as $tag) :
					if($tag) :
						$redis->srem('tag_post_id:'.$tag,$id);
					endif;
				endforeach;
				//评论
				$db = $redis->sort($type.'_comment_id:'.$id,array('sort'=>'desc','limit'=>array(0,999)));
                foreach($db as $comment) :
                    $db_child = $redis->sort('comment_child_id:'.$comment,array('sort'=>'desc','limit'=>array(0,100)));
                    foreach($db_child as $comment_child) :
                        $redis->srem('comment_id',$comment_child);
                        $redis->srem('user_comment_id:'.$user_id,$comment_child);
                        $redis->del('comment:'.$comment_child);
                    endforeach;
                    $redis->srem('comment_id',$comment);
                    $redis->srem('user_comment_id:'.$user_id,$comment);
                    if($redis->hget('comment:'.$comment,'parent')>0) :
                        $comment_parent = $redis->hget('comment:'.$comment,'parent');
                        $redis->srem('comment_child_id:'.$comment_parent,$comment);
                    else :
                        $redis->srem('post_comment_id:'.$id,$comment);
                        $redis->del('comment_child_id:'.$comment);
                    endif;
                    $redis->del('comment:'.$comment);
                endforeach;
				//RANK列表
				$redis->zrem('rank_list',$id);
				//标记内容
				$redis->hset('post:'.$id,'del',1);
				$url = $redis->get('site_url').'?done=删除成功';
			else :
				$url = $redis->get('site_url').'?m=post&a=single&id='.$id.'&done=你没有删除此文章的权限';
			endif;
		elseif($_GET['type']=='page') :
			if($redis->hget('user:'.$user_id,'user_level')==10) :
				//全部
				$redis->srem('post_id:page',$id);
				//缓存
				$redis->del('new_post_id:page');
				foreach($redis->sort('post_id:page',array('sort'=>'desc','limit'=>array(0,1200))) as $post_id) :
					$redis->rpush('new_post_id:page',$post_id);
					$redis->ltrim('new_post_id:page',0,1199);
				endforeach;
				//删除内容
				$redis->del('post:page:'.$id);
				$url = $redis->get('site_url').'?m=admin&a=page&done=删除成功';
			else :
				$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
		elseif($_GET['type']=='user') :
			if($redis->hget('user:'.$user_id,'user_level')==10) :
				//全部
				$redis->zrem('user_id_name',$redis->hget('user:'.$id,'user_name'));
				//缓存
				$redis->del('new_user_id');
				foreach( $redis->zrevrange('user_id_name',0,1199) as $user_name) :
					$user_id = $redis->zscore('user_id_name',$user_name);
					$redis->lpush('new_user_id',$user_id);
				endforeach;
				//发布列表
				$redis->del('user_post_id:'.$id);
				$redis->del('user_bbs_id:'.$id);
				//喜欢列表
				$redis->del('user_like:'.$id);
				$redis->del('user_guanzhu:'.$id);
				$redis->del('user_fans:'.$id);
				//评论列表
				$redis->del('user_comment_id:'.$id);
				//订阅列表
				$redis->del('user_sub_topic_id:'.$id);
				//锁定话题
				foreach($redis->smembers('user_topic_id:'.$id) as $topic_id) :
					$redis->hset('topic:'.$topic_id,'permission',4);
					//投稿全部退回
				endforeach;
				//标记内容
				$redis->hset('user:'.$id,'del',1);
				$url = $redis->get('site_url').'?m=admin&a=user&done=删除成功';
			else :
				$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
		elseif($_GET['type']=='topic') :
			if($redis->scard('topic_post_id:'.$id)>0) :
				$url = $redis->get('site_url').'?m=post&a=topic&id='.$id.'&done=有文章的话题不允许删除';
			else :
				if($redis->hget('user:'.$user_id,'user_level')==10) :
					//全部
					$redis->srem('topic_id',$id);
					//分类
					$term_id = $redis->hget('topic:'.$id,'term');
					$redis->srem('term_topic_id:'.$term_id,$id);
					//排名
					$redis->zrem('topic_rank_list',$id);
					//用户
					$author_id = $redis->hget('topic:'.$id,'author');
					$redis->srem('user_topic_id:'.$author_id,$id);
					//标记
					$redis->hset('topic:'.$id,'del',1);
					//已删除文章列表
					$redis->sadd('del_topic_id',$id);
					$url = $redis->get('site_url').'?m=post&a=topic&done=删除话题成功';
				else :
						$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
				endif;
			endif;
		elseif($_GET['type']=='pro') :
			if($redis->hget('user:'.$user_id,'user_level')==10) :
				//所有
				$redis->zrem('pro_id',$id);
				//已删除文章列表
				$redis->sadd('del_pro_id',$id);
				//分类
				$term_id = $redis->hget('pro:'.$id,'term');
				$redis->zrem('term_pro_id:'.$term_id,$id);
                $parent = $redis->hget('term:pro:'.$term_id,'parent');
                if($parent>0) :
                    $redis->zrem('term_pro_id:'.$parent,$id);
                endif;
                $postid = $redis->hget('pro:'.$id,'post');
                if($postid>0) :
                    $redis->srem('term_post_id:'.$redis->hget('pro:'.$id,'post_term'),$postid);
                    $redis->srem('post_id',$postid);
                    $redis->srem('user_post_id:'.$redis->hget('pro:'.$id,'author'),$postid);
                    $redis->zrem('rank_list',$postid);
                    $redis->del('post:'.$postid);
                endif;
				$redis->hset('pro:'.$id,'del',1);
				$url = $redis->get('site_url').'?m=pro&a=index&done=商品删除成功';
			else :
				$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
		elseif($_GET['type']=='bbs') :
			if($redis->hget('user:'.$user_id,'user_level')==10) :
			//所有
			$redis->srem('bbs_id',$id);
			$redis->zrem('date_bbs_id',$id);
			//用户文章列表
			$author_id = $redis->hget('bbs:'.$id,'author');
			$redis->srem('user_bbs_id:'.$author_id,$id);
			//分类
			$term_id = $redis->hget('bbs:'.$id,'term');
			$redis->srem('term_bbs_id:'.$term_id,$id);
			$redis->zrem('date_term_bbs_id:'.$term_id,$id);
			//评论
			$db = $redis->sort($type.'_comment_id:'.$id,array('sort'=>'desc','limit'=>array(0,999)));
			foreach($db as $comment) :
				$db_child = $redis->sort('comment_child_id:'.$comment,array('sort'=>'desc','limit'=>array(0,100)));
				foreach($db_child as $comment_child) :
					$redis->srem('comment_id',$comment_child);
					$redis->srem('user_comment_id:'.$user_id,$comment_child);
					$redis->del('comment:'.$comment_child);
				endforeach;
				$redis->srem('comment_id',$comment);
				$redis->srem('user_comment_id:'.$user_id,$comment);
				if($redis->hget('comment:'.$comment,'parent')>0) :
					$comment_parent = $redis->hget('comment:'.$comment,'parent');
					$redis->srem('comment_child_id:'.$comment_parent,$comment);
				else :
					$redis->srem('post_comment_id:'.$id,$comment);
					$redis->del('comment_child_id:'.$comment);
				endif;
				$redis->del('comment:'.$comment);
			endforeach;
			//数据
			$redis->del('bbs:'.$id);

			$url = $redis->get('site_url').'?m=bbs&a=index&done=删除成功';
			else :
			$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
        elseif($_GET['type']=='deal') :
            if($redis->hget('user:'.$user_id,'user_level')==10) :
            $redis->srem('deal_id',$id);
            $author = $redis->hget('deal:'.$id,'author');
            $redis->srem('user_deal_id:'.$author,$id);
            $term = $redis->hget('deal:'.$id,'term');
            $redis->srem('term_deal_id:'.$term,$id);
            //进展
            foreach($redis->smembers('deal:updatelist:'.$id) as $page_id) :
                $redis->del('deal:update:'.$page_id);
            endforeach;
            $redis->del('deal:updatelist:'.$id);
            //支持
            foreach($redis->smembers('deal:rewardlist:'.$id) as $page_id) :
                $author = $redis->hget('deal:reward:'.$page_id,'user_id');
                $redis->srem('user:reward:'.$author,$page_id);
                $redis->del('deal:reward:'.$page_id);
            endforeach;
            $redis->del('deal:rewardlist:'.$id);
            //项目
            $redis->del('deal:'.$id);
            $url = $redis->get('site_url').'?m=deal&a=index&done=删除成功';
            else :
			$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
        elseif($_GET['type']=='cartrank') :
            if($redis->hget('user:'.$user_id,'user_level')==10) :
            $pro_id = $redis->hget('cart:rank:'.$id,'pro_id');
            $redis->srem('pro:imgrank',$id);
            $redis->srem('pro:rank:'.$pro_id,$id);
            $redis->del('cart:rank:'.$id);
            $url = $redis->get('site_url').'?m=pro&a=index&done=删除成功';
            else :
			$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
        elseif($_GET['type']=='activity') :
            $author = $redis->hget('activity:'.$page_id,'date');
            if($redis->hget('user:'.$user_id,'user_level')==10 || $author==$user_id) :
            $redis->del('activity:'.$id);
            $redis->srem('activity_id',$id);
            $redis->srem('user_activity_id:'.$author,$id);
            $url = $redis->get('site_url').'?m=user&a=index&id='.$author.'&done=删除成功';
            else :
			$url = $redis->get('site_url').'?done=你没有进行此项操作的权限';
			endif;
		else :
			$url = $redis->get('site_url').'?done=参数错误';
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
