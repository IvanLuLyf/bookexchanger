<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/slider.php">
							<div class="form-group">
								<label>
									图片-1
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_img:1')) :
										$fmimg_full_1 = $redis->get('slider_img:1');
									else :
										$fmimg_full_1 = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img1" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full_1; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,1)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_img_1" rows="1" class="form-control" id="pub-input1"><?php if($redis->get('slider_img:1')) : echo $fmimg_full_1; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									链接-1
								</label>
								<input type="url" name="slider_link_1" class="form-control" value="<?php echo $redis->get('slider_link:1'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									图片-2
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_img:2')) :
										$fmimg_full_2 = $redis->get('slider_img:2');
									else :
										$fmimg_full_2 = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img2" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full_2; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,2)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_img_2" rows="1" class="form-control" id="pub-input2"><?php if($redis->get('slider_img:2')) : echo $fmimg_full_2; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									链接-2
								</label>
								<input type="url" name="slider_link_2" class="form-control" value="<?php echo $redis->get('slider_link:2'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									图片-3
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_img:3')) :
										$fmimg_full_3 = $redis->get('slider_img:3');
									else :
										$fmimg_full_3 = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img3" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full_3; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,3)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_img_3" rows="1" class="form-control" id="pub-input3"><?php if($redis->get('slider_img:3')) : echo $fmimg_full_3; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									链接-3
								</label>
								<input type="url" name="slider_link_3" class="form-control" value="<?php echo $redis->get('slider_link:3'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									商品幻灯图片-1
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_pro:img:1')) :
										$fmimg_full = $redis->get('slider_pro:img:1');
									else :
										$fmimg_full = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img7" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,7)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_pro_img_1" rows="1" class="form-control" id="pub-input7"><?php if($redis->get('slider_pro:img:1')) : echo $fmimg_full; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									商品幻灯链接-1
								</label>
								<input type="url" name="slider_pro_link_1" class="form-control" value="<?php echo $redis->get('slider_pro:link:1'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									商品幻灯图片-2
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_pro:img:2')) :
										$fmimg_full = $redis->get('slider_pro:img:2');
									else :
										$fmimg_full = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img8" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,8)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_pro_img_2" rows="1" class="form-control" id="pub-input8"><?php if($redis->get('slider_pro:img:2')) : echo $fmimg_full; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									商品幻灯链接-2
								</label>
								<input type="url" name="slider_pro_link_2" class="form-control" value="<?php echo $redis->get('slider_pro:link:2'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									商品幻灯图片-3
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_pro:img:3')) :
										$fmimg_full = $redis->get('slider_pro:img:3');
									else :
										$fmimg_full = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img9" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,9)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_pro_img_3" rows="1" class="form-control" id="pub-input9"><?php if($redis->get('slider_pro:img:3')) : echo $fmimg_full; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									商品幻灯链接-3
								</label>
								<input type="url" name="slider_pro_link_3" class="form-control" value="<?php echo $redis->get('slider_pro:link:3'); ?>">
							</div>
							<hr>
							<div class="form-group">
								<label>
									商品竖幅广告图片
								</label>
								<div class="clearfix"></div>
								<?php
									if($redis->get('slider_pro:img:4')) :
										$fmimg_full = $redis->get('slider_pro:img:4');
									else :
										$fmimg_full = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="default-img10" class="mb-10 pull-left mr-20" src="<?php echo $fmimg_full; ?>" width="300">
								<div class="pub-imgadd pull-left">
									<button type="button" class="btn btn-default btn-lg">上传图片</button>
									<input type="file" class="picfile" onchange="readFile(this,10)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="slider_pro_img_4" rows="1" class="form-control" id="pub-input10"><?php if($redis->get('slider_pro:img:4')) : echo $fmimg_full; endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									商品竖幅广告链接
								</label>
								<input type="url" name="slider_pro_link_4" class="form-control" value="<?php echo $redis->get('slider_pro:link:4'); ?>">
							</div>
							<button type="submit" class="btn btn-block btn-default">保存</button>
				</form>

				<script>
				function readFile(obj,id){
							$('#default-img'+id).attr('src','<?php echo $redis->get('site_url'); ?>/public/img/loading.gif');
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
										$('#default-img'+id).attr('src',url);
										$('#pub-input'+id).html(url);
									},
									error : function(data) {
										alert('上传失败');
										$('#default-img'+id).attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
									}
							});
			}
				</script>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
