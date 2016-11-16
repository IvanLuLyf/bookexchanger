<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if($_POST['user_name'] && $_POST['user_pass']) :
	$user_name = $_POST['user_name'];
	$user_pass = sha1($_POST['user_pass']);
	$idname = $redis->zscore('user_id_name',$user_name);
	$idphone = $redis->zscore('user_id_phone',$user_name);
	if($idname>0) :
		$id = $idname;
	elseif($idphone>0) :
		$id = $idphone;
		$user_name = $redis->hget('user:'.$id,'user_name');
	endif;
	if($id>0) :
		$user_pass_true = $redis->hget('user:'.$id,'user_pass');
		if($user_pass==$user_pass_true) :
			$date['user_login_date'] = strtotime("now");
            $date['user_last_ip'] = maoo_user_ip();
			//积分开始
			$user_coins_date = $redis->hget('user:'.$id,'user_coins_date')+86400;
			if($user_coins_date<$date['user_login_date']) :
				$coins = maoo_user_coins($id);
				$redis->hset('user:'.$id,'coins',$coins+maoo_coins_every_day());
				$redis->hset('user:'.$id,'user_coins_date',$date['user_login_date']);
				$coinsobj->user_id = $id;
				$coinsobj->des = '登录';
				$coinsobj->coins = maoo_coins_every_day();
				$coinsobj->date = strtotime("now");
				$redis->lpush('coins:user:'.$id,serialize($coinsobj));
			endif;
			//积分结束
			$redis->hmset('user:'.$id,$date);
            //json
            $now = $date['user_login_date'];
            if($redis->hget('user:'.$id,'token_deadline')<($now-86400*3)) :
                $redis->hset('user:'.$id,'token',maoo_rand(20));
                $redis->hset('user:'.$id,'token_deadline',$now);
            endif;
			$json->code = 200;
            $json->token = $redis->hget('user:'.$id,'token');
            $json->des = '登录成功';
            $json->uid = $id;
		else :
			$json->code = 502;
            $json->des = '用户名或密码错误';
		endif;
	else :
		$json->code = 502;
        $json->des = '用户名或密码错误';
	endif;
else :
	$json->code = 502;
    $json->des = '用户名或密码未填写';
endif;
echo json_encode($json);
?>