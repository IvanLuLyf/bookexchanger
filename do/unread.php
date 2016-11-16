<?php
require 'functions.php';
if(maoo_user_id()) :
	$db = $redis->lrange('message:user:'.maoo_user_id(),0,19);
	$redis->ltrim('chat_id', 0, 19);
	foreach($db as $mes_id) :
		$redis->hdel('message:'.$mes_id,'unread');
	endforeach;
	$redis->hdel('user:'.maoo_user_id(),'message');
endif;
