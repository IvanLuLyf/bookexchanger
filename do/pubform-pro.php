<?php
require 'functions.php';
if(maoo_user_id()>0 && $redis->hget('user:'.maoo_user_id(),'user_level')>7) :
	$user_id = maoo_user_id();
	$_POST['page']['title'] = maoo_remove_html($_POST['page']['title']);
	$_POST['page']['content'] = maoo_str_replace_base64($_POST['page']['content']);
		if($_POST['page']['title'] && $_POST['page']['content'] && $_POST['page']['term']>0) :
			if($_POST['id']>0) : //编辑
				$id = $_POST['id'];

				//最小价格
				$price_array = array();
				$parameters = $_POST['page']['parameter'];
				foreach($parameters as $parameter) :
					if($parameter['price']>0) :
						array_push($price_array,$parameter['price']);
					endif;
				endforeach;
				$_POST['page']['min_price'] = min($price_array);

				//格式化初始数据
				$_POST['page']['deadline'] = strtotime($_POST['page']['deadline']);
				$_POST['page']['sale_off_date'] = strtotime($_POST['page']['sale_off_date']);
				$_POST['page']['parameter'] = serialize($_POST['page']['parameter']);
				$_POST['page']['cover_image'] = serialize($_POST['page']['cover_image']);
				if($_POST['page']['inlist']>0) :
					$inlist = $_POST['page']['inlist'];
				else :
					$inlist = $id;
				endif;

				$term_id = $redis->hget('pro:'.$id,'term');
				if($term_id!=$_POST['page']['term']) :
					$redis->zrem('term_pro_id:'.$term_id,$id);
					$redis->zadd('term_pro_id:'.$_POST['page']['term'],$inlist,$id);
                    //父分类内商品
                    $parent = $redis->hget('term:pro:'.$_POST['page']['term'],'parent');
                    $parent_new = $redis->hget('term:pro:'.$term_id,'parent');
                    if($parent>0 && $parent_new>0) :
                        $redis->zrem('term_pro_id:'.$parent,$id);
                        $redis->zadd('term_pro_id:'.$parent_new,$inlist,$id);
                    elseif($parent>0) :
                        $redis->zrem('term_pro_id:'.$parent,$id);
                    elseif($parent_new>0) :
                        $redis->zadd('term_pro_id:'.$parent_new,$inlist,$id);
                    endif;
				endif;
                if($_POST['page']['post_term']>0 && $_POST['page']['post_term']!=$redis->hget('pro:'.$id,'post_term')) :
                    $postid = $redis->hget('pro:'.$id,'post');
                    $redis->srem('term_post_id:'.$redis->hget('pro:'.$id,'post_term'),$postid);
                        $redis->sadd('term_post_id:'.$_POST['page']['post_term'],$postid);
                        if($_POST['page']['rank']>0) :
                        else :
                            $_POST['page']['rank'] = $redis->hget('pro:'.$id,'date');
                        endif;
                        $redis->zadd('rank_list',$_POST['page']['rank'],$postid);
                    endif;
				//更新文章
				$redis->hmset('pro:'.$id,$_POST['page']);
				$url = $redis->get('site_url').'?m=pro&a=single&done=编辑成功&id='.$id;
			else : //新建
				$id = $redis->incr('pro_id_incr');
				$_POST['page']['date'] = strtotime("now");

				//最小价格
				$price_array = array();
				$parameters = $_POST['page']['parameter'];
				foreach($parameters as $parameter) :
					if($parameter['price']>0) :
						array_push($price_array,$parameter['price']);
					endif;
				endforeach;
				$_POST['page']['min_price'] = min($price_array);

				//格式化初始数据
                $_POST['page']['author'] = maoo_user_id();
				$_POST['page']['sales_volume'] = 0;
				$_POST['page']['collection'] = 0;
				$_POST['page']['rank'] = 0;
				$_POST['page']['rank_count'] = 0;
				$_POST['page']['views'] = 0;
				$_POST['page']['deadline'] = strtotime($_POST['page']['deadline']);
				$_POST['page']['sale_off_date'] = strtotime($_POST['page']['sale_off_date']);
				$_POST['page']['parameter'] = serialize($_POST['page']['parameter']);
				$_POST['page']['cover_image'] = serialize($_POST['page']['cover_image']);
				if($_POST['page']['inlist']>0) :
					$inlist = $_POST['page']['inlist'];
				else :
					$inlist = $id;
				endif;

				if($_POST['page']['min_price']>0) :
					$redis->zadd('pro_id',$inlist,$id);
					$redis->zadd('term_pro_id:'.$_POST['page']['term'],$inlist,$id);
                    //父分类
                    $parent = $redis->hget('term:pro:'.$_POST['page']['term'],'parent');
                    if($parent>0) :
                        $redis->zadd('term_pro_id:'.$parent,$inlist,$id);
                    endif;
                    if($_POST['page']['post_term']>0) :
                        $postid = $redis->incr('post_id_incr');
                        $_POST['page']['post'] = $postid;
                        $redis->sadd('term_post_id:'.$_POST['page']['post_term'],$postid);
                        $redis->sadd('post_id',$postid);
                        $redis->sadd('user_post_id:'.maoo_user_id(),$postid);
                        if($_POST['page']['rank']>0) :
                        else :
                            $_POST['page']['rank'] = $_POST['page']['date'];
                        endif;
                        $redis->hset('post:'.$postid,'pro',$id);
                        $redis->zadd('rank_list',$_POST['page']['rank'],$postid);
                    endif;
                    $text = '我分享了商品《<a href="'.maoo_url('pro','single',array('id'=>$id)).'">'.$_POST['page']['title'].'</a>》';
                    maoo_add_message($user_id,$text);
					$redis->hmset('pro:'.$id,$_POST['page']);
					$url = $redis->get('site_url').'?m=pro&a=single&done=发布成功&id='.$id;
				else :
					$url = $redis->get('site_url').'?m=admin&a=publishpro&done=最小价格不能为0';
				endif;
			endif;
		else :
			if($_POST['id']>0) :
				$url = $redis->get('site_url').'?m=admin&a=publishpro&done=必须设置标题、内容以及分类&id='.$_POST['id'];
			else :
				$url = $redis->get('site_url').'?m=admin&a=publishpro&done=必须设置标题、内容以及分类';
			endif;
		endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
