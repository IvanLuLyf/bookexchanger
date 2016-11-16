<?php include('header.php'); ?>
<div class="container pro-page">
	<div class="row">
		<div class="col-xs-12 col pro-term">
			<ul class="nav nav-pills nav-pro-term-list">
				<li role="presentation">
					<a href="<?php echo maoo_url('pro'); ?>">
						全部
					</a>
				</li>
				<?php foreach($redis->zrange('term:pro',0,-1) as $title) : $term_id = $redis->zscore('term:pro',$title); ?>
				<li role="presentation" class="<?php if($term_id==$id) echo 'active'; ?>">
					<a href="<?php echo maoo_url('pro','term',array('id'=>$term_id)); ?>">
						<?php echo $title; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
			<div class="row shop-allpro-list">
			<?php foreach($db as $page_id) : $cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
			<div class="col-xs-6 col-md-3 col">
				<div class="thumbnail">
								<a class="img-div" href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><img src="<?php echo $cover_images[1]; ?>" alt="<?php echo $redis->hget('pro:'.$page_id,'title'); ?>"></a>
								<div class="caption">
									<h4 class="title">
										<a class="wto" href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('pro:'.$page_id,'title'); ?></a>
									</h4>
									<div class="price"><?php echo maoo_pro_min_price($page_id); ?>元</div>
								</div>
							</div>
			</div>
			<?php endforeach; ?>
			</div>
			<?php echo maoo_pagenavi($count,$page_now); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
