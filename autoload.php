<?php
error_reporting(0);
session_start();
header("Content-Type: text/html; charset=UTF-8");

//定义根目录
define('ROOT_PATH',dirname(__FILE__));
define('DB_TYPE','mysql');

//数据库连接
if(DB_TYPE=='mysql') :
	/*
		开发时需注意:
		- mysql操作类中，大部分”增改删“操作只返回true和false
		- sort只对set表有效
		- 各类型表中，name未经过重名限制，请人工避免name重复
	*/
	require __DIR__.'/mysql.php';
	try {
		$redis = new Mao10Mysql;
		$redis->connect('192.168.1.11','4332a507-5e0f','4332a507-5e0f','ddf507ba229bd417bab97dfc7f3c02901'); //在此处设置数据库配置信息  ！！！注意：请保证已经建立了一个空数据库用于安装！！！
		$redis->setprefix('maoo_');
        $redis->flush();
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
endif;
