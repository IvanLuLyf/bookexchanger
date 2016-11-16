<?php
require 'functions.php';
if($_GET['id']>0 && maoo_user_id()) : 
	$id = $_GET['id'];
	$user_id = maoo_user_id();
    $redis->sadd('activity_zan_id:'.$id,$user_id);
endif;
