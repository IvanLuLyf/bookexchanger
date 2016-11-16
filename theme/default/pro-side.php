<div class="home-side-box side-latest-post">
				<h4 class="title mt-0 mb-10">
					最新文章
					<a class="pull-right" href="<?php echo maoo_url('post','latest'); ?>">更多</a>
				</h4>
				<ul class="media-list">
					<?php $db = $redis->sort('post_id',array('sort'=>'desc','limit'=>array(0,5))); ?>
					<?php foreach($db as $page_id) : ?>
					<li class="media">
						<div class="media-left">
							<a class="wto" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
								<img class="media-object" src="<?php echo maoo_fmimg($page_id); ?>" alt="<?php echo $redis->hget('post:'.$page_id,'title'); ?>">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('post:'.$page_id,'title'); ?></a>
							</h4>
							<div class="excerpt">
								<?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),30); ?>
							</div>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="home-side-box side-topic-list">
				<h4 class="title mt-0 mb-10">
					热门话题
					<a class="pull-right" href="<?php echo maoo_url('post','topic'); ?>">更多</a>
				</h4>
				<?php $db = $redis->sort('topic_id',array('sort'=>'desc','limit' =>array(0,10))); ?>
				<?php foreach($db as $topic_id) : ?>
				<a class="side-topic" href="<?php echo maoo_url('post','topic',array('id'=>$topic_id)); ?>"><?php echo $redis->hget('topic:'.$topic_id,'title'); ?></a>
				<?php endforeach; ?>
			</div>
			<div class="home-side-box side-user-rank-list">
				<h4 class="title mt-0 mb-10">
					推荐作者
					<a class="pull-right" href="<?php echo maoo_url('index','authors'); ?>">更多</a>
				</h4>
				<?php 
					$db = $redis->zrevrange('user_rank_list',0,4);
				?>
				<ul class="media-list">
					<?php foreach($db as $rank_user_id) : ?>
					<li class="media">
						<div class="media-left">
							<a href="<?php echo maoo_url('user','index',array('id'=>$rank_user_id)); ?>">
								<img class="media-object" src="<?php echo maoo_user_avatar($rank_user_id); ?>" alt="<?php echo maoo_user_display_name($rank_user_id); ?>">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('user','index',array('id'=>$rank_user_id)); ?>"><?php echo maoo_user_display_name($rank_user_id); ?></a>
							</h4>
							<?php echo $redis->hget('user:'.$rank_user_id,'description'); ?>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>