<?php  
require 'functions.php';
if(maoo_user_id()) :
	if($_POST['id']>0 && $_POST['url']) :
		$cart = $_POST['id'];
		$user_id = $redis->hget('cart:'.$cart,'user_id');
		if($user_id==maoo_user_id()) :
			$pro_id = $redis->hget('cart:'.$cart,'pro_id');
			$redis->del('cart:'.$cart);
			$redis->srem('cart:user:'.$user_id,$cart);
			$redis->srem('cart:user:1:'.$user_id,$cart);
			maoo_update_stock($pro_id,$redis->hget('cart:'.$cart,'parameter'),$redis->hget('cart:'.$cart,'number'));
            if(strstr($_POST['url'],'?')) :
                $url = $_POST['url'].'&showcart=1&done=删除成功';
            else :
                $url = $_POST['url'].'?showcart=1&done=删除成功';
            endif;
		else :
            if(strstr($_POST['url'],'?')) :
                $url = $_POST['url'].'&showcart=1&done=删除失败';
            else :
                $url = $_POST['url'].'?showcart=1&done=删除失败';
            endif;
		endif;
	else :
		$url = $redis->get('site_url').'?done=操作参数有误&showcart=1';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>