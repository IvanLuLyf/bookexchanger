<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
			<div class="row">
				<div class="col-xs-12 col">
					<div class="panel panel-default mb-40" id="ucash1">
						<div class="panel-heading text-center">
							购买积分
						</div>
						<div class="panel-body">
							<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/cash_to_coins.php">
								<div class="form-group">
									<label>
										购买积分数量
									</label>
									<select class="form-control" name="coins">
										<?php $coins_array = array(10,20,50,100,200,500,1000,2000,5000); foreach($coins_array as $coins) : ?>
										<option value="<?php echo $coins; ?>"><?php echo $coins; ?>积分</option>
										<?php endforeach ; ?>
									</select>
									<p class="help-block">您的账户余额为<?php echo maoo_user_cash(maoo_user_id()); ?>元，可购买积分：<?php echo maoo_user_cash(maoo_user_id())*maoo_cash_to_coins(); ?>。如需购买更多积分，请先<a href="<?php echo maoo_url('user','cash'); ?>#ucash1">充值</a>。</p>
								</div>
								<button type="submit" class="btn btn-danger btn-block">
									立即购买
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default" id="ucash3">
				<div class="panel-heading text-center">
					积分记录
				</div>
				<?php
					$db = $redis->lrange('coins:user:'.maoo_user_id(),0,9);
					if($db) :
				?>
				<ul class="list-group">
					<?php foreach($db as $coins_message) : $coins_message = unserialize($coins_message); ?>
					<li class="list-group-item">
						于 <?php echo maoo_format_date($coins_message->date); ?>
						<?php if($coins_message->des=='打赏') : ?>
							打赏 <a href="<?php echo maoo_url('user','index',array('id'=>$coins_message->user_id)); ?>"><?php echo maoo_user_display_name($coins_message->user_id); ?></a>
						<?php elseif($coins_message->des=='被打赏') : ?>
							被 <a href="<?php echo maoo_url('user','index',array('id'=>$coins_message->user_id)); ?>"><?php echo maoo_user_display_name($coins_message->user_id); ?></a> 打赏
						<?php elseif($coins_message->des=='注册') : ?>
							注册本站，获得赠送积分
						<?php elseif($coins_message->des=='登录') : ?>
							登录网站，获得登录积分奖励
						<?php elseif($coins_message->des=='购买隐藏内容') : ?>
							购买文章《<a href="<?php echo maoo_url('post','single',array('id'=>$coins_message->post_id)); ?>"><?php echo $redis->hget('post:'.$coins_message->post_id,'title'); ?></a>》的隐藏内容
						<?php elseif($coins_message->des=='出售隐藏内容') : ?>
							出售文章《<a href="<?php echo maoo_url('post','single',array('id'=>$coins_message->post_id)); ?>"><?php echo $redis->hget('post:'.$coins_message->post_id,'title'); ?></a>》的隐藏内容
                        <?php elseif($coins_message->des=='购物抵现') : ?>
                            购买商品时使用积分抵现，订单号：<?php echo $coins_message->out_trade_no; ?>
						<?php endif; ?>
						=> 积分：<span class="text-<?php if($coins_message->coins>0) : ?>success<?php else : ?>danger<?php endif; ?>"><?php echo $coins_message->coins; ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php else : ?>
					<div class="panel-body">
						暂无任何积分记录
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
