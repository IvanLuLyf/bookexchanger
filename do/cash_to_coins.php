<?php
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	if($_POST['coins']>0) :
		$total = round($_POST['coins']/maoo_cash_to_coins(),2);
		if(maoo_user_cash($user_id)>=$total) :
			$curDateTime = date("YmdHis");
			$randNum = rand(1000, 9999);
			$out_trade_no = maoo_user_id() . $curDateTime . $randNum;
			$redis->hset('user:'.$user_id,'cash',maoo_user_cash($user_id)-$total);
			$id = $redis->incr('cash:id_incr');
			$redis->sadd('cash:user_id:'.maoo_user_id(),$id);
			$cash['out_trade_no'] = $out_trade_no;
			$cash['user_id'] = maoo_user_id();
			$cash['status'] = 2;
			$cash['total'] = $total;
			$cash['des'] = '购买积分';
			$cash['date'] = strtotime("now");
			$redis->hmset('cash:'.$id,$cash);
			$redis->hset('user:'.$user_id,'coins',maoo_user_coins($user_id)+$_POST['coins']);
			$coinsobj->des = '购买积分';
			$coinsobj->coins = $_POST['coins'];
			$coinsobj->date = strtotime("now");
			$redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
			$url = $redis->get('site_url').'?m=user&a=coins&done=支付成功';
		else :
			$url = $redis->get('site_url').'?m=user&a=cash&done=账户余额不足，请先充值';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=coins&done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
