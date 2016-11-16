<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
?>
<?php 
			$count = $redis->zcard('rank_list');
			$page_now = $_GET['page'];
			$page_size = $_GET['page_size'];
			$max_page = ($count-$count%$page_size)/$page_size+1;
			if(empty($page_now) || $page_now<1) :
				$page_now = 1;
			elseif($page_now>$max_page) :
				$page_now = $max_page;
			else :
				$page_now = $_GET['page'];
			endif;
			$offset = ($page_now-1)*$page_size;
			$db = $redis->zrevrange('rank_list',$offset,$offset+$page_size-1);

$posts = array();
foreach($db as $page_id) :
	$author = $redis->hget('post:'.$page_id,'author');
	$post->ID = $page_id;
	$post->title = $redis->hget('post:'.$page_id,'title');
    $post->content = maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),33);
	$post->fmimg = maoo_fmimg($page_id);
	$post->time = maoo_format_date($redis->hget('post:'.$page_id,'date'));
	$post->views = maoo_get_views($page_id);
	$post->userName = maoo_user_display_name($author);
	$post->userAvatar = maoo_user_avatar($author);
    $post->likeCount = maoo_like_count($page_id);
    $post->topicID = $redis->hget('post:'.$page_id,'topic');
    $post->topicTitle = $redis->hget('topic:'.$redis->hget('post:'.$page_id,'topic'),'title');
	array_push($posts,$post);
	unset($post);
endforeach;
$json->count = $count;
$json->posts = $posts;
echo json_encode($json);
?>