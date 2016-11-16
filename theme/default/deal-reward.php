<?php include('header.php'); ?>
<div class="container checkout">
	<div class="row">
        <div class="col-sm-6 col-sm-offset-3 col">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    支持项目
                </div>
                <form method="post" target="_blank" action="<?php echo $redis->get('site_url'); ?>/do/reward.php">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="reward" value="<?php echo $rewardkey; ?>">
                <div class="panel-body">
                    <div class="well">
                        <h4 class="title mt-0"><?php echo $redis->hget('deal:'.$id,'title'); ?></h4>
                        <?php echo $reward['content']; ?>
                    </div>
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
                    <div class="form-group">
                        <label>留言给项目发起者</label>
                        <textarea rows="3" class="form-control" placeholder="写下你想说的话" name="somewords"></textarea>
                    </div>
                    <div class="well">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    使用积分
                                </span>
                                <input onkeyup="value=value.replace(/[^0-9]/g,'')" onpaste="value=value.replace(/[^0-9]/g,'')" oncontextmenu = "value=value.replace(/[^0-9]/g,'')" type="text" name="coins" id="payCoinsInput" class="form-control" placeholder="您共有<?php echo maoo_user_coins($user_id); ?>积分" />
                            </div>
                        </div>
                        <p>
                            每100积分可以抵现金<?php echo round(100/maoo_cash_to_coins(),2); ?>元，每次最多使用<?php echo maoo_pay_coins_limit(); ?>积分
                        </p>
                        <div class="text-right">
                            <h4>总金额 <?php $total = $reward['price']; echo $total; ?> 元</h4>
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
                        </script>
                    </div>
                    <?php $recharge = $total-maoo_user_cash($user_id); if($recharge>0) : ?>
                        <div class="well">
                            <p>您当前的账户余额为：<?php echo maoo_user_cash(maoo_user_id()); ?>元，还需充值<span id="recharge"><?php echo $recharge; ?></span>元</p>
                            <a class="btn btn-danger" target="_blank" href="<?php echo maoo_url('user','cash',array('cash'=>$recharge)); ?>#ucash1">立即充值</a>
                        </div>
                        <?php endif; ?>
				</div>
				<div class="panel-footer text-right">
				    <a class="btn btn-default" href="javascript:history.go(-1);">取消</a>
				    <button type="submit" class="btn btn-warning">立即支付</button>
				</div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
