<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
    $db = $redis->lrange('chat_id',0,99);
    foreach($db as $comment) :
        $redis->del('chat:'.$comment);
    endforeach;
    $redis->del('chat_id');
    $url = $redis->get('site_url').'?m=bbs&a=index&done=聊天记录清除完毕';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>