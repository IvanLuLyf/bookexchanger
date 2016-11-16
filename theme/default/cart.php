<div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
				<h4 class="modal-title">
					我的购物车
				</h4>
			</div>
			<div class="modal-body">
				<?php 
					$carts = $redis->smembers('cart:user:1:'.maoo_user_id());
					if($carts) : 
				?>
				<div class="row hidden-xs hidden-sm">
					<div class="col-xs-5 col">
						商品
					</div>
					<div class="col-xs-1 text-center col">
						原价
					</div>
					<div class="col-xs-1 text-center col">
						单价
					</div>
					<div class="col-xs-2 text-center col">
						数量
					</div>
					<div class="col-xs-2 text-center col">
						小计
					</div>
					<div class="col-xs-1 text-center col">
						操作
					</div>
				</div>
				<hr class="hidden-xs hidden-sm">
				<ul class="media-list">
					<?php foreach($carts as $cart) : 
					$pro_id = $redis->hget('cart:'.$cart,'pro_id');
					$cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image')); ?>
					<li class="media">
						<div class="row">
							<div class="col-md-5 col">
								<div class="media-left">
									<a class="img-div" style="background-image:url(<?php echo $cover_images[1]; ?>)" href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $pro_id; ?>"></a>
								</div>
								<div class="media-body pull-left">
									<h4 class="media-heading mb-10">
										<a href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $pro_id; ?>"><?php echo $redis->hget('pro:'.$pro_id,'title'); ?></a>
									</h4>
									<?php echo $redis->hget('cart:'.$cart,'parameter'); ?>
                                    <div class="visible-xs-block visible-sm-block mt-10">
                                        <div class="mb-10">
                                            <span class="price"><?php echo $redis->hget('cart:'.$cart,'price'); ?>元</span> x <?php echo $redis->hget('cart:'.$cart,'number'); ?> = <?php echo $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number'); ?>元 
                                        </div>
                                        <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/cart_delete.php">
                                            <input type="hidden" name="id" value="<?php echo $cart; ?>">
                                            <input type="hidden" name="url" value="<?php echo maoo_page_url(); ?>">
                                            <button type="submit" class="btn btn-link btn-sm">删除</button>
                                        </form>
                                    </div>
								</div>
                                <div class="clearfix"></div>
							</div>
							<div class="col-md-1 text-center col hidden-xs hidden-sm">
								<?php echo $redis->hget('cart:'.$cart,'original_price'); ?>元
							</div>
							<div class="col-md-1 text-center col hidden-xs hidden-sm">
								<span class="price"><?php echo $redis->hget('cart:'.$cart,'price'); ?>元</span>
							</div>
							<div class="col-md-2 text-center col hidden-xs hidden-sm">
                                <div class="cartNumberBox">
                                    <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/cartnumber.php">
                                        <input type="hidden" name="cart" value="<?php echo $cart; ?>">
                                        <input type="hidden" name="url" value="<?php echo maoo_page_url(); ?>">
								        <input onkeyup="value=value.replace(/[^0-9]/g,'')" onpaste="value=value.replace(/[^0-9]/g,'')" oncontextmenu = "value=value.replace(/[^0-9]/g,'')" type="text" class="cartNumber form-control text-center" name="number" value="<?php echo $redis->hget('cart:'.$cart,'number'); ?>">
                                    </form>
                                </div>
							</div>
							<div class="col-md-2 text-center col hidden-xs hidden-sm">
								<?php echo $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number'); ?>元
							</div>
							<div class="col-md-1 text-center col hidden-xs hidden-sm">
								<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/cart_delete.php">
								<input type="hidden" name="id" value="<?php echo $cart; ?>">
								<input type="hidden" name="url" value="<?php echo maoo_page_url(); ?>">
								<button type="submit" class="btn btn-link">删除</button>
								</form>
							</div>
						</div>
					</li>
					<?php $cart_sale_off_price += ($redis->hget('cart:'.$cart,'original_price')-$redis->hget('cart:'.$cart,'price'))*$redis->hget('cart:'.$cart,'number'); $cart_price += $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number'); endforeach; ?>
				</ul>
				<?php else : ?>
				<div class="nothing">
					购物车中没有任何商品
				</div>
				<?php endif; ?>
			</div>
			<div class="modal-footer">
				<?php if($carts) : ?>
				<ul class="list-inline pull-left">
					<li>总价：<span class="price"><?php echo $cart_price; ?>元</span></li>
					<li>共优惠：<?php echo $cart_sale_off_price; ?>元</li>
				</ul>
				<?php endif; ?>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					继续购物
				</button>
				<a href="<?php echo maoo_url('pro','checkout'); ?>" class="btn btn-warning">
					确认购买
				</a>
			</div>
		</div>
	</div>
</div>
<script>
    $('.cartNumber').keyup(function(){
        var number = $(this).val();
        if(number>0) {
            $(this).parent('form').submit();
        }
    });
</script>
<?php if($_GET['showcart']==1) : ?>
<script>
	$('#cartModal').modal({
		show: true
	});
</script>
<?php endif; ?>