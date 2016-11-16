<?php include('header.php'); ?>
<div class="container mb-40 checkout">
	<form method="post" target="_blank" action="<?php echo $redis->get('site_url'); ?>/do/checkout.php">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col">
				<ol class="breadcrumb mb-0">
					<li>
						<a href="<?php echo $redis->get('site_url'); ?>">
							首页
						</a>
					</li>
					<li class="active">
						账户充值
					</li>
				</ol>
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						账户充值
					</div>
					<div class="panel-body">
                        <div class="text-center cash-box">
                            充值<span class="cash"><?php echo $cash; ?></span>元
                        </div>
                        <?php if($redis->hget('payset','alipay_partner') && $redis->hget('payset','alipay_key')) : ?>
                        <div class="text-center">
                            <a class="up btn btn-default" href="<?php echo $redis->get('site_url'); ?>/public/pay/alipay/alipayapi.php?cash=<?php echo $cash; ?>">
                                <img src="<?php echo $redis->get('site_url'); ?>/public/img/alipay.png">
                            </a>
                        </div>
                        <?php else : ?>
                        <!-- alipay, alipay_pc_direct, alipay_wap, alipay_qr, apple_pay, bfb, bfb_wap, cnp_u, cnp_f, upacp, upacp_pc, upacp_wap, upmp, upmp_wap, wx, wx_pub, wx_pub_qr, yeepay_wap, jdpay_wap, cp_b2b -->
						<input id="amount" type="hidden" value="<?php echo $cash; ?>" />
                        <div class="text-center">
                            <?php if($redis->hget('payset','pingxx_alipay')==1) : ?>
                            <span class="up btn btn-default hidden-xs hidden-sm" onclick="wap_pay('alipay_pc_direct')">
                                <img src="<?php echo $redis->get('site_url'); ?>/public/img/alipay.png">
                            </span>
                            <?php endif; ?>
                            <?php if($redis->hget('payset','pingxx_yinlian')==1) : ?>
                            <span class="up btn btn-default hidden-xs hidden-sm" onclick="wap_pay('upacp_pc')">
                                <img src="<?php echo $redis->get('site_url'); ?>/public/img/yinlian.png">
                            </span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
					</div>
					<div class="panel-footer text-right">
						<a class="btn btn-default" href="<?php echo $redis->get('site_url'); ?>">取消</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script src="<?php echo $redis->get('site_url'); ?>/public/pay/pingpp-html5-master/src/pingpp-pc.js" type="text/javascript"></script>
<script>
    function wap_pay(channel) {
        var amount = document.getElementById('amount').value * 100;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?php echo $redis->get('site_url'); ?>/public/pay/pingpp-php-master/engine/pay.php", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.send(JSON.stringify({
            channel: channel,
            amount: amount
        }));

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
                pingppPc.createPayment(xhr.responseText, function(result, err) {
                    console.log(result);
                    console.log(err);
                });
            }
        }
    }
</script>
<script src="<?php echo $redis->get('site_url'); ?>/public/pay/pingpp-html5-master/src/pingpp.js" type="text/javascript"></script>
<script>
    function wap_pay2(channel) {
        var amount = document.getElementById('amount').value * 100;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?php echo $redis->get('site_url'); ?>/public/pay/pingpp-php-master/engine/pay.php", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.send(JSON.stringify({
            channel: channel,
            amount: amount
        }));

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
                pingppPc.createPayment(xhr.responseText, function(result, err) {
                    console.log(result);
                    console.log(err);
                });
            }
        }
    }
</script>
<?php include('footer.php'); ?>
