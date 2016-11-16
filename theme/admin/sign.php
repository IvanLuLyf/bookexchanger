<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/sign.php">
				<div class="form-group">
					<label>
						QQ互联接口设置
					</label>
					<input type="text" name="qq_appid" class="form-control" value="<?php echo $redis->get('user:connect:qq:appid'); ?>" placeholder="APP ID">
				</div>
				<div class="form-group">
					<input type="text" name="qq_appkey" class="form-control" value="<?php echo $redis->get('user:connect:qq:appkey'); ?>" placeholder="APP KEY">
					<p class="help-block">设置以上两项参数后，QQ登录功能会自动开启，申请此接口地址：<a target="_blank" href="http://connect.qq.com/">http://connect.qq.com/</a>。回调地址请填写：<?php echo $redis->get('site_url'); ?>/public/connect-qq</p>
				</div>
				<div class="form-group">
					<label>
						新浪微博接口设置
					</label>
					<input type="text" name="weibo_appkey" class="form-control" value="<?php echo $redis->get('user:connect:weibo:appkey'); ?>" placeholder="APP KEY">
				</div>
				<div class="form-group">
					<input type="text" name="weibo_appsecret" class="form-control" value="<?php echo $redis->get('user:connect:weibo:appsecret'); ?>" placeholder="APP SECRET">
					<p class="help-block">设置以上两项参数后，新浪微博登录功能会自动开启，申请此接口地址：<a target="_blank" href="http://open.weibo.com/">http://open.weibo.com/</a>。回调地址请填写：<?php echo $redis->get('site_url'); ?>/public/connect-weibo</p>
				</div>
				<div class="form-group">
					<label>
						阿里大鱼接口设置
					</label>
					<input type="text" name="dayu_appkey" class="form-control" value="<?php echo $redis->get('user:connect:dayu:appkey'); ?>" placeholder="APP KEY">
				</div>
				<div class="form-group">
					<input type="text" name="dayu_secretkey" class="form-control" value="<?php echo $redis->get('user:connect:dayu:secretkey'); ?>" placeholder="SECRET KEY">
					<p class="help-block">设置以上两项参数后，短信相关功能会自动开启，申请此接口地址：<a target="_blank" href="http://www.alidayu.com//">http://www.alidayu.com/</a></p>
				</div>
				<div class="form-group">
					<select name="dayu_reglock" class="form-control" disabled>
						<option value="0" <?php if($redis->get('user:connect:dayu:reglock')!=1) echo 'selected'; ?>>不强制手机注册</option>
						<option value="1" <?php if($redis->get('user:connect:dayu:reglock')==1) echo 'selected'; ?>>强制手机注册</option>
					</select>
					<p class="help-block">设置以上两项参数后，新浪微博登录功能会自动开启，申请此接口地址：<a target="_blank" href="http://open.weibo.com/">http://open.weibo.com/</a></p>
				</div>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
