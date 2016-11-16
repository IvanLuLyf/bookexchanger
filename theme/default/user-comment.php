<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
			<?php include_once('user-nav-1.php'); ?>
			<ul class="media-list comment-list mb-20">
				<?php foreach($db as $comment) : $comment_type = $redis->hget('comment:'.$comment,'type'); ?>
				<li class="media" id="comment-<?php echo $comment; ?>">
								<div class="media-left">
									<img class="media-object" src="<?php echo maoo_user_avatar($user_id); ?>">
								</div>
								<div class="media-body">
									<h4 class="media-heading mb-10"><?php echo maoo_user_display_name($user_id); ?></h4>
									<div class="mb-10"><?php echo $redis->hget('comment:'.$comment,'content'); ?></div>
									<div class="clearfix"></div>
									<ul class="list-inline mb-0">
										<?php if($comment_type) : ?>
										<li>
											<i class="glyphicon glyphicon-pushpin"></i> <a href="<?php echo maoo_url($comment_type,'single',array('id'=>$redis->hget('comment:'.$comment,'post'))); ?>"><?php echo $redis->hget($comment_type.':'.$redis->hget('comment:'.$comment,'post'),'title'); ?></a>
										</li>
										<?php endif; ?>
										<li>
											<i class="glyphicon glyphicon-time"></i> <?php echo date('Y-m-d H:i',$redis->hget('comment:'.$comment,'date')); ?>
										</li>
									</ul>
								</div>
							</li>
				<?php endforeach; ?>
			</ul>
			<?php echo maoo_pagenavi($count,$page_now); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>