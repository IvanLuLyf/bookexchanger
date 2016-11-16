<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
            <?php include_once('user-nav-1.php'); ?>
					<ul class="media-list guanzhu-list">
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
									<?php echo maoo_guanzhu_btn($rank_user_id); ?>
								</h4>
								<?php echo $redis->hget('user:'.$rank_user_id,'description'); ?>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>