<?php include('header.php'); ?>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/react.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/JSXTransformer.js"></script>
<div class="container mb-40 checkout">
	<?php
	$carts = $redis->smembers('cart:user:1:'.maoo_user_id());
	if($carts) :
	?>
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
						支付订单
					</li>
				</ol>
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						支付订单
					</div>
					<div class="panel-body">
						<div class="well">
                            <?php
								foreach($carts as $cart) :
									$pro_id = $redis->hget('cart:'.$cart,'pro_id');
									echo '<p>'.$redis->hget('pro:'.$pro_id,'title').' [ '.$redis->hget('cart:'.$cart,'parameter').' ] x '.$redis->hget('cart:'.$cart,'number').'</p>';
                                    $cart_price += $redis->hget('cart:'.$cart,'price')*$redis->hget('cart:'.$cart,'number');
								endforeach;
							?>
						</div>
                        <?php $user_id = maoo_user_id(); ?>
						<div class="form-group">
                            <label>收货信息</label>
							<input type="text" class="form-control" placeholder="收货人姓名" name="WIDreceive_name" value="<?php echo $redis->hget('user:'.$user_id,'add_name'); ?>">
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
							<textarea rows="3" class="form-control" placeholder="详细地址" name="WIDreceive_address"><?php echo $redis->hget('user:'.$user_id,'add_address'); ?></textarea>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="联系电话" name="WIDreceive_phone" value="<?php echo $redis->hget('user:'.$user_id,'add_phone'); ?>">
						</div>
                        <div class="well">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        使用积分
                                    </span>
                                    <input onkeyup="value=value.replace(/[^0-9]/g,'')" onpaste="value=value.replace(/[^0-9]/g,'')" oncontextmenu = "value=value.replace(/[^0-9]/g,'')" type="text" name="coins" id="payCoinsInput" class="form-control"  value="<?php echo $_SESSION['checkout_coins']; ?>" placeholder="您共有<?php echo maoo_user_coins($user_id); ?>积分" />
                                </div>
                            </div>
                            <p>
                                每100积分可以抵现金<?php echo round(100/maoo_cash_to_coins(),2); ?>元，每次最多使用<?php echo maoo_pay_coins_limit(); ?>积分
                            </p>
                            <div class="text-right">
                                <h4>总金额 <?php echo $cart_price; ?> 元</h4>
                                <?php if($redis->get('express')>0) : $total = $cart_price+$redis->get('express'); ?>
                                <h4>运费 <?php echo $redis->get('express'); ?> 元</h4>
                                <?php else : $total = $cart_price; endif; ?>
                                <h5 id="coinsToCash" style="display:none">积分共抵现金 <span>0</span> 元</h5>
                                <h3 class="mb-0">共需支付 <span id="realPay"><?php echo $total; ?></span> 元</h3>
                            </div>
                            <script>
                                $('#payCoinsInput').keyup(function(){
                                    var coins = $(this).val()*1;
                                    if(coins>0) {
                                        $('#coinsToCash').css('display','block');
                                        if(coins><?php echo $total*maoo_cash_to_coins(); ?>) {
                                           $('#payCoinsInput').val(<?php echo $total*maoo_cash_to_coins(); ?>);
                                           $('#coinsToCash span').text(<?php echo $total; ?>);
                                            $('#realPay').text(0);
                                        } else {
                                            var cash = <?php echo $total; ?>;
                                            var newCoinsToCash = coins/<?php echo maoo_cash_to_coins(); ?>;
                                            var nctc = newCoinsToCash.toFixed(2)*1;
                                            var newRealPay = cash-nctc;
                                            $('#coinsToCash span').text(nctc);
                                            $('#realPay').text(newRealPay.toFixed(2));
                                        }
                                    } else {
                                        $('#realPay').text(<?php echo $total; ?>);
                                        $('#coinsToCash span').text(0);
                                        $('#coinsToCash').css('display','none');
                                    }
                                });
                                $(document).ready(function(){
                                    var coins = $('#payCoinsInput').val()*1;
                                    if(coins>0) {
                                        $('#coinsToCash').css('display','block');
                                        if(coins><?php echo $total*maoo_cash_to_coins(); ?>) {
                                           $('#payCoinsInput').val(<?php echo $total*maoo_cash_to_coins(); ?>);
                                           $('#coinsToCash span').text(<?php echo $total; ?>);
                                            $('#realPay').text(0);
                                        } else {
                                            var cash = <?php echo $total; ?>;
                                            var newCoinsToCash = coins/<?php echo maoo_cash_to_coins(); ?>;
                                            var nctc = newCoinsToCash.toFixed(2)*1;
                                            var newRealPay = cash-nctc;
                                            $('#coinsToCash span').text(nctc);
                                            $('#realPay').text(newRealPay.toFixed(2));
                                        }
                                    } else {
                                        $('#realPay').text(<?php echo $total; ?>);
                                        $('#coinsToCash span').text(0);
                                        $('#coinsToCash').css('display','none');
                                    }
                                });
                            </script>
                        </div>
                        <div class="well">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        推荐码
                                    </span>
                                    <input type="text" name="reffer" id="payRefferInput" class="form-control" placeholder="输入有效的推荐码可以获得10%的折扣" value="<?php echo $_SESSION['checkout_reffer']; ?>" />
                                </div>
                                <p id="payRefferText" class="help-block"></p>
                            </div>
                            <script>
                                $('#payRefferInput').blur(function(){
                                    var code = $(this).val();
                                    $.ajax({
													data: {
                                                        code: code
                                                    },
													type: "POST",
													url: "<?php echo $redis->get('site_url'); ?>/do/ref-verify.php",
													success: function(data) {
														$('#payRefferText').html(data);
													},
													error : function(data) {
														alert('验证失败');
													}
											});
                                });
                            </script>
                        </div>
                        <?php $recharge = $total-maoo_user_cash($user_id); if($recharge>0) : ?>
                        <div class="well">
                            <p>您当前的账户余额为：<?php echo maoo_user_cash(maoo_user_id()); ?>元，还需支付<span id="recharge"><?php echo $recharge; ?></span>元</p>
                        </div>
                        <?php endif; ?>
					</div>
					<div class="panel-footer text-right">
						<a class="btn btn-default" href="javascript:history.go(-1);">取消</a>
						<button type="submit" class="btn btn-warning">立即支付</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php else : ?>
	<div class="nothing">
		购物车里没有任何商品，<a href="<?php echo $redis->get('site_url'); ?>">马上去购物</a>！
	</div>
	<?php endif; ?>
</div>
<?php include('footer.php'); ?>
