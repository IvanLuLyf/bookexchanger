<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if($_GET['id']>0) :
	$id = $_GET['id'];
	$json->title = $redis->hget('bbs:'.$id,'title');
	$json->content = $redis->hget('bbs:'.$id,'content');
	$json->error = 0;
	echo json_encode($json);
else :
	$json->error = 1;
	echo json_encode($json);
endif;
?>