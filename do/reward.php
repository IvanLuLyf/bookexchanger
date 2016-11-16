<?php
require 'functions.php';
if(maoo_user_id()) :
	$user_id = maoo_user_id();
	$id = $_POST['id'];
    $status = maoo_deal_status($id);
    if($status==4) :
        $url = $redis->get('site_url').'?m=deal&a=index&done=此项目正在审核中';
    elseif($status==3) :
        $url = $redis->get('site_url').'?m=deal&a=single&id='.$id.'&done=此项目已经结束';
    else :
        $rewardkey = $_POST['reward'];
        $rewards = unserialize($redis->hget('deal:'.$id,'reward'));
        $reward = $rewards[$rewardkey];
        if($reward['count']>=$reward['number']) :
            $url = $redis->get('site_url').'?m=deal&a=single&id='.$id.'&done=支持人数已经达到限额';
        else :
            $total = $reward['price'];
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
                    //支持数量
                    $rewards[$rewardkey]['count'] = $reward['count']+1;
                    $rewards_db = serialize($rewards);
                    $redis->hset('deal:'.$id,'reward',$rewards_db);
                    //已支持金额
                    $redis->hset('deal:'.$id,'total',$redis->hget('deal:'.$id,'total')+$reward['price']);
                    //支持列表
                    $reward_list['user_id'] = $user_id;
                    $reward_list['address'] = $_POST['WIDreceive_name'].' - '.$_POST['province'].' - '.$_POST['city'].' - '.$_POST['area'].' - '.$_POST['WIDreceive_address'].' - '.$_POST['WIDreceive_phone'];
                    if($_POST['somewords']=='') :
                        $reward_list['somewords'] = '支持';
                    else :
                        $reward_list['somewords'] = $_POST['somewords'];
                    endif;
                    $reward_list['price'] = $reward['price'];
                    $reward_list['deal'] = $id;
                    $reward_list['rewardkey'] = $rewardkey;
                    $reward_list['date'] = strtotime("now");
                    $reward_list['out_trade_no'] = $out_trade_no;
                    $reward_id = $redis->incr('deal:reward:id_incr');
                    $redis->hmset('deal:reward:'.$reward_id,$reward_list);
                    $redis->sadd('deal:rewardlist:'.$id,$reward_id);
                    $redis->sadd('user:reward:'.$user_id,$reward_id);
                    //核算
                    if($total>0) :
                        //余额
                        $redis->hset('user:'.$user_id,'cash',maoo_user_cash($user_id)-$total);
                        //消费记录
                        $id = $redis->incr('cash:id_incr');
                        $cash['out_trade_no'] = $out_trade_no;
                        $cash['user_id'] = $user_id;
                        $cash['status'] = 2;
                        $cash['total'] = $total;
                        $cash['des'] = '支持项目';
                        $cash['date'] = strtotime("now");
                        $redis->hmset('cash:'.$id,$cash);
                    endif;
                    //积分
                    if($_POST['coins']>0) :
                        $coins = maoo_user_coins($user_id);
                        $redis->hset('user:'.$user_id,'coins',$coins-$_POST['coins']);
                        $coinsobj->des = '众筹抵现';
                        $coinsobj->out_trade_no = $out_trade_no;
                        $coinsobj->coins = -$_POST['coins'];
                        $coinsobj->date = strtotime("now");
                        $redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
                    endif;
                    //站内信通知项目发起人
                    $text = '我刚刚支持了众筹项目《<a href="'.maoo_url('deal','single',array('id'=>$pid)).'">'.$redis->hget('deal:'.$pid,'title').'</a>》，金额：'.$total.'元。';
                    maoo_add_message($user_id,$text);
                    $text = '<a href="'.maoo_url('user','index',array('id'=>$user_id)).'">'.maoo_user_display_name($user_id).'</a>刚刚支持了我的众筹项目《<a href="'.maoo_url('deal','single',array('id'=>$pid)).'">'.$redis->hget('deal:'.$pid,'title').'</a>》，金额：'.$total.'元。';
                    maoo_add_message($redis->hget('deal:'.$id,'author'),$text);
                    $url = $redis->get('site_url').'?m=user&a=reward&done=支持项目成功';
                else :
                    $url = $redis->get('site_url').'?m=user&a=cash&done=账户余额不足，请先充值';
                endif;
            elseif($_POST['coins']>maoo_user_coins($user_id)) :
                $url = $redis->get('site_url').'?m=deal&a=reward&id='.$id.'&reward='.$rewardkey.'&done=使用的积分不能超过您拥有的积分';
            elseif($_POST['coins']>maoo_pay_coins_limit() || $_POST['coins']>$total*maoo_cash_to_coins()) :
                $url = $redis->get('site_url').'?m=deal&a=reward&id='.$id.'&reward='.$rewardkey.'&done=使用的积分超过限额';
            else :
                $url = $redis->get('site_url').'?m=deal&a=reward&id='.$id.'&reward='.$rewardkey.'&done=积分格式有误';
            endif;
        endif;
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
$url = urldecode($url);
Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
