<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
?>
<?php 
			$count = $redis->zcard('date_bbs_id');
			$page_now = $_GET['page'];
			$page_size = 10;
			$max_page = ($count-$count%$page_size)/$page_size+1;
			if(empty($page_now) || $page_now<1) :
				$page_now = 1;
			elseif($page_now>$max_page) :
				$page_now = $max_page;
			else :
				$page_now = $_GET['page'];
			endif;
			$offset = ($page_now-1)*$page_size;
			$db = $redis->zrevrange('date_bbs_id',$offset,$offset+$page_size-1);

$posts = array();
foreach($db as $page_id) :
	$author = $redis->hget('bbs:'.$page_id,'author');
	$post->id = $page_id;
	$post->title = $redis->hget('bbs:'.$page_id,'title');
	$post->time = maoo_format_date($redis->hget('bbs:'.$page_id,'date'));
	$post->views = maoo_get_views($page_id);
	$post->fmimg = maoo_user_avatar($author);
	$post->userName = maoo_user_display_name($author);
	array_push($posts,$post);
	unset($post);
endforeach;
$json->count = $count;
$json->posts = $posts;
echo json_encode($json);
?>