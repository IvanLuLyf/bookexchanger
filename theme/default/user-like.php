<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
            <?php include_once('user-nav-1.php'); ?>
			
					<div class="post-list">
						<?php foreach($db as $page_id) : ?>
						<div class="post-<?php echo $id; ?> post mb-20">
							<a class="pull-left" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
								<img class="mb-10" src="<?php echo maoo_fmimg($page_id); ?>">
							</a>
							<div class="post-right">
								<h2 class="title">
									<a href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
										<?php echo $redis->hget('post:'.$page_id,'title'); ?>
									</a>
								</h2>
								<?php $author = $redis->hget('post:'.$page_id,'author'); ?>
								<div class="author mb-10">
									<a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a> • <?php echo date('Y/m/d',$redis->hget('post:'.$page_id,'date')); ?>
								</div>
								<div class="entry mb-10">
									<?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),70); ?>
								</div>
								<ul class="list-inline mb-0">
									<?php if($redis->hget('post:'.$page_id,'topic')>0) : ?>
									<li><i class="glyphicon glyphicon-paperclip"></i> <a href="<?php echo maoo_url('post','topic',array('id'=>$redis->hget('post:'.$page_id,'topic'))); ?>"><?php echo $redis->hget('topic:'.$redis->hget('post:'.$page_id,'topic'),'title'); ?></a></li>
									<?php endif; ?>
									<li><i class="glyphicon glyphicon-heart"></i> <?php echo maoo_like_count($page_id); ?></li>
									<li><i class="glyphicon glyphicon-eye-open"></i> <?php echo maoo_get_views($page_id); ?></li>
								</ul>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php endforeach; ?>
					</div>
        </div>
	</div>
</div>
<?php include('footer.php'); ?>