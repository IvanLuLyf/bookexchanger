<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
if($_GET['uid']>0 && $_GET['token']) :
    $id = $_GET['uid'];
	if($_GET['token']==$redis->hget('user:'.$id,'token')) :
        $now = strtotime("now");
        if($redis->hget('user:'.$id,'token_deadline')<($now-86400*3)) :
            $json->code = 505;
            $json->des = 'token 已过期,请重新登录';
        else :
            $redis->hset('user:'.$id,'token_deadline',$now);
            //json
            $json->code = 200;
            if($redis->hget('user:'.$id,'display_name')) :
                $json->displayName = $redis->hget('user:'.$id,'display_name');
            else :
                $json->displayName = '';
            endif;
            $json->userName = $redis->hget('user:'.$id,'user_name');
            if($redis->hget('user:'.$id,'description')) :
                $json->description = $redis->hget('user:'.$id,'description');
            else :
                $json->description = '';
            endif;
            $json->cash = maoo_user_cash($id);
            $json->coins = maoo_user_coins($id);
            $json->des = '用户信息获取成功';
            $json->uid = $id;
        endif;
	else :
		$json->code = 504;
        $json->des = '用户id或token错误';
	endif;
else :
	$json->code = 503;
    $json->des = '用户id或token未填写';
endif;
echo json_encode($json);
?>