<?php include('header.php'); ?>
<div class="container">
	<div class="row">
        <div class="col-sm-3 col">
            
            <div class="home-side-box side-comment-list">
					<h4 class="title mt-0 mb-10">
						最新评论
					</h4>
					<?php $comments = $redis->sort('comment_id',array('sort'=>'desc','limit'=>array(0,4))); ?>
					<ul class="media-list">
						<?php foreach($comments as $comment_id) : ?>
                        <?php 
                            $comment_post_id = $redis->hget('comment:'.$comment_id,'post');
                            $comment_type = $redis->hget('comment:'.$comment_id,'type');
                        ?>
						<li class="media">
							<?php $comment_user_id = $redis->hget('comment:'.$comment_id,'author'); ?>
							<div class="media-left">
								<a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$comment_user_id)); ?>">
									<img class="media-object" src="<?php echo maoo_user_avatar($comment_user_id); ?>" alt="<?php echo maoo_user_display_name($comment_user_id); ?>">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading mb-10">
									<a href="<?php echo maoo_url('user','index',array('id'=>$comment_user_id)); ?>"><?php echo maoo_user_display_name($comment_user_id); ?></a> :
								</h4>
								<div class="content mb-10">
									<?php echo $redis->hget('comment:'.$comment_id,'content'); ?>
								</div>
								<div class="time">
									<i class="glyphicon glyphicon-time"></i> <?php echo maoo_format_date($redis->hget('comment:'.$comment_id,'date')); ?>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
        </div>
		<div class="col-sm-6 col author-list">
			<div class="panel panel-default panel-author-list">
				<div class="panel-heading text-center">
					<i class="glyphicon glyphicon-th-list"></i> 活跃用户
				</div>
				<div class="panel-body">
					<ul class="media-list mb-0">
						<?php foreach($db as $rank_user_id) : ?>
						<li class="media">
							<div class="media-left">
								<a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$rank_user_id)); ?>">
									<img class="media-object" src="<?php echo maoo_user_avatar($rank_user_id); ?>" alt="<?php echo maoo_user_display_name($rank_user_id); ?>">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading">
									<a href="<?php echo maoo_url('user','index',array('id'=>$rank_user_id)); ?>"><?php echo maoo_user_display_name($rank_user_id); ?></a>
									<?php echo maoo_guanzhu_btn($rank_user_id,'pull-right'); ?>
								</h4>
                                <?php $activity = $redis->sort('user_activity_id:'.$rank_user_id,array('sort'=>'desc','limit'=>array(0,1))); if($activity[0]>0) : ?>
                                <?php echo $redis->hget('activity:'.$activity[0],'content'); ?>
                                <?php else : ?>
								<?php echo $redis->hget('user:'.$rank_user_id,'description'); ?>
                                <?php endif; ?>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
            <?php echo maoo_pagenavi($count,$page_now); ?>
		</div>
        <div class="col-sm-3 col hidden-xs hidden-sm">
            <div class="home-side-box side-latest-post">
				<h4 class="title mt-0 mb-10">
					最新文章
					<a class="pull-right" href="<?php echo maoo_url('post','latest'); ?>">更多</a>
				</h4>
				<ul class="media-list">
					<?php $db = $redis->sort('post_id',array('sort'=>'desc','limit'=>array(0,5))); ?>
					<?php foreach($db as $page_id) :  if($redis->hget('post:'.$page_id,'title')) : ?>
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
								<?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),21); ?>
							</div>
						</div>
					</li>
                    <?php elseif($redis->hget('post:'.$page_id,'pro')>0) : $pro_id = $redis->hget('post:'.$page_id,'pro'); $cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image'));  ?>
                    <li class="media">
						<div class="media-left">
							<a class="wto" href="<?php echo maoo_url('pro','single',array('id'=>$pro_id)); ?>">
								<img class="media-object" src="<?php echo $cover_images[1]; ?>" alt="<?php echo $redis->hget('pro:'.$pro_id,'title'); ?>">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('pro:'.$pro_id,'title'); ?></a>
							</h4>
							<div class="excerpt">
								<?php echo maoo_cut_str(strip_tags($redis->hget('pro:'.$pro_id,'content')),21); ?>
							</div>
						</div>
					</li>
                    <?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
            <?php if($redis->get('promod')!=1) : ?>
			<div class="home-side-box side-pro-list">
				<h4 class="title mt-0 mb-10">
					会员专购
					<a class="pull-right" href="<?php echo maoo_url('pro'); ?>">更多</a>
				</h4>
				<?php
					$db = $redis->zrevrange('pro_id',0,4);
				?>
				<ul class="media-list">
					<?php foreach($db as $page_id) : $cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
					<li class="media">
						<a class="media-left img-div" href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>">
							<img class="media-object" src="<?php echo $cover_images[1]; ?>">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('pro:'.$page_id,'title'); ?></a>
							</h4>
							<div class="price"><?php echo maoo_pro_min_price($page_id); ?>元</div>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
            <?php endif; ?>
        </div>
	</div>
</div>
<?php include('footer.php'); ?>