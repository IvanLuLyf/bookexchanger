<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
    if($_POST['every_day']>=0) :
        $redis->set('coins:every_day',$_POST['every_day']);
    endif;
    if($_POST['register']>=0) :
	   $redis->set('coins:register',$_POST['register']);
    endif;
    if($_POST['cash_to_coins']>0) :
	   $redis->set('coins:cash_to_coins',$_POST['cash_to_coins']);
    endif;
    if($_POST['pay_coins_limit']>0) :
	   $redis->set('coins:pay_coins_limit',$_POST['pay_coins_limit']);
    endif;
	$url = $redis->get('site_url').'?m=admin&a=coinsset&done=设置修改成功';
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
