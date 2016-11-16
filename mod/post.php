<?php
class Maoo {
	public function latest(){
		global $redis;
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$maoo_title = '最新'.$maoo_title_page.' - '.$redis->get('site_name');
        $count = $redis->scard('post_id');
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $db = $redis->sort('post_id',array('sort'=>'desc','limit'=>array($offset,$page_size)));
		include ROOT_PATH.'/theme/'.maoo_theme().'/latest.php';
	}
	public function publish(){
		global $redis;
		if(maoo_user_id()) {
			$user_id = maoo_user_id();
			if($_GET['topic_id']>0) {
				$topic_id = $_GET['topic_id'];
			} elseif($redis->hget('user_draft_post:'.$user_id,'topic')) {
				$topic_id = $redis->hget('user_draft_post:'.$user_id,'topic');
			};
			$maoo_title = '发布文章 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/publish-post.php';
		} else {
			$maoo_title = '用户登录 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/login.php';
		}
	}
	public function edit(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
		};
		if(maoo_user_id()) {
			$user_id = maoo_user_id();
			
				if($redis->hget('post:'.$id,'author')==$user_id || $redis->hget('user:'.maoo_user_id(),'user_level')==10 || $redis->hget('user:'.maoo_user_id(),'user_level')==8) {
					$maoo_title = '编辑文章 - '.$redis->get('site_name');
					include ROOT_PATH.'/theme/'.maoo_theme().'/publish-post.php';
				} else {
					$error = '您没有权限进行此操作';
					$maoo_title = '错误404 - '.$redis->get('site_name');
					include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
				}
		} else {
			$maoo_title = '用户登录 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/login.php';
		}
	}
	public function publishtopic(){
		global $redis;
		if(maoo_user_id()) {
			$maoo_title = '发起话题 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/publish-topic.php';
		} else {
			$maoo_title = '用户登录 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/login.php';
		}
	}
	public function single(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
			$author = $redis->hget('post:'.$id,'author');
			if($redis->hget('post:'.$id,'del')==1) {
				$error = '该文章已被删除';
				$maoo_title = '错误404 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
			} else {
				$maoo_title = $redis->hget('post:'.$id,'title').' - '.$redis->get('site_name');
				maoo_set_views($id);
                if(maoo_user_id() && $redis->hget('post:'.$id,'term')>0) :
                    $redis->sadd('term_post_involvement:'.$redis->hget('post:'.$id,'term'),maoo_user_id());
                endif;
				include ROOT_PATH.'/theme/'.maoo_theme().'/post-single.php';
			}
		} else {
			$error = '您访问的页面没有找到';
			$maoo_title = '错误404 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
		}
	}
	public function pubtopic(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
        };
		include ROOT_PATH.'/theme/'.maoo_theme().'/publish-topic.php';
	}
	public function term(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
		};
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$maoo_title = maoo_term_title($id).$maoo_title_page.' - '.$redis->get('site_name');
        $count = $redis->scard('term_post_id:'.$id);
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $db = $redis->sort('term_post_id:'.$id,array('sort'=>'desc','limit' =>array($offset,$offset+$page_size-1)));
        if(maoo_user_id()) :
            $redis->sadd('term_post_involvement:'.$id,maoo_user_id());
        endif;
		include ROOT_PATH.'/theme/'.maoo_theme().'/post-term.php';
	}
	public function tag(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
		};
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$tag = $_GET['tag'];
		$maoo_title = $tag.$maoo_title_page.' - '.$redis->get('site_name');
        $count = $redis->scard('tag_post_id:'.$tag);
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $db = $redis->sort('tag_post_id:'.$tag,array('sort'=>'desc','limit' =>array($offset,$offset+$page_size-1)));
		include ROOT_PATH.'/theme/'.maoo_theme().'/post-tag.php';
	}
}
