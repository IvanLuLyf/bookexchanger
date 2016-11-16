<?php 
class Maoo {
	public function index(){
		global $redis;
		if($_GET['page']>1) :
			$maoo_title_page = ' - 第'.$_GET['page'].'页';
		endif;
		$maoo_title = '众筹'.$maoo_title_page.' - '.$redis->get('site_name');
        $page_now = $_GET['page'];
        $page_size = $redis->get('page_size');
        if(empty($page_now) || $page_now<1) :
            $page_now = 1;
        else :
            $page_now = $_GET['page'];
        endif;
        $offset = ($page_now-1)*$page_size;
        $count = $redis->scard('deal_id');
        $db = $redis->sort('deal_id',array('sort'=>'desc','limit'=>array($offset,$page_size)));
		include ROOT_PATH.'/theme/'.maoo_theme().'/deal-index.php';
	}
	public function reward(){
		global $redis;
		if(maoo_user_id()) {
			$user_id = maoo_user_id();
            $id = $_GET['id'];
            $status = maoo_deal_status($id);
            $reward_can = true;
            if($status==4 || $status==3) :
                $reward_can = false;
            else :
                $rewardkey = $_GET['reward'];
                $rewards = unserialize($redis->hget('deal:'.$id,'reward'));
                $reward = $rewards[$rewardkey];
                if($reward['count']>=$reward['number']) :
                    $reward_can = false;
                endif;
            endif;
            if($reward_can) :
                $maoo_title = '支持项目 - '.$redis->get('site_name');
                include ROOT_PATH.'/theme/'.maoo_theme().'/deal-reward.php';
            else :
                $error = '无法支持此项目';
				$maoo_title = '错误404 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
            endif;
		} else {
			$maoo_title = '用户登录 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/login.php';
		}
	}
	public function publish(){
		global $redis;
		if(maoo_user_id()) {
			$user_id = maoo_user_id();
			$maoo_title = '发起项目 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/'.maoo_theme().'/publish-deal.php';
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
			if($redis->hget('user:'.maoo_user_id(),'user_level')==10 || $redis->hget('user:'.maoo_user_id(),'user_level')==8) {
				$maoo_title = '编辑项目 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/publish-deal.php';
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
            $status = maoo_deal_status($id);
            if($status==4) :
                $error = '此项目正在审核中';
				$maoo_title = '错误404 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
            else :
                $author = $redis->hget('deal:'.$id,'author');
                $term_id = $redis->hget('deal:'.$id,'term');
                $rewards = unserialize($redis->hget('deal:'.$id,'reward'));
                $maoo_title = $redis->hget('deal:'.$id,'title').' - '.$redis->get('site_name');
                maoo_set_views($id,'deal');
                include ROOT_PATH.'/theme/'.maoo_theme().'/deal-single.php';
            endif;
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
		$maoo_title = maoo_term_title($id,'deal').$maoo_title_page.' - '.$redis->get('site_name');
		include ROOT_PATH.'/theme/'.maoo_theme().'/deal-term.php';
	}
}