<?php  
require 'functions.php';
if(maoo_user_id()>0) :
    $user_id = maoo_user_id();
	if($_POST['id']>0 && $_POST['content']!='') :
		$author = $redis->hget('deal:'.$_POST['id'],'author');
		if($author==$user_id) :
			$update['date'] = strtotime("now");
			if($_POST['images'][1] || $_POST['images'][2] || $_POST['images'][3] || $_POST['images'][4]) :
				$update['images'] = serialize($_POST['images']);
            endif;
            $update['content'] = maoo_remove_html($_POST['content']);
            $update_id = $redis->incr('deal:update:id_incr');
            $redis->hmset('deal:update:'.$update_id,$update);
            $redis->sadd('deal:updatelist:'.$_POST['id'],$update_id);
            $url = $redis->get('site_url').'?m=deal&a=single&id='.$_POST['id'].'&done=添加进展成功';
		else :
			$url = $redis->get('site_url').'?m=user&a=order&done=你无权更新这个项目';
		endif;
	else :
		$url = $redis->get('site_url').'?m=deal&a=single&id='.$_POST['id'].'&done=进展详情必须填写';
	endif;
else :
	$url = $redis->get('site_url').'?m=user&a=login&done=请先登录';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>