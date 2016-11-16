<?php  
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	if($_POST['id']>0 && $_POST['parameter']) :
		$parameters = $redis->hget('pro:'.$_POST['id'],'parameter');
		if($parameters) :
			$cart['user_id'] = $user_id;
			$cart['pro_id'] = $_POST['id'];
			$parameters = unserialize($parameters);
			$cart['price'] = $parameters[$_POST['parameter']]['price'];
			$cart['original_price'] = $cart['price'];
			if($redis->hget('pro:'.$_POST['id'],'sale_off_date')>strtotime("now") && $redis->hget('pro:'.$_POST['id'],'sale_off')>0) : 
				$cart['price'] = $cart['price']*$redis->hget('pro:'.$_POST['id'],'sale_off')/10;
			endif;
			$cart['parameter'] = $parameters[$_POST['parameter']]['name'];
			$cart['number'] = 1;
			$cart['status'] = 1;
			if($parameters[$_POST['parameter']]['stock']>0) :
				$ucarts = $redis->smembers('cart:user:1:'.$user_id);
				foreach($ucarts as $ucart_id) :
					$ucart->pro_id = $redis->hget('cart:'.$ucart_id,'pro_id');
					$ucart->parameter = $redis->hget('cart:'.$ucart_id,'parameter');
					$ucart->price = $redis->hget('cart:'.$ucart_id,'price');
					if($ucart->pro_id==$cart['pro_id'] && $ucart->parameter==$cart['parameter'] && $ucart->price==$cart['price']) :
						$ncart_id = $ucart_id;
					endif;
				endforeach;
				if($ncart_id>0) :
					$cart['number'] = $redis->hget('cart:'.$ncart_id,'number')+1;
					$redis->hset('cart:'.$ncart_id,'number',$cart['number']);
				else :
					$id = $redis->incr('cart_id_incr');
					$redis->hmset('cart:'.$id,$cart);
					$redis->sadd('cart:user:'.$user_id,$id);
					$redis->sadd('cart:user:1:'.$user_id,$id);
				endif;
				maoo_update_stock($cart['pro_id'],$cart['parameter'],-1);
				$url = $redis->get('site_url').'?m=pro&a=single&id='.$cart['pro_id'].'&done=加入购物车成功&showcart=1';
			else :
				$url = $redis->get('site_url').'?m=pro&a=single&id='.$cart['pro_id'].'&done=商品库存不足';
			endif;
		else :
			$url = $redis->get('site_url').'?done=商品参数错误';
		endif;
	else :
		$url = $redis->get('site_url').'?m=pro&a=single&id='.$_POST['id'].'&done=请选择商品参数';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>