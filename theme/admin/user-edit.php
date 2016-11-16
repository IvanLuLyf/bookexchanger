<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<h4 class="title mt-0">编辑用户</h4>
			<hr>
			<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/set.php">
				<div class="form-group">
					<label>
						用户名
					</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo $redis->hget('user:'.$user_id,'user_name'); ?>" disabled>
				</div>
				<div class="form-group">
					<label>
						现有积分
					</label>
					<input type="text" name="coins" class="form-control" placeholder="" value="<?php echo maoo_user_coins($user_id); ?>">
				</div>
				<div class="form-group">
					<label>
						现金余额
					</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo maoo_user_cash($user_id); ?>" disabled>
				</div>
				<div class="form-group">
					<label>
						用户等级
					</label>
					<select name="user_level" class="form-control">
						<?php $user_level_array = array(1,8,10); ?>
						<?php foreach($user_level_array as $user_level) : ?>
						<option <?php if($redis->hget('user:'.$user_id,'user_level')==$user_level) echo 'selected'; ?> value="<?php echo $user_level; ?>">
							<?php
								if($user_level==1) :
									echo '普通用户';
								elseif($user_level==8) :
									echo '编辑';
								elseif($user_level==10) :
									echo '管理员';
								endif;
							?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>
						昵称
					</label>
					<input type="text" name="display_name" class="form-control" placeholder="" value="<?php echo $redis->hget('user:'.$user_id,'display_name'); ?>">
				</div>
				<div class="form-group">
					<label>
						头像
					</label>
					<div class="clearfix"></div>
					<img src="<?php echo maoo_user_avatar($user_id); ?>" id="default-img1" class="mb-20 pull-left mr-20" width="200" height="200" style="border: 1px solid #e3e3e3;">
					<div class="pub-imgadd pull-left">
						<button type="button" class="btn btn-default btn-lg">上传头像</button>
						<input type="file" class="picfile" onchange="readFile(this,1)" />
						<input type="hidden" name="avatar" id="pub-input1" value="<?php echo $redis->hget('user:'.$user_id,'avatar'); ?>">
					</div>
					<div class="clearfix"></div>
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
												url: "<?php echo $redis->get('site_url'); ?>/do/imgupload-xs.php",
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
				<div class="form-group">
					<label>
						个人介绍
					</label>
					<textarea rows="3" class="form-control" name="description"><?php echo $redis->hget('user:'.$user_id,'description'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						注册时间
					</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo date('Y/m/d H:i:s',$redis->hget('user:'.$user_id,'user_register_date')); ?>" disabled>
				</div>
				<div class="form-group">
					<label>
						最后登陆
					</label>
					<input type="text" class="form-control" placeholder="" value="<?php echo date('Y/m/d H:i:s',$redis->hget('user:'.$user_id,'user_login_date')); ?>" disabled>
				</div>
				<button type="submit" class="btn btn-default btn-block">
					确认修改
				</button>
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
