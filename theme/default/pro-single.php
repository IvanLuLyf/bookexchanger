<?php include('header.php'); ?>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col">
			<div class="shop-pro-content">
				<div class="panel panel-default panel-pro-single">
					<div class="panel-body">
						<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/add_cart.php">
						<div class="row">
					<div class="col-md-6 col">
						<?php $cover_images = unserialize($redis->hget('pro:'.$id,'cover_image')); ?>
						<div id="carousel-pro-generic" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
								<?php foreach($cover_images as $cover_key=>$img) : if($img) : ?>
								<div class="item <?php if($cover_key==1) echo 'active'; ?>">
									<div class="imgshow"><img src="<?php echo $img; ?>" alt="<?php echo $pro['title']; ?>"></div>
								</div>
								<?php endif; endforeach; ?>
							</div>
							<ol class="carousel-indicators">
								<?php foreach($cover_images as $cover_key=>$img) : if($img) : ?>
								<li data-target="#carousel-pro-generic" data-slide-to="<?php echo $cover_key-1; ?>" class="<?php if($cover_key==1) echo 'active'; ?>"></li>
								<?php endif; endforeach; ?>
							</ol>
						</div>
					</div>
					<div class="col-md-6 col">
						<h1 class="title mt-0 mb-20"><?php echo $redis->hget('pro:'.$id,'title'); ?></h1>
						<div id="pro-price" class="mb-10">
							<?php if(maoo_pro_min_price($id)!=maoo_pro_max_price($id)) : ?><?php echo maoo_pro_min_price($id); ?>元 -  <?php endif; ?><?php echo maoo_pro_max_price($id); ?>元
						</div>
						<?php if($redis->hget('pro:'.$id,'sale_off_date')>strtotime("now") && $redis->hget('pro:'.$id,'sale_off')>0) : ?>
						<div id="pro-price-original" class="mb-10">
							<del>原价：<?php if(maoo_pro_original_min_price($id)!=maoo_pro_original_max_price($id)) : ?><?php echo maoo_pro_original_min_price($id); ?>元 -  <?php endif; ?><?php echo maoo_pro_original_max_price($id); ?>元</del>
						</div>
						<?php endif; ?>
						<div class="clearfix"></div>
						<div id="pro-stock" class="mb-10" style="display: none;"></div>
						<hr>
						<?php $parameters = unserialize($redis->hget('pro:'.$id,'parameter')); ?>
						<div class="form-group">
							<label>
								选择商品参数
							</label>
							<ul class="list-inline pro-par-list">
								<?php foreach($parameters as $key_par=>$parameter) : if($parameter['price']>0) : if($redis->hget('pro:'.$id,'sale_off_date')>strtotime("now") && $redis->hget('pro:'.$id,'sale_off')>0) : $parameter['price'] = $parameter['price']*$redis->hget('pro:'.$id,'sale_off')/10; endif; ?>
								<li>
									<label price-data="<?php echo $parameter['price']; ?>" stock-data="<?php if($parameter['stock']>0) : echo $parameter['stock']; else : echo '0'; endif; ?>">
										<span><?php echo $parameter['name']; ?></span>
										<input type="radio" name="parameter" value="<?php echo $key_par; ?>">
									</label>
								</li>
								<?php endif; endforeach; ?>
							</ul>
						</div>
						<script>
							$('.pro-par-list label').click(function(){
								$('.pro-par-list label').removeClass('active');
								$(this).addClass('active');
								//价格
								var price_now = $(this).attr('price-data')*1;
								$('#pro-price').text(price_now+'元');
								//库存
								var stock_now = $(this).attr('stock-data')*1;
								$('#pro-stock').css('display','block');
								$('#pro-stock').text('库存：'+stock_now);
								if(stock_now>0) {
									$('.buy-btn-1').css('display','block');
									$('.buy-btn-2').css('display','none');
								} else {
									$('.buy-btn-1').css('display','none');
									$('.buy-btn-2').css('display','block');
								};
							});
						</script>
						<hr>
						<button type="submit" class="btn btn-warning btn-lg mt-40">加入购物车</button>
					</div>
				</div>
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						</form>
						<hr>
						<div class="entry mt-20">
							<?php echo $redis->hget('pro:'.$id,'content'); ?>
						</div>
					</div>
				</div>
				<div class="rank-to-pro-list">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="glyphicon glyphicon-list-alt"></i> 买家评价
						</div>
						<div class="panel-body">
							<?php
							$rank_to_pros = $redis->smembers('pro:rank:'.$id);
							if($rank_to_pros) : ?>
							<ul class="ranks-list media-list">
								<?php
								foreach($rank_to_pros as $rank_to_pro) :
								$rank_user_id = $redis->hget('cart:rank:'.$rank_to_pro,'user_id');
								?>
								<li class="media" id="rank-<?php echo $rank_to_pro; ?>">
									<div class="media-left">
										<a class="img-div" href="<?php echo $redis->get('site_url'); ?>?m=user&a=index&id=<?php echo $rank_user_id; ?>">
											<img class="media-object" src="<?php echo maoo_user_avatar($rank_user_id); ?>">
										</a>
									</div>
									<div class="media-body">
										<h4 class="media-heading">
											<a href="<?php echo $redis->get('site_url'); ?>?m=user&a=index&id=<?php echo $rank_user_id; ?>">
												<?php echo maoo_user_display_name($rank_user_id); ?>
											</a>
											评价于 <?php echo maoo_format_date($redis->hget('cart:rank:'.$rank_to_pro,'date')); ?>
											<div class="pull-right">
												<?php
												$rank = $redis->hget('cart:rank:'.$rank_to_pro,'rank');
												$rank_star = 0; do {
													$rank_star++;
													echo '<i class="glyphicon glyphicon-star"></i>';
												} while($rank_star<$rank);
												?>
												<span>(<?php if($rank==1) echo '非常差'; elseif($rank==2) echo '不值得购买'; elseif($rank==3) echo '一般般'; elseif($rank==4) echo '物超所值'; elseif($rank==5) echo '强烈推荐'; ?>)</span>
											</div>
										</h4>
										<div class="content">
											<?php echo $redis->hget('cart:rank:'.$rank_to_pro,'content'); ?>
										</div>
										<?php if($redis->hget('cart:rank:'.$rank_to_pro,'images')) : $rank_images = unserialize($redis->hget('cart:rank:'.$rank_to_pro,'images')); ?>
										<div class="row mt-10">
										<?php foreach($rank_images as $rank_image) : if($rank_image) : ?>
										<div class="col-xs-3 col">
											<a href="#" class="img-div" data-toggle="modal" data-target="#rankimageModal">
												<img src="<?php echo $rank_image; ?>">
											</a>
										</div>
										<?php endif; endforeach; ?>
										</div>
										<?php endif; ?>
										<?php if($redis->hget('cart:rank:'.$rank_to_pro,'reply')) : ?>
										<div class="mt-10 reply">
											<strong>卖家回复：</strong><?php echo $redis->hget('cart:rank:'.$rank_to_pro,'reply'); ?>
										</div>
										<?php endif; ?>
										<?php if($redis->hget('user:'.maoo_user_id(),'user_level')>7) : ?>
										<div class="mt-10">
											<a href="#" class="btn btn-default btn-sm btn-rankreply" data-id="<?php echo $rank_to_pro; ?>" data-toggle="modal" data-target="#rankreplyModal"><?php if($redis->hget('cart:rank:'.$rank_to_pro,'reply')) : ?>重新<?php endif; ?>回复此评价</a>
                                            <a href="<?php echo $redis->get('site_url'); ?>/do/delete.php?type=cartrank&id=<?php echo $rank_to_pro; ?>" class="btn btn-warning btn-sm">删除</a>
										</div>
										<?php endif; ?>
									</div>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<div class="modal fade" id="rankimageModal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">
											&times;
										</span>
									</button>
									<h4 class="modal-title">
										查看图片
									</h4>
								</div>
								<div class="modal-body">
									<div class="img-div">
										<img src="">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">
										关闭
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="rankreplyModal" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">
											&times;
										</span>
									</button>
									<h4 class="modal-title">
										回复买家
									</h4>
								</div>
								<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/rank_reply.php">
								<input type="hidden" name="id" value="">
								<div class="modal-body">
									<textarea class="form-control" rows="3" name="content" placeholder="请输入回复内容"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">
										取消
									</button>
									<button type="submit" class="btn btn-warning">
										保存
									</button>
								</div>
								</form>
							</div>
						</div>
					</div>
					<script>
						$('.ranks-list .media-body .img-div').hover(function(){
							var src = $('img',this).attr('src');
							$('#rankimageModal img').attr('src',src);
						});
						$('.btn-rankreply').hover(function(){
							var id = $(this).attr('data-id');
							$('#rankreplyModal input').val(id);
						});
					</script>
					<?php else : ?>
					<div class="nothing">
						暂无任何评价
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="pro-recommend hidden-xs hidden-sm">
	<div class="container">
		<div class="shop-index-list">
			<div class="shop-index-term">
				<h4 class="title text-center">
					推荐商品
				</h4>
				<?php $db = $redis->zrevrange('pro_id',0,3); ?>
				<div class="shop-allpro-list">
					<div class="row">
						<?php foreach($db as $page_id) : $recommend_cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
						<div class="col-xs-3 col">
							<div class="thumbnail">
								<a class="img-div" href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><img src="<?php echo $recommend_cover_images[1]; ?>" alt="<?php echo $redis->hget('pro:'.$page_id,'title'); ?>"></a>
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
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include('footer.php'); ?>
