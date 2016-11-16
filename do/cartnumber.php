<?php  
require 'functions.php';
if(maoo_user_id()) :
    $cart = $_POST['cart'];
    $number = $_POST['number'];
    if($number>0 && floor($number)==$number) :
        $number_now = $redis->hget('cart:'.$cart,'number');
        if($redis->hget('cart:'.$cart,'user_id')==maoo_user_id()) :
            $pid = $redis->hget('cart:'.$cart,'pro_id');
            $parameters = $redis->hget('pro:'.$pid,'parameter');
            if($parameters) :
                $parameters = unserialize($parameters);
                $name = $redis->hget('cart:'.$cart,'parameter');
                foreach($parameters as $key_par=>$parameter) :
                    if($parameter['name']==$name) :
                        $stock = $parameter['stock'];
                    endif;
                endforeach;
                if($stock>($number-$number_now)) :
                    $redis->hset('cart:'.$cart,'number',$number);
                    maoo_update_stock($pid,$name,$number_now-$number);
                    if(strstr($_POST['url'],'?')) :
                        $url = $_POST['url'].'&showcart=1&done=修改数量成功';
                    else :
                        $url = $_POST['url'].'?showcart=1&done=修改数量成功';
                    endif;
                else :
                    if(strstr($_POST['url'],'?')) :
                        $url = $_POST['url'].'&showcart=1&done=商品库存不足';
                    else :
                        $url = $_POST['url'].'?showcart=1&done=商品库存不足';
                    endif;
                endif;
            else :
                if(strstr($_POST['url'],'?')) :
                    $url = $_POST['url'].'&showcart=1&done=商品库存不足';
                else :
                    $url = $_POST['url'].'?showcart=1&done=商品库存不足';
                endif;
            endif;
        else :
            $url = $redis->get('site_url').'?done=您无权修改其他人购物车中的商品';
        endif;
    else :
        if(strstr($_POST['url'],'?')) :
             $url = $_POST['url'].'&showcart=1&done=商品数量必须大于0';
        else :
             $url = $_POST['url'].'?showcart=1&done=商品数量必须大于0';
        endif;
    endif;
else :
    $url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>