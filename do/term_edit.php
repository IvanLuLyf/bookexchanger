<?php
require 'functions.php';
if($redis->hget('user:'.maoo_user_id(),'user_level')==10) :
	if($_POST['id']>0) :
		$types = array('post','bbs','pro','deal');
		if(in_array($_POST['type'],$types)) :
			$id = $_POST['id'];
			$type = $_POST['type'];
			if($_POST['page']['title']!='') :
				//$redis->multi();
				foreach($redis->zrange('term:'.$type,0,-1) as $title) :
					if($redis->zscore('term:'.$type,$title)==$id) :
						$redis->zrem('term:'.$type,$title);
					endif;
				endforeach;
				$redis->zadd('term:'.$type,$id,$_POST['page']['title']);
				$parent = $redis->hget('term:'.$type.':'.$id,'parent');
				if($_POST['page']['parent']>0 && $redis->hget('term:'.$type.':'.$id,'parent')!=$_POST['page']['parent']) :
					$redis->srem('term:'.$type.':'.$parent.':child',$id);
					$parent_new = $_POST['page']['parent'];
					$redis->sadd('term:'.$type.':'.$parent_new.':child',$id);
                    //父分类内商品
                    $db = $redis->zrevrange('term_pro_id:'.$id,0,-1);
                    foreach($db as $pro_id) :
                        $redis->zrem('term_pro_id:'.$parent,$pro_id);
                        $redis->zadd('term_pro_id:'.$parent_new,$redis->hget('pro:'.$pro_id,'inlist'),$pro_id);
                    endforeach;
				else :
					$redis->srem('term:'.$type.':'.$parent.':child',$id);
				endif;
				$redis->hmset('term:'.$type.':'.$id,$_POST['page']);
				//$redis->exec();
				$url = $redis->get('site_url').'?m=admin&a=termedit&id='.$id.'&type='.$type.'&done=编辑分类成功';
			else :
				$url = $redis->get('site_url').'?m=admin&a=termedit&id='.$id.'&type='.$type.'&done=分类标题必须填写';
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
