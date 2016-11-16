<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	if($_GET['pid']>0 && $_GET['coins']>0) :
		$pid = $_GET['pid'];
        if($_GET['type']=='user') :
            $author = $pid;
        else :
            $author = $redis->hget('post:'.$pid,'author');
        endif;
		if($author>0 && $author!=$user_id && $redis->hget('user:'.$user_id,'user_last_ip')!=$redis->hget('user:'.$author,'user_last_ip')) :
			$coins = maoo_user_coins($user_id);
			$coins2 = maoo_user_coins($author);
			if($coins>=$_GET['coins']) :
				$redis->hset('user:'.$user_id,'coins',$coins-$_GET['coins']);
				$coinsobj->des = '打赏';
				$coinsobj->user_id = $author;
				$coinsobj->coins = -$_GET['coins'];
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
				$redis->hset('user:'.$author,'coins',$coins2+$_GET['coins']);
				$coinsobj->des = '被打赏';
				$coinsobj->user_id = $user_id;
				$coinsobj->coins = $_GET['coins'];
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$author,serialize($coinsobj));
                if($_GET['type']=='user') :
                    $url = $redis->get('site_url').'?m=user&a=index&id='.$pid.'&done=打赏成功';
                else :
				    $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=打赏成功#post-author-box';
                endif;
			else :
				if($coins>0) :
					$redis->hset('user:'.$user_id,'coins',0);
					$coinsobj->des = '打赏';
					$coinsobj->user_id = $author;
					$coinsobj->coins = -$coins;
					$coinsobj->date = strtotime("now");
					$redis->lpush('coins:user:'.$user_id,serialize($coinsobj));
					$redis->hset('user:'.$author,'coins',$coins2+$coins);
					$coinsobj->des = '被打赏';
					$coinsobj->user_id = $user_id;
					$coinsobj->coins = $coins;
					$coinsobj->date = strtotime("now");
					$redis->lpush('coins:user:'.$author,serialize($coinsobj));
                    if($_GET['type']=='user') :
                        $url = $redis->get('site_url').'?m=user&a=index&id='.$pid.'&done=已将您全部积分共 '.$coins.' 打赏出去啦';
                    else :
                        $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=已将您全部积分共 '.$coins.' 打赏出去啦#post-author-box';
                    endif;
				else :
                    if($_GET['type']=='user') :
                        $url = $redis->get('site_url').'?m=user&a=index&id='.$pid.'&done=您没有积分哦';
                    else :
                        $url = $redis->get('site_url').'?m=post&a=single&id='.$pid.'&done=您没有积分哦#post-author-box';
                    endif;
				endif;
			endif;
		else:
			$url = $redis->get('site_url').'?m=user&a=index&done=不能打赏自己哦';
		endif;
	else:
		$url = $redis->get('site_url').'?m=user&a=index&done=打赏积分必须大于0';
	endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
