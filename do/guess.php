<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	if($_POST['id']>0 && $_POST['g']>0 && $_POST['coins']>0) :
		$pid = $_POST['id'];
        $g = $_POST['g'];
        $post_coins = $_POST['coins'];
		$author = $redis->hget('post:'.$pid,'author');
        $guess_args = maoo_unserialize($redis->hget('post:'.$pid,'guess'));
        $guess = $guess_args[$g];
		if($author>0) :
			$coins = maoo_user_coins($user_id);
			if($coins>=$post_coins) :
                if($post_coins<=$guess['total']) :
                    $redis->hset('user:'.$user_id,'coins',$coins-$post_coins);
                    $coinsobj->des = '积分竞猜';
                    $coinsobj->post_id = $pid;
                    $coinsobj->coins = -$post_coins;
                    $coinsobj->date = strtotime("now");
                    $redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
                    //竞猜记录
                    $gid = $redis->incr('guess_id_incr');
                    $guess_now['user_id'] = $user_id;
                    $guess_now['coins'] = $post_coins;
                    $guess_now['p'] = $pid;
                    $guess_now['g'] = $g;
                    $guess_now['date'] = strtotime("now");
                    $redis->hmset('guess:'.$gid,$guess_now);
                    $redis->sadd('post:'.$pid.':guess:'.$g,$gid);
                    //更新竞猜记录
                    $guess_args[$g]['total'] = $guess_args[$g]['total']-$post_coins;
                    $guess_db = maoo_serialize($guess_args);
                    $redis->hset('post:'.$pid,'guess',$guess_db);
                    $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=积分竞猜投注成功';
                else :
                    $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=竞猜积分超过最高积分限额';
                endif;
			else :
				$url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=您的积分不足，无法完成此次支付';
			endif;
		else:
			$url = $redis->get('site_url').'?done=参数错误1';
		endif;
	else:
		$url = $redis->get('site_url').'?done=参数错误2';
	endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
