<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
            <div class="panel panel-default mb-40" id="ucash0">
				<div class="panel-heading text-center">
				    现金余额
				</div>
				<div class="panel-body text-center">
				    <h2 class="number"><?php echo maoo_user_cash($user_id); ?> <small>元</small></h2>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 col">
					<div class="panel panel-default mb-40" id="ucash1">
						<div class="panel-heading text-center">
							账户充值
						</div>
						<div class="panel-body">
							<form method="post" target="_blank" action="<?php echo maoo_url('user','recharge'); ?>">
								<div class="form-group">
									<label>
										充值金额
									</label>
									<div class="input-group">
										<input type="text" name="cash" class="form-control" placeholder="请填写大于0的数字" value="<?php echo $_GET['cash']; ?>">
										<span class="input-group-addon">元</span>
									</div>
								</div>
								<button type="submit" class="btn btn-danger btn-block">
									立即充值
								</button>
							</form>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col">
					<div class="panel panel-default mb-40" id="ucash2">
						<div class="panel-heading text-center">
							预约提现
						</div>
						<div class="panel-body">
							<form>
								<div class="form-group">
									<label>
										请输入提现金额
									</label>
									<div class="input-group">
										<input type="text" name="tixian" class="form-control" placeholder="金额须大于100元" value="">
										<span class="input-group-addon">元</span>
									</div>
								</div>
								<button type="button" class="btn btn-success btn-block">
									提交预约
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default mb-40" id="ucash3">
				<div class="panel-heading text-center">
					账单明细
				</div>
				<?php if($db) : ?>
					<ul class="list-group">
						<?php foreach($db as $cash_id) : ?>
						<li class="list-group-item">
							于<?php echo date('Y-m-d H:i:s',$redis->hget('cash:'.$cash_id,'date')); ?> <?php echo $redis->hget('cash:'.$cash_id,'des'); ?>，<?php if($redis->hget('cash:'.$cash_id,'des')=='充值') : ?><span class="text-success">+<?php echo $redis->hget('cash:'.$cash_id,'total'); ?></span><?php else : ?><span class="text-danger">-<?php echo $redis->hget('cash:'.$cash_id,'total'); ?></span><?php endif; ?>元
							<?php if($redis->hget('cash:'.$cash_id,'status')==1) : ?><span class="badge ml-10">未支付</span><?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<div class="panel-body">
						暂无任何账单
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
