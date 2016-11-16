<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
    if($_POST['page']['pingxx_alipay']=='') :
        $_POST['page']['pingxx_alipay'] = 0;
    endif;
    if($_POST['page']['pingxx_yinlian']=='') :
        $_POST['page']['pingxx_yinlian'] = 0;
    endif;
	$redis->hmset('payset',$_POST['page']);
	$url = $redis->get('site_url').'?m=admin&a=pay&done=设置成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
