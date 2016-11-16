<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_GET['id']>0 && $_GET['g']>0) :
        $pid = $_GET['id'];
        $g = $_GET['g'];
        $t = $_GET['t'];
        if($t==1) :
            $guess_args = maoo_unserialize($redis->hget('post:'.$pid,'guess'));
            $guess_single = $guess_args[$g];
            $guess_args[$g]['total'] = 0;
            foreach($redis->smembers('post:'.$pid.':guess:'.$g) as $gid) :
                $user_id = $redis->hget('guess:'.$gid,'user_id');
                $coins = $redis->hget('guess:'.$gid,'coins');
                $back_coins = $coins*$guess_single['odds'];
                $user_coins = maoo_user_coins($user_id);
                $redis->hset('user:'.$user_id,'coins',$user_coins+$back_coins);
                $coinsobj->des = '积分竞猜获胜奖励';
                $coinsobj->post_id = $pid;
                $coinsobj->coins = $back_coins;
                $coinsobj->date = strtotime("now");
                $redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
            endforeach;
            $redis->set('post:'.$pid.':guess:'.$g.':t',1);
        else :
            $redis->set('post:'.$pid.':guess:'.$g.':t',2);
        endif;
        //状态
        $total = 0;
        foreach($guess_args as $guess_num=>$guess) :
            if($guess['content'] && $guess['odds']>0 && $guess['total']>=0) :
                $total += $guess['total'];
            endif;
        endforeach;
        if($total>0) :
        else :
            $redis->hset('post:'.$pid,'guess_end',1);
        endif;
        $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=操作成功';
    else :
        $url = $redis->get('site_url').'?done=参数错误';
    endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
