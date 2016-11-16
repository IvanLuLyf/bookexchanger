<?php include('header.php'); ?>
<div class="show-rank-list mb-50">
	<div class="container">
		<ol class="breadcrumb mb-20">
			<li>
				<a href="<?php echo $redis->get('site_url'); ?>">
					首页
				</a>
			</li>
			<li>
				<a href="<?php echo maoo_url('pro'); ?>">
					全部商品
				</a>
			</li>
			<li class="active">
				买家晒单
			</li>
		</ol>
		<?php if($db) : ?>
		<div class="grid row">
			<?php 
			foreach($db as $rank_to_pro) : 
			$rank_pro_id = $redis->hget('cart:rank:'.$rank_to_pro,'pro_id');
			$rank_user_id = $redis->hget('cart:rank:'.$rank_to_pro,'user_id');
			$rank_images = unserialize($redis->hget('cart:rank:'.$rank_to_pro,'images'));
			?>
			<div class="grid-item col-xs-6 col-sm-4 col-md-3 col">
				<div class="rank-box">
					<?php foreach($rank_images as $rank_image) : if($rank_image) : ?>
					<a href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $rank_pro_id; ?>#rank-<?php echo $rank_to_pro; ?>" rel="nofollow" class="img-div mb-10"><img src="<?php echo $rank_image; ?>"></a>
					<?php endif; endforeach; ?>
					<div class="media mb-10">
						<div class="media-left">
							<a class="img-div" href="<?php echo $redis->get('site_url'); ?>?m=user&a=index&id=<?php echo $rank_user_id; ?>">
								<img class="media-object" src="<?php echo maoo_user_avatar($rank_user_id); ?>">
							</a>
						</div>
						<div class="media-body">
							<a href="<?php echo $redis->get('site_url'); ?>?m=user&a=index&id=<?php echo $rank_user_id; ?>"><?php echo maoo_user_display_name($rank_user_id); ?></a> 购买了 <a href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $rank_pro_id; ?>#rank-<?php echo $rank_to_pro; ?>"><?php echo $redis->hget('pro:'.$rank_pro_id,'title'); ?></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="content">
						<?php echo maoo_cut_str(strip_tags($redis->hget('cart:rank:'.$rank_to_pro,'content')),150); ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php echo maoo_pagenavi($count,$page_now,30); ?>
		<?php else : ?>
		<div class="nothing">
			暂无任何买家晒单！
		</div>
		<?php endif; ?>
	</div>
</div>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/masonry.pkgd.min.js"></script>
<script src="//cdn.bootcss.com/jquery.imagesloaded/3.2.0/imagesloaded.min.js"></script>
<script>
	$(function(){  
	    var $container = $('.grid');  
	    $container.imagesLoaded( function(){  
	            $container.masonry({  
	                itemSelector : '.grid-item'  
	        });  
	    });  
	});
</script>
<?php include('footer.php'); ?>