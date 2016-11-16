<?php 
class Maoo {
	public function index(){
		global $redis;
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$maoo_title = '社区'.$maoo_title_page.' - '.$redis->get('site_name');
        $count = $redis->zcard('date_bbs_id');
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $db = $redis->zrevrange('date_bbs_id',$offset,$offset+$page_size-1);
		include ROOT_PATH.'/theme/'.maoo_theme().'/bbs-index.php';
	}
	public function publish(){
		global $redis;
		if(maoo_user_id()) {
			$user_id = maoo_user_id();
			$maoo_title = '发帖 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/publish-bbs.php';
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
			if($redis->hget('bbs:'.$id,'author')==$user_id || $redis->hget('user:'.maoo_user_id(),'user_level')==10 || $redis->hget('user:'.maoo_user_id(),'user_level')==8) {
				$maoo_title = '编辑帖子 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/publish-bbs.php';
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
	public function single(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
			$author = $redis->hget('bbs:'.$id,'author');
			$term_id = $redis->hget('bbs:'.$id,'term');
			$maoo_title = $redis->hget('bbs:'.$id,'title').' - '.$redis->get('site_name');
			maoo_set_views($id,'bbs');
			include ROOT_PATH.'/theme/'.maoo_theme().'/bbs-single.php';
		} else {
			$error = '您访问的页面没有找到';
			$maoo_title = '错误404 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
		}
	}
	public function term(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
		};
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$maoo_title = maoo_term_title($id,'bbs').$maoo_title_page.' - '.$redis->get('site_name');
        $count = $redis->zcard('date_term_bbs_id:'.$id);
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $db = $redis->zrevrange('date_term_bbs_id:'.$id,$offset,$offset+$page_size-1);
		include ROOT_PATH.'/theme/'.maoo_theme().'/bbs-term.php';
	}
}