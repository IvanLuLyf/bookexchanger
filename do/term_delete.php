<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_GET['id']>0) :
		$types = array('post','bbs','pro','deal');
		if(in_array($_GET['type'],$types)) :
			$id = $_GET['id'];
			$type = $_GET['type'];
			if($type=='pro') :
				$count = $redis->zcard('term_'.$type.'_id:'.$id);
			else : 
				$count = $redis->scard('term_'.$type.'_id:'.$id);
			endif;
			if($count>0) :
				$url = $redis->get('site_url').'?m=admin&a=term&done=无法删除有内容的分类';
			else :
				//$redis->multi();
				foreach($redis->zrange('term:'.$type,0,-1) as $title) : 
					if($redis->zscore('term:'.$type,$title)==$id) :
						$redis->zrem('term:'.$type,$title);
					endif;
				endforeach;
				$parent = $redis->hget('term:'.$type.':'.$id,'parent');
				$redis->srem('term:'.$type.':'.$parent.':child',$id);
				$redis->del('term:'.$type.':'.$id);
				//$redis->exec();
				$url = $redis->get('site_url').'?m=admin&a=term&done=删除分类成功';
			endif;
		else :
			$url = $redis->get('site_url').'?m=admin&a=term&done=分类参数有误';
		endif;
	else :
		$url = $redis->get('site_url').'?m=admin&a=term&done=分类参数有误';
	endif;
else :
	$url = $redis->get('site_url').'?done=请迅速撤离危险区域';
endif;
//Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>

