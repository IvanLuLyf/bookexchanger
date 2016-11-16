<?php  
require 'functions.php';
if(maoo_user_id()>0) :
	if($_POST['rankid']>0 && $_POST['rank']>0) :
		$pro_id = $redis->hget('cart:'.$_POST['rankid'],'pro_id');
		if($pro_id>0) :
			if($_POST['rank']==1 || $_POST['rank']==2 || $_POST['rank']==3 || $_POST['rank']==4 || $_POST['rank']==5) :
				$rank_to_pro_new['pro_id'] = $pro_id;
				$rank_to_pro_new['user_id'] = maoo_user_id();
				$rank_to_pro_new['rank'] = $_POST['rank'];
				$rank_to_pro_new['date'] = strtotime("now");
				$redis->multi();
				if($_POST['images'][1] || $_POST['images'][2] || $_POST['images'][3] || $_POST['images'][4]) :
					$rank_to_pro_new['images'] = serialize($_POST['images']);
					$redis->sadd('pro:imgrank',$_POST['rankid']);
				endif;
				if($_POST['content']) :
					$rank_to_pro_new['content'] = maoo_remove_html($_POST['content']);
				else :
					$rank_to_pro_new['content'] = '该用户没有留下任何评价';
				endif;
				$redis->hmset('cart:rank:'.$_POST['rankid'],$rank_to_pro_new);
				$redis->sadd('pro:rank:'.$pro_id,$_POST['rankid']);
				$redis->exec();
				$url = $redis->get('site_url').'?m=user&a=order&done=发表评价成功';
			else :
				$url = $redis->get('site_url').'?m=user&a=order&done=评分参数错误';
			endif;
		else :
			$url = $redis->get('site_url').'?m=user&a=order&done=订单参数错误';
		endif;
	else :
		$url = $redis->get('site_url').'?m=user&a=order&done=订单参数错误';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
Header("Location:$url");
?>