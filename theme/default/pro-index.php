<?php include('header.php'); ?>
<div class="container pro-page">
	<div class="row mb-20 pro-home-top">
		<div class="col-xs-3 col hidden-xs hidden-sm">
			<div class="list-group mb-0 nav-pro-term-list">
				<?php foreach($redis->zrange('term:pro',0,6) as $title) : $term_id = $redis->zscore('term:pro',$title); ?>
				<a class="list-group-item" href="<?php echo maoo_url('pro','term',array('id'=>$term_id)); ?>">
					<i class="glyphicon glyphicon-record"></i> <?php echo $title; ?>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col-md-9 col">
			<?php if($redis->get('slider_pro:img:1')) : ?>
			<div id="carousel-home" class="carousel slide animated zoomIn" data-ride="carousel">
				<?php if($redis->get('slider_pro:img:2')) : ?>
				<ol class="carousel-indicators">
					<li data-target="#carousel-home" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-home" data-slide-to="1"></li>
					<?php if($redis->get('slider_pro:img:3')) : ?>
					<li data-target="#carousel-home" data-slide-to="2"></li>
					<?php endif; ?>
				</ol>
				<?php endif; ?>
				<div class="carousel-inner" role="listbox">
					<a class="item active" href="<?php echo $redis->get('slider_pro:link:1'); ?>" style="background-image: url(<?php echo $redis->get('slider_pro:img:1'); ?>);"></a>
					<?php if($redis->get('slider_pro:img:2')) : ?>
					<a class="item" href="<?php echo $redis->get('slider_pro:link:2'); ?>" style="background-image: url(<?php echo $redis->get('slider_pro:img:2'); ?>);"></a>
					<?php endif; ?>
					<?php if($redis->get('slider_pro:img:3')) : ?>
					<a class="item" href="<?php echo $redis->get('slider_pro:link:3'); ?>" style="background-image: url(<?php echo $redis->get('slider_pro:img:3'); ?>);"></a>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<h3 class="title mb-20 hidden-xs hidden-sm">
		热销商品
	</h3>
	<div class="row hidden-xs hidden-sm">
		<div class="col-xs-3 col">
			<a class="img-div home-pro-list-side-1" href="<?php echo $redis->get('slider_pro:link:4'); ?>">
				<img src="<?php echo $redis->get('slider_pro:img:4'); ?>" />
			</a>
		</div>
		<div class="col-md-9 col home-pro-list pro-term">
			<div class="row shop-allpro-list">
			<?php
						$db = $redis->zrevrange('pro_id',0,5);
					?>
			<?php foreach($db as $page_id) : $cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
			<div class="col-xs-6 col-md-4 col">
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
		</div>
	</div>
	<?php foreach($redis->zrange('term:pro',0,-1) as $title) : $term_id = $redis->zscore('term:pro',$title); if($redis->zcard('term_pro_id:'.$term_id)>0) : ?>
	<h3 class="title mb-20">
		<?php echo $title; ?>
		<a class="pull-right" href="<?php echo maoo_url('pro','term',array('id'=>$term_id)); ?>">
			更多 <i class="glyphicon glyphicon-menu-right"></i>
		</a>
	</h3>
	<div class="row">
		<div class="col-xs-12 col pro-term">
			<div class="row shop-allpro-list">
			<?php
					$db = $redis->zrevrange('term_pro_id:'.$term_id,0,3);
			?>
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
		</div>
	</div>
	<?php endif; endforeach; ?>
</div>
<?php if($_GET['m']=='index' || $_GET['m']=='') : ?>
<div class="link-box" style="padding-top:20px; padding-bottom:20px">
    <div class="container">
        <div class="link-box-in">
            <?php echo maoo_link(); ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php include('footer.php'); ?>
