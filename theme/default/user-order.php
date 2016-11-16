<?php include('header.php'); ?>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/react.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/JSXTransformer.js"></script>
<div class="container user-center">
	<div class="user-order">
		<div class="row">
			<div class="col-sm-8 col">
				<div class="order-type-nav">
					<a href="<?php echo maoo_url('user','order'); ?>" class="<?php if($_GET['type']=='') echo 'active'; ?>">全部订单</a>
					<a href="<?php echo maoo_url('user','order',array('type'=>2)); ?>" class="<?php if($_GET['type']==2) echo 'active'; ?>">已付款</a>
					<a href="<?php echo maoo_url('user','order',array('type'=>3)); ?>" class="<?php if($_GET['type']==3) echo 'active'; ?>">等待发货</a>
					<a href="<?php echo maoo_url('user','order',array('type'=>4)); ?>" class="<?php if($_GET['type']==4) echo 'active'; ?>">已发货</a>
					<a href="<?php echo maoo_url('user','order',array('type'=>5)); ?>" class="<?php if($_GET['type']==5) echo 'active'; ?>">交易完成</a>
				</div>
				<div class="panel panel-default panel-order pr">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-5 col">
								我的订单
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
								状态
							</div>
						</div>
					</div>
					<div class="panel-body">
						<?php
						if($db) : ?>
						<ul class="media-list">
							<?php foreach($db as $cart) :
							$pro_id = $redis->hget('cart:'.$cart,'pro_id');
							$cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image'));
							$cart_status = $redis->hget('cart:'.$cart,'status'); ?>
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
												<?php echo $redis->hget('cart:'.$cart,'address'); ?>
											</div>
											<?php endif; ?>
                                            <?php if($redis->hget('cart:'.$cart,'update')) : ?>
                                            <div class="well well-update">
                                                <?php echo $redis->hget('cart:'.$cart,'update'); ?>
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
									<div class="col-xs-2 text-center col">
										<?php
											if($cart_status==2) :
												$status = '已付款';
											elseif($cart_status==3) :
												$status = '等待发货';
											elseif($cart_status==4) :
												$status = '已收货';
											elseif($cart_status==5) :
												$status = '交易结束';
											else :
												$status = '购物车';
											endif;
											echo '<span class="status-'.$cart_status.'">'.$status.'</span>';
											if($cart_status==2) :
												if($redis->hget('cart:rank:'.$cart,'rank')>0) :
													echo '<div class="clearfix mb-10"></div><a href="'.$redis->get('site_url').'?m=pro&a=single&id='.$pro_id.'#rank-'.$cart.'" class="rankbtn">查看我的评价</a>';
												else :
													echo '<div class="clearfix mb-10"></div><a href="#" class="rankbtn" data-id="'.$cart.'" data-toggle="modal" data-target="#rankModal">评价</a>';
												endif;
											endif;
										?>
									</div>
								</div>
							</li>
							<?php $cart_price += $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number'); endforeach; ?>
						</ul>
						<div class="modal fade" id="rankModal" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
										<h4 class="modal-title">
											评价商品
										</h4>
									</div>
									<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/rank_to_pro.php">
										<div class="modal-body">
											<input id="rankid" type="hidden" name="rankid" value="">
											<div class="form-group">
												<label>商品评分</label>
												<ul class="list-inline rank-radio-list">
													<li>
														<label>
															<span>1分 - 非常差</span>
															<input type="radio" name="rank" value="1">
														</label>
													</li>
													<li>
														<label>
															<span>2分 - 不值得购买</span>
															<input type="radio" name="rank" value="2">
														</label>
													</li>
													<li>
														<label>
															<span>3分 - 一般般</span>
															<input type="radio" name="rank" value="3">
														</label>
													</li>
													<li>
														<label>
															<span>4分 - 物超所值</span>
															<input type="radio" name="rank" value="4">
														</label>
													</li>
													<li>
														<label class="active">
															<span>5分 - 强烈推荐</span>
															<input type="radio" name="rank" value="5" checked>
														</label>
													</li>
												</ul>
											</div>
											<div class="form-group">
												<label>简短评价</label>
												<textarea class="form-control" rows="3" name="content" placeholder="把您的购物感受分享给大家"></textarea>
											</div>
											<div class="form-group">
												<label>商品图片</label>
												<div class="clearfix"></div>
												<div class="row mb-20 cover-image">
													<?php $cover_image_num_array = array(1,2,3,4); ?>
													<?php foreach($cover_image_num_array as $cover_image_num) : ?>
													<div class="col-xs-3 col">
														<div class="cover-image-show mb-10" id="cover-image-show-<?php echo $cover_image_num; ?>">
															<div class="img-div">
																<img src="<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg">
															</div>
														</div>
														<textarea id="cover-image-<?php echo $cover_image_num; ?>" class="hidden" name="images[<?php echo $cover_image_num; ?>]"></textarea>
														<div id="upload-button-row-<?php echo $cover_image_num; ?>" class="mb-20">
															<div class="row">
																<div class="col-xs-6 col col-1">
																	<div class="pub-imgadd">
																		<button type="button" class="btn btn-default btn-block">上传</button>
																		<input type="file" class="picfile" onchange="readFile(this,<?php echo $cover_image_num; ?>)" />
																	</div>
																</div>
																<div class="col-xs-6 col col-2">
																	<input type="button" id="upload-button-del-<?php echo $cover_image_num; ?>" cover-image-data="<?php echo $cover_image_num; ?>" class="btn btn-default btn-block upload-button-del" value="删除">
																</div>
															</div>
														</div>
													</div>
													<?php endforeach; ?>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">
												取消
											</button>
											<button type="submit" class="btn btn-warning">
												提交
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<script>
							$('.upload-button-del').click(function(){
								var num = $(this).attr('cover-image-data');
								$('#cover-image-show-'+num+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
								$('#cover-image-'+num).val('');
							});
							function readFile(obj,id){
										$('#cover-image-show-'+id+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/loading.gif');
										var file = obj.files[0];
										//判断类型是不是图片
										if(!/image\/\w+/.test(file.type)){
														alert("请确保文件为图像类型");
														return false;
										}

										data = new FormData();
										data.append("file", file);
										$.ajax({
												data: data,
												type: "POST",
												url: "<?php echo $redis->get('site_url'); ?>/do/imgupload.php",
												cache: false,
												contentType: false,
												processData: false,
												success: function(url) {
													$('#cover-image-show-'+id+' img').attr('src',url);
													$('#cover-image-'+id).html(url);
												},
												error : function(data) {
													alert('上传失败');
													$('#cover-image-show-'+id+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
												}
										});
						};
							$('.rank-radio-list label').click(function(){
								$('.rank-radio-list label').removeClass('active');
								$(this).addClass('active');
							});
							$('.rankbtn').hover(function(){
								var id = $(this).attr('data-id');
								$('#rankid').val(id);
							});
						</script>
						<?php else : ?>
						<div class="nothing">
							暂无任何订单，<a href="<?php echo $redis->get('site_url'); ?>">立即去购物</a>吧！
						</div>
						<?php endif; ?>
					</div>
				</div>
                <?php echo maoo_pagenavi($count,$page_now); ?>
			</div>
			<div class="col-sm-4 col">
				<div class="panel panel-default panel-address-set">
					<div class="panel-heading">
						默认收货地址设置
					</div>
					<div class="panel-body">
						<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/address.php">
							<div class="form-group">
								<input type="text" name="page[add_name]" class="form-control" placeholder="收货人姓名" value="<?php echo $redis->hget('user:'.$user_id,'add_name'); ?>">
							</div>
                            <div id="react-address-box"></div>
                            <script type="text/jsx" src="<?php echo $redis->get('site_url'); ?>/theme/default/react/address.js"></script>
                            <script type="text/jsx">
                                  React.render(
                                    <Pca province="<?php echo $redis->hget('user:'.$user_id,'add_province'); ?>" city="<?php echo $redis->hget('user:'.$user_id,'add_city'); ?>" area="<?php echo $redis->hget('user:'.$user_id,'add_area'); ?>" />,
                                    document.getElementById('react-address-box')
                                  );
                            </script>
							<div class="form-group">
								<textarea placeholder="详细地址" rows="5" name="page[add_address]" class="form-control"><?php echo $redis->hget('user:'.$user_id,'add_address'); ?></textarea>
							</div>
							<div class="form-group">
								<input type="text" placeholder="联系电话" name="page[add_phone]" class="form-control" value="<?php echo $redis->hget('user:'.$user_id,'add_phone'); ?>">
							</div>
							<button type="submit" class="btn btn-primary btn-block">
								保存
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
