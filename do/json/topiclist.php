<?php  
header('Access-Control-Allow-Origin: *');
require '../functions.php';
?>
<?php 
												$count = $redis->zcard('topic_rank_list');
												$db_rank = $redis->zrevrange('topic_rank_list',0,9);
												if($db_rank) :
													$db = $db_rank;
												else :
													$db = $redis->sort('user_topic_id:'.$_GET['uid'],array('sort'=>'desc'));
												endif;
												$topic_array = array();
												foreach($db as $s_topic_id) : if($s_topic_id>0) :
													$topic->topic_id = $s_topic_id;
													$topic->topic_title = $redis->hget('topic:'.$s_topic_id,'title');
													array_push($topic_array, $topic);
													unset($topic);
												endif; endforeach;
												echo json_encode($topic_array);
											?>
											