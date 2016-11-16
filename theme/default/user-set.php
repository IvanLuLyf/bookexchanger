<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
			<?php if(maoo_dayu()) : ?>
			<div class="panel panel-default">
				<div class="panel-heading text-center">
					绑定手机号
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $redis->get('site_url'); ?>/public/bigfish/bindphone.php">
						<div class="form-group" id="bindPhone">
							<div class="row mb-10">
								<div class="col-xs-6 col">
									<input type="text" class="form-control bindPhoneNumber" name="phone" value="<?php echo $redis->hget('user:'.$user_id,'phone'); ?>" placeholder="输入您的手机号">
								</div>
								<div class="col-xs-6 col">
									<div class="input-group">
									  <input type="text" class="form-control" name="code" placeholder="请填写验证码">
									  <span class="input-group-addon">获取验证码</span>
									</div>
								</div>
							</div>
							<p class="help-block">绑定手机号后，可以使用手机号代替账号快速登录，还可以获得10积分奖励。</p>
						</div>
						<button type="submit" class="btn btn-default btn-block">
							提交绑定 <span></span>
						</button>
						<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
					</form>
				</div>
			</div>
			<script>
				$('#bindPhone .input-group-addon').click(function(){
					$('#bindPhone .input-group-addon').text('验证码发送中');
					var phone = $('.bindPhoneNumber').val();
					if(phone>0) {
						$.ajax({
							type: 'POST',
							url: '<?php echo $redis->get('site_url'); ?>/public/bigfish/getcode.php',
							data: {
								phone:phone
							},
							success: function(data) {
								if(data=='验证码发送成功') {
									$('#bindPhone .help-block').css('color','#78c752');
									$('#bindPhone .help-block').text(data);
									$('#bindPhone .input-group-addon').text('获取验证码');
								} else {
									$('#bindPhone .help-block').css('color','#dc3f32');
									$('#bindPhone .help-block').text(data);
									$('#bindPhone .input-group-addon').text('获取验证码');
								}
							},
							error: function() {
								$('#bindPhone .help-block').css('color','#dc3f32');
								$('#bindPhone .help-block').text('验证码获取失败');
								$('#bindPhone .input-group-addon').text('获取验证码');
							}
						});
					} else {
						$('#bindPhone .help-block').css('color','#dc3f32');
						$('#bindPhone .help-block').text('手机号码不得为空');
						$('#bindPhone .input-group-addon').text('获取验证码');
					}
				});
			</script>
			<?php endif; ?>
			<div class="panel panel-default">
				<div class="panel-heading text-center">
					账号设置
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/set.php">
						<div class="form-group">
							<label>
								用户名
							</label>
							<input type="text" class="form-control" placeholder="" value="<?php echo $redis->hget('user:'.$user_id,'user_name'); ?>" disabled>
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
								<textarea class="hidden" type="hidden" name="avatar" id="pub-input1"><?php echo $redis->hget('user:'.$user_id,'avatar'); ?></textarea>
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
						<?php if(maoo_social_sign()) : ?>
						<div class="form-group">
							<label>
								绑定社交账号
							</label>
							<div class="clearfix"></div>
							<?php if($redis->get('user:connect:qq:appid') && $redis->get('user:connect:qq:appkey')) : ?>
							<?php if($redis->hget('user:'.$user_id,'connect_qq')=='') : ?>
							<a target="_blank" href="<?php echo $redis->get('site_url'); ?>/public/connect-qq/oauth" class="btn btn-primary">绑定QQ账号</a>
							<?php else : ?>
							<a href="<?php echo $redis->get('site_url'); ?>/do/unbind.php?type=qq" class="btn btn-default">解除绑定:QQ账号</a>
							<?php endif; ?>
							<?php endif; ?>
							<?php if($redis->get('user:connect:weibo:appkey') && $redis->get('user:connect:weibo:appsecret')) : ?>
							<?php if($redis->hget('user:'.$user_id,'connect_weibo')=='') : ?>
							<a target="_blank" href="<?php echo $redis->get('site_url'); ?>/public/connect-weibo/oauth" class="btn btn-danger">绑定新浪微博账号</a>
							<?php else : ?>
							<a href="<?php echo $redis->get('site_url'); ?>/do/unbind.php?type=weibo" class="btn btn-default">解除绑定:新浪微博</a>
							<?php endif; ?>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<button type="submit" class="btn btn-default btn-block">
							确认修改
						</button>
						<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
					</form>
				</div>
			</div>
            <div class="panel panel-default">
				<div class="panel-heading text-center">
				    我的推荐码
				</div>
				<div class="panel-body">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control input-lg text-center" value="REF<?php echo $user_id; ?>" disabled >
                        <p class="help-block">
                            将此邀请码发送给其他用户，当Ta在本网站进行任何消费时输入此推荐码，都可以获得10%的优惠，同时您永久获得该用户消费金额的15%现金奖励。
                        </p>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
