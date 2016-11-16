<?php  
require 'functions.php';
$now = strtotime("now");
$time = rand(1000,9999);
$last_time = $redis->get('pro:ordershow:time');
if(($last_time+$time)<$now) :
	$prolist = $redis->zrevrange('pro_id',0,99);
	$pro_key = array_rand($prolist,1);
	$pro_id = $prolist[$pro_key];
	if($pro_id>0) :
		$text = '用户'.maoo_rand(4).'**：购买了商品<a href="'.maoo_url('pro','single',array('id'=>$pro_id)).'">'.$redis->hget('pro:'.$pro_id,'title').'</a>';
		$redis->lpush('pro:ordershow',$text);
		$redis->ltrim('chat_id', 0, 6);
		$redis->set('pro:ordershow:time',$now);
	endif;
endif;
$db = $redis->lrange('pro:ordershow',0,6);
$ordershow_array = array();
foreach($db as $ordershow) :
	array_push($ordershow_array,$ordershow);
	unset($ordershow);
endforeach;
echo json_encode($ordershow_array);
?>