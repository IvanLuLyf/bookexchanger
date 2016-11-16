<?php
require 'functions.php';
if(maoo_user_id()) :
    $_SESSION['checkout_reffer'] = $_POST['reffer'];
    $_SESSION['checkout_coins'] = $_POST['coins'];
	$user_id = maoo_user_id();
	$carts = $redis->smembers('cart:user:1:'.$user_id);
	if($carts) :
		foreach($carts as $cart) :
			$cart_price += $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number');
		endforeach;
	endif;
	$total = $cart_price+$redis->get('express');
    if($total>0) :
        $code = str_replace('REF','',$_POST['reffer']);
        if($code>0 && $redis->hget('user:'.$code,'user_name')!='' && $code!=maoo_user_id() && strstr($_POST['reffer'],'REF')) :
            $total = $total/100*90;
            $reffer_cash = $total/100*15;
        endif;

        if(!is_numeric($_POST['coins'])) :
            $_POST['coins'] = 0;
        endif;
		if($_POST['coins']>=0 && $_POST['coins']<=maoo_user_coins($user_id) && $_POST['coins']<=maoo_pay_coins_limit() && $_POST['coins']<=$total*maoo_cash_to_coins()) :
            if($_POST['coins']>0) :
                $total = $total-round($_POST['coins']/maoo_cash_to_coins(),2);
            endif;
            if(maoo_user_cash($user_id)>=$total) :
                $curDateTime = date("YmdHis");
                $randNum = rand(1000, 9999);
                $out_trade_no = $user_id . $curDateTime . $randNum;
                if($carts) :
                    foreach($carts as $cart) :
                        $redis->multi();
                        $redis->srem('cart:user:1:'.$user_id,$cart);
                        $redis->sadd('cart:user:2:'.$user_id,$cart);
                        $redis->hset('cart:'.$cart,'status',2);
                        $redis->hset('cart:'.$cart,'date',strtotime("now"));
                        $redis->hset('cart:'.$cart,'out_trade_no',$out_trade_no);
                        $redis->hset('cart:'.$cart,'address',$_POST['WIDreceive_name'].' - '.$_POST['province'].' - '.$_POST['city'].' - '.$_POST['area'].' - '.$_POST['WIDreceive_address'].' - '.$_POST['WIDreceive_phone']);
                        $redis->sadd('cart:order',$cart);
                        $redis->exec();
                    endforeach;
                endif;
                if($total>0) :
                    //余额
                    $redis->hset('user:'.$user_id,'cash',maoo_user_cash($user_id)-$total);
                    //消费记录
                    $id = $redis->incr('cash:id_incr');
                    $redis->sadd('cash:user_id:'.$user_id,$id);
                    $cash['out_trade_no'] = $out_trade_no;
                    $cash['user_id'] = $user_id;
                    $cash['status'] = 2;
                    $cash['total'] = $total;
                    $cash['des'] = '购买商品';
                    $cash['date'] = strtotime("now");
                    $redis->hmset('cash:'.$id,$cash);
                endif;
                if($reffer_cash>0) :
                    //余额
                    $redis->hset('user:'.$code,'cash',maoo_user_cash($code)+$reffer_cash);
                    //消费记录
                    $id = $redis->incr('cash:id_incr');
                    $redis->sadd('cash:user_id:'.$code,$id);
                    $cash['out_trade_no'] = $out_trade_no;
                    $cash['user_id'] = $code;
                    $cash['status'] = 2;
                    $cash['total'] = $reffer_cash;
                    $cash['des'] = '推广返利';
                    $cash['date'] = strtotime("now");
                    $redis->hmset('cash:'.$id,$cash);
                endif;
                //积分
                if($_POST['coins']>0) :
                    $coins = maoo_user_coins($user_id);
                    $redis->hset('user:'.$user_id,'coins',$coins-$_POST['coins']);
                    $coinsobj->des = '购物抵现';
                    $coinsobj->out_trade_no = $out_trade_no;
                    $coinsobj->coins = -$_POST['coins'];
                    $coinsobj->date = strtotime("now");
                    $redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
                endif;
                $_SESSION['checkout_reffer'] = null;
                $_SESSION['checkout_coins'] = null;
                $url = $redis->get('site_url').'?m=user&a=order&done=支付成功';
            else :
                $mustpay = $total-maoo_user_cash($user_id);
                $_SESSION['mustpay'] = $mustpay;
                $url = $redis->get('site_url').'?m=user&a=recharge&mustpay='.$mustpay;
            endif;
        elseif($_POST['coins']>maoo_user_coins($user_id)) :
            $url = $redis->get('site_url').'?m=pro&a=checkout&done=使用的积分不能超过您拥有的积分';
        elseif($_POST['coins']>maoo_pay_coins_limit() || $_POST['coins']>$total*maoo_cash_to_coins()) :
            $url = $redis->get('site_url').'?m=pro&a=checkout&done=使用的积分超过限额';
        else :
            $url = $redis->get('site_url').'?m=pro&a=checkout&done=积分格式有误';
        endif;
	else :
        $url = $redis->get('site_url').'?m=pro&a=checkout&done=商品参数错误';
    endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
