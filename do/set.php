<?php
require 'functions.php';
if(maoo_user_id()) :
	if($_POST['user_id']==maoo_user_id()) :
        if($_POST['display_name']) :
            $new_display_name = maoo_remove_html($_POST['display_name'],'all');
            if($redis->zscore('user_id_display_name',$new_display_name)>0 && $redis->zscore('user_id_display_name',$new_display_name)!=$_POST['user_id']) :
                $url = $redis->get('site_url').'?m=user&a=set&id='.$_POST['user_id'].'&done=昵称已被使用';
                echo '<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url='.$url.'"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>';
                exit;
            else :
                $display_name = $redis->hget('user:'.$_POST['user_id'],'display_name');
                $redis->zrem('user_id_display_name',$display_name);
                $redis->zadd('user_id_display_name',$_POST['user_id'],$new_display_name);
                $redis->hset('user:'.$_POST['user_id'],'display_name',$new_display_name);
            endif;
        else :
            $display_name = $redis->hget('user:'.$_POST['user_id'],'display_name');
            $redis->zrem('user_id_display_name',$display_name);
            $redis->hset('user:'.$_POST['user_id'],'display_name','');
        endif;
		$redis->hset('user:'.$_POST['user_id'],'avatar',maoo_remove_html($_POST['avatar'],'all'));
		$redis->hset('user:'.$_POST['user_id'],'description',maoo_remove_html($_POST['description'],'all'));
		if($redis->hget('user:'.$_POST['user_id'],'user_level')==10) :
			if($_POST['coins']>0) :
				$redis->hset('user:'.$_POST['user_id'],'coins',$_POST['coins']);
			endif;
			$url = $redis->get('site_url').'?m=user&a=set&id='.$_POST['user_id'].'&done=资料修改成功';
		else :
			$url = $redis->get('site_url').'?m=user&a=set&done=资料修改成功';
		endif;
	elseif($_POST['user_id']>0) :
		if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
            $display_name = $redis->hget('user:'.$_POST['user_id'],'display_name');
            $redis->zrem('user_id_display_name',$display_name);
            $redis->zadd('user_id_display_name',$_POST['user_id'],maoo_remove_html($_POST['display_name'],'all'));
			$redis->hset('user:'.$_POST['user_id'],'display_name',maoo_remove_html($_POST['display_name'],'all'));
			$redis->hset('user:'.$_POST['user_id'],'avatar',maoo_remove_html($_POST['avatar'],'all'));
			if($_POST['coins']>0) :
				$redis->hset('user:'.$_POST['user_id'],'coins',$_POST['coins']);
			endif;
			if($_POST['user_level']>0) :
				$redis->hset('user:'.$_POST['user_id'],'user_level',$_POST['user_level']);
			endif;
			$url = $redis->get('site_url').'?m=admin&a=user&id='.$_POST['user_id'].'&done=资料修改成功';
		else :
			$url = $redis->get('site_url').'?done=你没有权限修改其他人的资料';
		endif;
	else :
		$url = $redis->get('site_url').'?done=参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
