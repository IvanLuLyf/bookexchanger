<?php
class Maoo {
	public function index(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '网站设置';
			include ROOT_PATH.'/theme/admin/index.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function user(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			if($_GET['id']>0) {
				$user_id = $_GET['id'];
				$maoo_title = '编辑用户';
				include ROOT_PATH.'/theme/admin/user-edit.php';
			} else {
				$maoo_title = '用户管理';
				include ROOT_PATH.'/theme/admin/user.php';
			}
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function slider(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '幻灯设置';
			include ROOT_PATH.'/theme/admin/slider.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function seo(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = 'SEO设置';
			include ROOT_PATH.'/theme/admin/seo.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function nav(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '导航设置';
			include ROOT_PATH.'/theme/admin/nav.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function link(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '友情链接设置';
			include ROOT_PATH.'/theme/admin/link.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function ad(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '广告设置';
			include ROOT_PATH.'/theme/admin/ad.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function sign(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '登录设置';
			include ROOT_PATH.'/theme/admin/sign.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function pay(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '支付设置';
			include ROOT_PATH.'/theme/admin/pay.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function coinsset(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '积分设置';
			include ROOT_PATH.'/theme/admin/coins_set.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function cashset(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '充值设置';
			include ROOT_PATH.'/theme/admin/cash_set.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function cashlist(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '充值监控';
			include ROOT_PATH.'/theme/admin/cash_list.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function donttouchmymoney(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '提现审核';
			include ROOT_PATH.'/theme/admin/dont_touch_my_money.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function term(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '分类管理';
			include ROOT_PATH.'/theme/admin/term.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function termedit(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$id = $_GET['id'];
			$type = $_GET['type'];
			$maoo_title = '编辑分类';
			include ROOT_PATH.'/theme/admin/term_edit.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function pending(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '待审文章';
			include ROOT_PATH.'/theme/admin/pending.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function deletedposts(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '已删文章';
			include ROOT_PATH.'/theme/admin/deleted-posts.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function deletedpros(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '已删商品';
			include ROOT_PATH.'/theme/admin/deleted-pros.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function deal(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '待审核项目';
			include ROOT_PATH.'/theme/admin/deal.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function page(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '全部页面';
			include ROOT_PATH.'/theme/admin/page.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function publishpage(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$id = $_GET['id'];
			$maoo_title = '发布页面';
			include ROOT_PATH.'/theme/admin/publish-page.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function image(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '图片管理';
			include ROOT_PATH.'/theme/admin/image.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function db(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '数据管理';
			include ROOT_PATH.'/theme/admin/db.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function publishpro(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
            if($_GET['id']>0) {
                $id = $_GET['id'];
            };
			$maoo_title = '发布商品 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/publish-pro.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function editpro(){
		global $redis;
		if($_GET['id']>0) {
			$id = $_GET['id'];
		};
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$user_id = maoo_user_id();
			if($redis->hget('user:'.$user_id,'user_level')==10 || $redis->hget('user:'.$user_id,'user_level')==8) {
				$maoo_title = '编辑商品 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/admin/publish-pro.php';
			} else {
				$error = '您没有权限进行此操作';
				$maoo_title = '错误404 - '.$redis->get('site_name');
				include ROOT_PATH.'/theme/'.maoo_theme().'/404.php';
			}
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function order(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '订单管理 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/order.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function postlist(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '全部文章 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/postlist.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function prolist(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '全部商品 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/prolist.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function deallist(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '全部项目 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/deallist.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
	public function bbslist(){
		global $redis;
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) {
			$maoo_title = '全部帖子 - '.$redis->get('site_name');
			include ROOT_PATH.'/theme/admin/bbslist.php';
		} else {
			$error = '请以管理员身份<a href="'.$redis->get('site_url').'/?m=user&a=login">登录</a>';
			$maoo_title = '错误404';
			include ROOT_PATH.'/theme/admin/404.php';
		}
	}
}
