<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/coins_set.php">
				<div class="form-group">
					<label>
						每日登录积分
					</label>
					<input type="text" name="every_day" class="form-control" value="<?php echo $redis->get('coins:every_day'); ?>" placeholder="默认值 1">
				</div>
				<div class="form-group">
					<label>
						注册赠送积分
					</label>
					<input type="text" name="register" class="form-control" value="<?php echo $redis->get('coins:register'); ?>" placeholder="默认值 3">
				</div>
				<div class="form-group">
					<label>
						积分购买汇率 - 1元现金可购买的积分数
					</label>
					<input type="text" name="cash_to_coins" class="form-control" value="<?php echo $redis->get('coins:cash_to_coins'); ?>" placeholder="默认值 10">
				</div>
				<div class="form-group">
					<label>
						积分抵现上限 - 单次支付中最多可以使用多少积分抵消现金
					</label>
					<input type="text" name="pay_coins_limit" class="form-control" value="<?php echo $redis->get('coins:pay_coins_limit'); ?>" placeholder="默认值 1000">
				</div>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
