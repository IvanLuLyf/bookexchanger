<?php
require 'functions.php';
if(maoo_user_id()) :
    $user_id = maoo_user_id();
    if($redis->hget('user:'.$user_id,'user_level')==10) :
        if(strstr($_POST['img'],$redis->get('site_url'))) :
            $img = str_replace($redis->get('site_url'),'',$_POST['img']);
            unlink(ROOT_PATH.$img);
            $redis->zrem('site_img_list',$_POST['img']);
            $url = $redis->get('site_url').'?m=admin&a=image&done=删除成功';
        else :
            $url = $redis->get('site_url').'?m=admin&a=image&done=暂不支持删除图片空间中的项目';
        endif;
    else :
        $url = $redis->get('site_url').'?done=你没有权限这么做';
    endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
