<?php 
header('Content-Type: text/html; charset=UTF-8'); 
define( 'WB_AKEY' , $redis->get('user:connect:weibo:appkey') ); 
define( 'WB_SKEY' , $redis->get('user:connect:weibo:appsecret') ); 
define( 'WB_CALLBACK_URL' , $redis->get('site_url').'/public/connect-weibo' );