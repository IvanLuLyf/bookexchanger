<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body admin-order">
			<ul class="media-list">
                <li class="media">
                    <div class="row">
									<div class="col-xs-5 col">
										商品
									</div>
									<div class="col-xs-2 text-center col">
										单价
									</div>
									<div class="col-xs-1 text-center col">
										数量
									</div>
									<div class="col-xs-2 text-center col">
										小计
									</div>
									<div class="col-xs-2 text-center col">
										订单状态
									</div>
								</div>
                </li>
			<?php
			$count = $redis->scard('cart:order');
			$page_now = $_GET['page'];
			$page_size = $redis->get('page_size');
			if(empty($page_now) || $page_now<1) :
				$page_now = 1;
			else :
				$page_now = $_GET['page'];
			endif;
			$offset = ($page_now-1)*$page_size;
			$db = $redis->sort('cart:order',array('sort'=>'desc','limit'=>array($offset,$page_size)));
			foreach($db as $cart) :
			$pro_id = $redis->hget('cart:'.$cart,'pro_id');
			$cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image'));
			$cart_status = $redis->hget('cart:'.$cart,'status');
			?>
			<li class="media">
								<div class="row">
									<div class="col-xs-5 col">
										<div class="media-left">
											<a class="img-div" style="background-image:url(<?php echo $cover_images[1]; ?>)" href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $pro_id; ?>"></a>
										</div>
										<div class="media-body pull-left">
											<h4 class="media-heading mb-10">
												<a href="<?php echo $redis->get('site_url'); ?>?m=pro&a=single&id=<?php echo $pro_id; ?>"><?php echo $redis->hget('pro:'.$pro_id,'title'); ?></a>
											</h4>
											<p><?php echo $redis->hget('cart:'.$cart,'parameter'); ?></p>
											<?php if($cart_status>1) : ?>
											<div class="well">
												<p><?php echo $redis->hget('cart:'.$cart,'address'); ?></p>
                                                <p>站内单号：<?php echo $redis->hget('cart:'.$cart,'out_trade_no'); ?></p>
                                                下单时间：<?php echo date('Y-m-d H:i',$redis->hget('cart:'.$cart,'date')); ?>
											</div>
											<?php endif; ?>
										</div>
									</div>
									<div class="col-xs-2 text-center col">
										<?php echo $redis->hget('cart:'.$cart,'price'); ?>元
									</div>
									<div class="col-xs-1 text-center col">
										<?php echo $redis->hget('cart:'.$cart,'number'); ?>
									</div>
									<div class="col-xs-2 text-center col">
										<?php echo $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number'); ?>元
									</div>
									<div class="col-xs-2 text-center col col-last">
										<div class="status-2 mb-10">付款成功</div>
                                        <a href="#" data-toggle="modal" data-target="#cartModal<?php echo $cart; ?>">更新状态</a>
                                        <?php if($redis->hget('cart:'.$cart,'update')) : ?>
                                        <div class="mb-10 clearfix"></div>
                                        <div class="well">
                                            <?php echo $redis->hget('cart:'.$cart,'update'); ?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="modal fade" id="cartModal<?php echo $cart; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">
                                                                &times;
                                                            </span>
                                                        </button>
                                                        <h4 class="modal-title">
                                                            更新状态
                                                        </h4>
                                                    </div>
                                                    <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/cartupdate.php">
                                                        <input type="hidden" name="id" value="<?php echo $cart; ?>">
                                                        <div class="modal-body">
                                                            <textarea rows="10" class="form-control" name="content" placeholder="发货/退换状态，物流信息"><?php echo $redis->hget('cart:'.$cart,'update'); ?></textarea>
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
									</div>
								</div>
							</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
