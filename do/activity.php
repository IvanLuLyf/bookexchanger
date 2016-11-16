<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
	if($_POST['content']) :
        if($_POST['img']) {
            $date['imgs'] = maoo_serialize($_POST['img']);  
        };
        $date['content'] = $_POST['content'];
        $date['date'] = strtotime("now");
        $date['author'] = $user_id;
        $id = $redis->incr('activity_id_incr');
        $date['id'] = $id;
        $redis->hmset('activity:'.$id,$date);
        $redis->sadd('activity_id',$id);
        $redis->sadd('user_activity_id:'.$user_id,$id);
        $url = $redis->get('site_url').'?m=user&a=index&done=动态发布成功#'.$id;
    else :
        $url = $redis->get('site_url').'?m=user&a=index&done=动态内容必须填写';
    endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
