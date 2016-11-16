<?php
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if($_POST['user_name']!='' && $_POST['user_pass']!='' && $_POST['user_question']>0 && $_POST['user_answer']!='') :
	$date['user_name'] = maoo_remove_html($_POST['user_name']);
	$date['user_pass'] = sha1($_POST['user_pass']);
	$date['user_question'] = $_POST['user_question'];
	$date['user_answer'] = $_POST['user_answer'];
	$date['user_register_date'] = strtotime("now");
	$date['user_login_date'] = strtotime("now");
    $date['user_last_ip'] = maoo_user_ip();
	//判断用户名是否存在
	if($redis->zscore('user_id_name',$date['user_name'])>0) :
		$json->code = 501;
        $json->des = '用户名已存在';
	else :
		if(strlen($date['user_name'])>15) :
			$json->code = 503;
            $json->des = '用户名过长';
		elseif(strpos($date['user_name']," ")) :
            $json->code = 504;
            $json->des = '用户名不得包含空格';
		elseif($date['user_name']>0) :
            $json->code = 505;
            $json->des = '用户名不得为纯数字';
		else :
			$id = $redis->incr('user_id_incr');
			//如果还没有用户，则注册用户为管理员
			if($redis->zcard('user_id_name')>0) {
				$date['user_level'] = 1;
				$date['rank2'] = 10;
			} else {
				$date['user_level'] = 10;
				$date['rank2'] = 100;
			};
			$date['rank1'] = 1000;
			//用户数据写入数据库
			if($redis->zadd('user_id_name',$id,$date['user_name'])) :
				if($_SESSION['connect_qq']!='') :
					$redis->zadd('user:connect:qq',$id,$_SESSION['connect_qq']);
					$date['connect_qq'] = $_SESSION['connect_qq'];
				endif;
				if($_SESSION['connect_weibo']!='') :
					$redis->zadd('user:connect:weibo',$id,$_SESSION['connect_weibo']);
					$date['connect_weibo'] = $_SESSION['connect_weibo'];
				endif;
				//积分开始
				$redis->hset('user:'.$id,'coins',maoo_coins_register());
				$redis->hset('user:'.$id,'user_coins_date',$date['user_login_date']);
				$coinsobj->des = '注册';
				$coinsobj->coins = maoo_coins_register();
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$id,serialize($coinsobj));
				//积分结束
				$redis->hmset('user:'.$id,$date);
				$redis->lpush('new_user_id',$id);
				$redis->ltrim('new_user_id',0,1199);
				//json
                $now = $date['user_login_date'];
                $redis->hset('user:'.$id,'token',maoo_rand(20));
                $redis->hset('user:'.$id,'token_deadline',$now);
                $json->code = 200;
                $json->token = $redis->hget('user:'.$id,'token');
                $json->des = '注册成功';
                $json->uid = $id;
			else :
				$json->code = 501;
                $json->des = '用户名已存在';
			endif;
		endif;
	endif;
else :
	$json->code = 502;
    $json->des = '用户名或密码未填写';
endif;
echo json_encode($json);
?>
