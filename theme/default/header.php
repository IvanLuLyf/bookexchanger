<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php if($maoo_description) : ?>
<meta name="keywords" content="<?php echo $maoo_keywords; ?>">
<meta name="description" content="<?php echo $maoo_description; ?>">
<?php endif; ?>
<title><?php echo $maoo_title; ?></title>
<link href="<?php echo $redis->get('site_url'); ?>/public/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $redis->get('site_url'); ?>/public/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $redis->get('site_url'); ?>/theme/default/style.css" rel="stylesheet">
<link href="<?php echo $redis->get('site_url'); ?>/theme/default/media.css" rel="stylesheet">
<script src="<?php echo $redis->get('site_url'); ?>/public/js/jquery.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/html5shiv.min.js"></script>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<a id="toptg"></a>
<nav id="mobile-nav" class="navbar navbar-default navbar-fixed-top visible-xs-block visible-sm-block">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" id="mobile-nav-btn" class="navbar-toggle collapsed" aria-expanded="false">
				<i class="fa fa-bars"></i>
			</button>
			<button type="button" class="navbar-toggle collapsed" data-toggle="modal" data-target="#cartModal">
				<i class="fa fa-shopping-cart"></i>
			</button>
			<a class="navbar-brand" href="<?php echo $redis->get('site_url'); ?>">
				<img src="<?php echo $redis->get('site_url'); ?>/theme/default/img/logo-sm.png" alt="<?php echo $redis->get('site_name'); ?>" />
			</a>
		</div>
	</div>
</nav>
<div id="nav-show-bg"></div>
<script>
    $('#mobile-nav-btn').click(function(){
        $('#header').addClass('navshow');
        $('#nav-show-bg').css('display','block');
    });
    $('#nav-show-bg').click(function(){
        $('#header').removeClass('navshow');
        $(this).css('display','none');
    });
</script>
<header id="header">
    <div class="mobile-header-logo visible-xs-block visible-sm-block"></div>
	<span class="bg"></span>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col">
                <a class="logo pull-left hidden-xs" href="<?php echo $redis->get('site_url'); ?>">
				    <img src="<?php echo $redis->get('site_url'); ?>/theme/default/img/logo-sm.png" alt="<?php echo $redis->get('site_name'); ?>" />
				</a>
				<div id="nav">
					<?php echo maoo_nav(); ?>
				</div>
			</div>
			<div class="col-md-4 col text-right" id="header-tools">
				<ul class="list-inline mb-0">
					<li>
						<a href="#" data-toggle="modal" data-target="#searchModal">
							<i class="glyphicon glyphicon-search"></i>
						</a>
					</li>
					<li>
						<a href="<?php echo maoo_url('post','publish'); ?>">
							发布文章
						</a>
					</li>
					<?php if(maoo_user_id()) : ?>
                    <?php 
                        $carts_count = $redis->scard('cart:user:1:'.maoo_user_id());
                        if($carts_count>0) : 
                    ?>
					<li class="cart">
						<a href="#" data-toggle="modal" data-target="#cartModal">
		                  <i class="glyphicon glyphicon-shopping-cart"></i>
						</a>
					</li>
                    <?php endif; ?>
					<li class="user">
						<img src="<?php echo maoo_user_avatar(maoo_user_id()); ?>" alt="<?php echo maoo_user_display_name(maoo_user_id()); ?>">
						<ul class="dropdown-menu" role="menu">
							<div class="border">
								<div class="arrow">

								</div>
								<li>
									<a href="<?php echo maoo_url('user','index'); ?>">
										<i class="glyphicon glyphicon-home"></i> 我的主页
									</a>
								</li>
                                <?php if($redis->get('promod')!=1) : ?>
								<li>
									<a href="<?php echo maoo_url('user','order'); ?>">
										<i class="glyphicon glyphicon-tower"></i> 我的订单
									</a>
								</li>
                                <?php endif; ?>
                                <?php if($redis->get('dealmod')!=1) : ?>
								<li>
									<a href="<?php echo maoo_url('user','deal'); ?>">
										<i class="glyphicon glyphicon-tint"></i> 我的项目
									</a>
								</li>
                                <?php endif; ?>
								<li>
									<a href="#" data-toggle="modal" data-target="#coinsModal">
										<i class="glyphicon glyphicon-credit-card"></i> 我的财产
									</a>
								</li>
								<li>
									<a href="<?php echo maoo_url('user','set'); ?>">
										<i class="glyphicon glyphicon-cog"></i> 账号设置
									</a>
								</li>
								<li>
									<a href="<?php echo maoo_url('user','pass'); ?>">
										<i class="glyphicon glyphicon-lock"></i> 修改密码
									</a>
								</li>
								<?php if($redis->hget('user:'.maoo_user_id(),'user_level')==10) : ?>
								<li>
									<a href="<?php echo maoo_url('admin','index'); ?>">
										<i class="glyphicon glyphicon-dashboard"></i> 网站管理
									</a>
								</li>
								<?php endif; ?>
								<li>
									<a href="<?php echo maoo_url('user','logout'); ?>">
										<i class="glyphicon glyphicon-off"></i> 退出
									</a>
								</li>
							</div>
						</ul>
					</li>
					<?php else : ?>
					<li class="user unlog">
						<a href="<?php echo $redis->get('site_url'); ?>?m=user&a=login<?php if($_GET['a']=='logout') : echo '&noreferer=yes'; endif; ?>">登录</a>
						<span>|</span>
						<a href="<?php echo $redis->get('site_url'); ?>?m=user&a=register<?php if($_GET['a']=='logout') : echo '&noreferer=yes'; endif; ?>">注册</a>
						<?php if(maoo_social_sign()) : ?>
						<ul class="dropdown-menu" role="menu">
							<div class="border">
								<div class="arrow">

								</div>
								<li>
									第三方账号登录：
								</li>
								<li>
									<?php if($redis->get('user:connect:qq:appid') && $redis->get('user:connect:qq:appkey')) : ?>
									<a href="<?php echo $redis->get('site_url'); ?>/public/connect-qq/oauth">
										<img src="<?php echo $redis->get('site_url'); ?>/public/img/connect-qq-24.png" />
									</a>
									<?php endif; ?>
									<?php if($redis->get('user:connect:weibo:appkey') && $redis->get('user:connect:weibo:appsecret')) : ?>
									<a href="<?php echo $redis->get('site_url'); ?>/public/connect-weibo/oauth">
										<img src="<?php echo $redis->get('site_url'); ?>/public/img/connect-weibo-24.png" />
									</a>
									<?php endif; ?>
								</li>
							</div>
						</ul>
						<?php endif; ?>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</header>
<?php if(maoo_user_id()) : ?>
<div class="modal fade" id="coinsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
				<h4 class="modal-title">
					<i class="glyphicon glyphicon-credit-card"></i> 我的财产
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6 col">
						<div class="cash">
							<div class="cashBox text-center mb-20">
								<h4 class="title">现金余额</h4>
								<h2 class="number"><?php echo maoo_user_cash(maoo_user_id()); ?> <small>元</small></h2>
								<a href="<?php echo maoo_url('user','cash'); ?>#ucash1">充值</a>
								<a href="<?php echo maoo_url('user','cash'); ?>#ucash2">提现</a>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="glyphicon glyphicon-list-alt"></i> 账单明细
									<a class="pull-right" href="<?php echo maoo_url('user','cash'); ?>#ucash3">更多</a>
								</div>
								<?php $cash_db = $redis->sort('cash:user_id:'.maoo_user_id(),array('sort'=>'desc','limit'=>array(0,10))); if($cash_db) : ?>
									<ul class="list-group">
										<?php foreach($cash_db as $cash_id) : ?>
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
					<div class="col-sm-6 col">
						<div class="coins">
							<div class="coinsBox text-center mb-20">
								<h4 class="title">现有积分</h4>
								<h2 class="number"><?php echo maoo_user_coins(maoo_user_id()); ?></h2>
								<a href="<?php echo maoo_url('user','coins'); ?>#ucash1">购买积分</a>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="glyphicon glyphicon-list-alt"></i> 积分记录
									<a class="pull-right" href="<?php echo maoo_url('user','coins'); ?>#ucash3">更多</a>
								</div>
								<?php
									$coins_db = $redis->lrange('coins:user:'.maoo_user_id(),0,9);
									if($coins_db) :
								?>
								<ul class="list-group">
									<?php foreach($coins_db as $coins_message) : $coins_message = unserialize($coins_message); ?>
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
                                        <?php elseif($coins_message->des=='积分竞猜') : ?>
                                            参加积分竞猜《<a href="<?php echo maoo_url('post','single',array('id'=>$coins_message->post_id)); ?>"><?php echo $redis->hget('post:'.$coins_message->post_id,'title'); ?></a>》，支付积分
                                        <?php elseif($coins_message->des=='积分竞猜获胜奖励') : ?>
                                            参加积分竞猜《<a href="<?php echo maoo_url('post','single',array('id'=>$coins_message->post_id)); ?>"><?php echo $redis->hget('post:'.$coins_message->post_id,'title'); ?></a>》，赢得积分
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
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link pull-left">
					<?php echo maoo_coins_time(maoo_user_id()); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					关闭
				</button>
			</div>
		</div>
	</div>
</div>
<script>
    $('#messageModal').on('show.bs.modal', function (e) {
        $('#header').removeClass('navshow');
        $('#nav-show-bg').css('display','none');
    });
    $('#coinsModal').on('show.bs.modal', function (e) {
        $('#header').removeClass('navshow');
        $('#nav-show-bg').css('display','none');
    });
</script>
<?php endif; ?>
