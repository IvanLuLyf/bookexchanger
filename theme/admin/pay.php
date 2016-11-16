<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/pay.php">
				<h4 class="title">基本信息</h4>
				<div class="form-group">
					<label>
						默认运费
					</label>
					<input type="text" name="page[express]" class="form-control" value="<?php echo $redis->hget('payset','express'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						支付运费规则 <small>同时支付多个商品时，运费的计算方式</small>
					</label>
					<select name="page[express_rule]" class="form-control">
						<option value="1" <?php if($redis->hget('payset','express_rule')==1) echo 'selected' ; ?>>按订单中商品的最低运费</option>
						<option value="2" <?php if($redis->hget('payset','express_rule')==2) echo 'selected' ; ?>>按订单中商品的最高运费</option>
						<option value="3" <?php if($redis->hget('payset','express_rule')==3) echo 'selected' ; ?>>按订单中每种商品运费之和</option>
						<option value="4" <?php if($redis->hget('payset','express_rule')==4) echo 'selected' ; ?>>按订单中所有商品运费之和</option>
					</select>
				</div>
				<div class="form-group">
					<label>
						免运费阙值 <small>当订单金额达到此值时，不收取运费</small>
					</label>
					<input type="text" name="page[express_threshold]" class="form-control" value="<?php echo $redis->hget('payset','express_threshold'); ?>" placeholder="">
				</div>
				<h4 class="title">独立支付宝即时到账接口设置</h4>
				<div class="form-group">
					<label>
						支付宝账号
					</label>
					<input type="text" name="page[alipay_seller]" class="form-control" value="<?php echo $redis->hget('payset','alipay_seller'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						合作身份者id <small>partner</small>
					</label>
					<input type="text" name="page[alipay_partner]" class="form-control" value="<?php echo $redis->hget('payset','alipay_partner'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						安全检验码 <small>key</small>
					</label>
					<input type="text" name="page[alipay_key]" class="form-control" value="<?php echo $redis->hget('payset','alipay_key'); ?>" placeholder="">
                    <p class="help-block">
                        设置以上选项，将默认开启支付宝接口而无法使用ping++接口。
                    </p>
				</div>
				<h4 class="title">ping++接口设置</h4>
				<div class="form-group">
					<label>
						Live Secret Key <small>api_key</small>
					</label>
					<input type="text" name="page[pingxx_key]" class="form-control" value="<?php echo $redis->hget('payset','pingxx_key'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						应用 ID <small>app_id</small>
					</label>
					<input type="text" name="page[pingxx_id]" class="form-control" value="<?php echo $redis->hget('payset','pingxx_id'); ?>" placeholder="">
                    <p class="help-block">
                        ping++支付接口申请地址：<a target="_blank" href="https://www.pingxx.com/">https://www.pingxx.com/</a>，开通支付渠道后，需配置Webhooks接收地址为<?php echo $redis->get('site_url'); ?>/public/pay/pingpp-php-master/index.php。目前支持支付宝PC支付和银联支付渠道，后续版本会开放更多支付类型。
                    </p>
				</div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="page[pingxx_alipay]" value="1" <?php if($redis->hget('payset','pingxx_alipay')==1) echo 'checked'; ?>>
                        启用ping++支付宝接口
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="page[pingxx_yinlian]" value="1" <?php if($redis->hget('payset','pingxx_yinlian')==1) echo 'checked'; ?>>
                        启用ping++银联接口
                    </label>
                </div>
				<hr>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
