<?php
require 'functions.php';
if(maoo_user_id()>0) :
	$user_id = maoo_user_id();
    if($redis->hget('user:'.maoo_user_id(),'user_level')!=10) :
	foreach($_POST['page'] as $page_key=>$page_val) :
        $legal_keys = array('title','content','term','goal','fmimg','deadline');
        if(!in_array($page_key,$legal_keys)) :
            unset($_POST['page'][$page_key]);
        endif;
	endforeach;
    endif;
	$_POST['page']['title'] = maoo_remove_html($_POST['page']['title']);
	$_POST['page']['content'] = maoo_str_replace_base64($_POST['page']['content']);
    $fmimg = $_POST['page']['fmimg'];
		if($_POST['page']['title'] && $_POST['page']['content'] && $_POST['page']['term']>0) :
			if($_POST['id']>0) : //编辑
                $_POST['page']['fmimg'] = maoo_remove_html($fmimg,'all');
                $id = $_POST['id'];

				//格式化初始数据
				$_POST['page']['deadline'] = strtotime($_POST['page']['deadline']);
				
                if($_POST['reward']) :
                    $rewards = array();
                    foreach($_POST['reward'] as $reward) :
                        if($reward['price']>0 && $reward['number']>0) :
                            array_push($rewards,$reward);
                        endif;
                        unset($reward);
                    endforeach;
                    $_POST['page']['reward'] = serialize($rewards);
                    $_POST['page']['pending'] = 1;
                    
                    $redis->sadd('deal_id',$id);
                    $redis->sadd('term_deal_id:'.$_POST['page']['term'],$id);

                    $term_id = $redis->hget('deal:'.$id,'term');
                    if($term_id!=$_POST['page']['term']) :
						$redis->srem('term_deal_id:'.$term_id,$id);
					endif;

                    //更新文章
                    $redis->hmset('deal:'.$id,$_POST['page']);
                    $redis->srem('deal_pending_id',$id);
                    $url = $redis->get('site_url').'?m=deal&a=single&done=项目已更新&id='.$id;
                else :
					$url = $redis->get('site_url').'?m=deal&a=publish&done=项目回报必须填写';
				endif;
			else : //新建
                $_POST['page']['fmimg'] = maoo_remove_html($fmimg,'all');
				$id = $redis->incr('deal_id_incr');
				$_POST['page']['date'] = strtotime("now");
                $_POST['page']['author'] = $user_id;

				//格式化初始数据
				$_POST['page']['views'] = 0;
				$_POST['page']['total'] = 0;
				$_POST['page']['deadline'] = strtotime($_POST['page']['deadline']);
                //项目回报
                if($_POST['reward']) :
                    $rewards = array();
                    foreach($_POST['reward'] as $reward) :
                        if($reward['price']>0 && $reward['number']>0) :
                            $reward['count'] = 0;
                            array_push($rewards,$reward);
                        endif;
                        unset($reward);
                    endforeach;
                    $_POST['page']['reward'] = serialize($rewards);
					$redis->hmset('deal:'.$id,$_POST['page']);
                    $redis->sadd('user_deal_id:'.$user_id,$id);
                    $redis->sadd('deal_pending_id',$id);
					$url = $redis->get('site_url').'?m=user&a=deal&done=项目提交成功，请耐心等待审核';
				else :
					$url = $redis->get('site_url').'?m=deal&a=publish&done=项目回报必须填写';
				endif;
			endif;
		else :
			if($_POST['id']>0) :
				$url = $redis->get('site_url').'?m=deal&a=publish&done=必须设置标题、内容以及分类&id='.$_POST['id'];
			else :
				$url = $redis->get('site_url').'?m=deal&a=publish&done=必须设置标题、内容以及分类';
			endif;
		endif;
else :
	$url = $redis->get('site_url').'?done=请先登录';
endif;
$url = urldecode($url);
Header("Location:$url");
?>
<!DOCTYPE html><html lang="zh-CN"><meta http-equiv="refresh" content="0;url=<?php echo $url; ?>"><head><meta charset="utf-8"><title>Mao10CMS</title></head><body></body></html>
