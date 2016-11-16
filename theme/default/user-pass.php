<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
			<div class="panel panel-default mb-40">
				<div class="panel-heading text-center">
					修改密码
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/pass.php">
						<div class="form-group">
							<label>
								当前密码
							</label>
							<input type="password" name="pass1" class="form-control" placeholder="" value="">
						</div>
						<hr>
						<div class="form-group">
							<label>
								新密码
							</label>
							<input type="password" name="pass2" class="form-control" placeholder="" value="">
						</div>
						<div class="form-group">
							<label>
								确认新密码
							</label>
							<input type="password" name="pass3" class="form-control" placeholder="" value="">
						</div>
						<button type="submit" class="btn btn-default btn-block">
							修改
						</button>
					</form>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading text-center">
					设置安全问题
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/safe.php">
						<div class="form-group">
							<label>
								安全问题
							</label>
							<select class="form-control" name="question">
								<option>请选择安全问题</option>
								<option value="1" <?php if($redis->hget('user:'.$user_id,'user_question')==1) echo 'selected'; ?>>你就读的第一所中学叫什么名字？</option>
								<option value="2" <?php if($redis->hget('user:'.$user_id,'user_question')==2) echo 'selected'; ?>>你最喜欢的宠物叫什么名字？</option>
								<option value="3" <?php if($redis->hget('user:'.$user_id,'user_question')==3) echo 'selected'; ?>>你的高中班主任叫什么名字？</option>
								<option value="4" <?php if($redis->hget('user:'.$user_id,'user_question')==4) echo 'selected'; ?>>你最喜欢的饮料品牌是？</option>
								<option value="5" <?php if($redis->hget('user:'.$user_id,'user_question')==5) echo 'selected'; ?>>你的女友/男友/基友的名字？</option>
							</select>
						</div>
						<div class="form-group">
							<label>
								安全答案
							</label>
							<input type="text" name="answer" class="form-control" placeholder="答案" value="<?php echo $redis->hget('user:'.$user_id,'user_answer'); ?>">
							<p class="help-block">注意：请务必牢记您的安全问题和答案，这将是您找回密码的唯一凭证！</p>
						</div>
						<button type="submit" class="btn btn-default btn-block">
							修改
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>