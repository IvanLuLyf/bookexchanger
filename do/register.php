<?php
require 'functions.php';
$_SESSION['user_referer'] = $_POST['user_referer'];
if($_POST['user_name']!='' && $_POST['user_pass']!='' && $_POST['user_question']>0 && $_POST['user_answer']!='') :
	$date['user_name'] = maoo_remove_html($_POST['user_name']);
	$date['user_pass'] = sha1($_POST['user_pass']);
	$date['user_question'] = $_POST['user_question'];
	$date['user_answer'] = $_POST['user_answer'];
	$date['user_register_date'] = strtotime("now");
	$date['user_login_date'] = strtotime("now");
    $date['user_last_ip'] = maoo_user_ip();
	//判断用户名是否存在
    $last_user = $redis->zrevrange('user_id_name',0,0);
    $last_user = $last_user[0];
    $last_user = $redis->zscore('user_id_name',$last_user);
    $last_user_ip = $redis->hget('user:'.$last_user,'user_last_ip');
    $last_user_date = $redis->hget('user:'.$last_user,'user_register_date')+600;
    $canreg = false;
    if($date['user_last_ip']!=$last_user_ip) :
        $canreg = true;
    else :
        if($last_user_date<$date['user_register_date']) :
            $canreg = true;
        endif;
    endif;
    if($canreg) :
        if(md5($_POST['vnum'].CODE_KEY)!=$_SESSION['vnum']) : 
            $url = $redis->get('site_url').'?m=user&a=register&done=验证问题错误&referer=1';
        else :
            if($redis->zscore('user_id_name',$date['user_name'])>0) :
                $url = $redis->get('site_url').'?m=user&a=register&done=用户名已存在&referer=1';
            else :
                if(strlen($date['user_name'])>15) :
                    $url = $redis->get('site_url').'?m=user&a=register&done=用户名过长&referer=1';
                elseif(strpos($date['user_name']," ")) :
                    $url = $redis->get('site_url').'?m=user&a=register&done=用户名不得包含空格&referer=1';
                elseif($date['user_name']>0) :
                    $url = $redis->get('site_url').'?m=user&a=register&done=用户名不得为纯数字&referer=1';
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
                        //登陆状态
                        $_SESSION['user_name'] = $date['user_name'];
                        $_SESSION['user_pass'] = $date['user_pass'];
                        //跳转页面
                        $_SESSION['vnum'] = null;
                        $done = '注册成功';
                        if($_POST['user_referer']) {
                            $url = $_POST['user_referer'];
                        } elseif($date['user_level']==10) {
                            $url = $redis->get('site_url').'?m=admin&a=index&done='.$done;
                        } else {
                            $url = $redis->get('site_url').'?m=user&a=index&id='.$id.'&done='.$done;
                        };
                    else :
                        $url = $redis->get('site_url').'?m=user&a=register&done=用户名已存在&referer=1';
                    endif;
                endif;
            endif;
        endif;
    else :
        $url = $redis->get('site_url').'?m=user&a=register&done=注册账号间隔不得超过10分钟&referer=1';
    endif;
else :
	$url = $redis->get('site_url').'?m=user&a=register&done=注册信息不完整&referer=1';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
