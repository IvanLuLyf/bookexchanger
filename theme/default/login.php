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
<link href="<?php echo $redis->get('site_url'); ?>/theme/default/style.css" rel="stylesheet">
<link href="<?php echo $redis->get('site_url'); ?>/theme/default/media.css" rel="stylesheet">
<script src="<?php echo $redis->get('site_url'); ?>/public/js/jquery.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/react.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/JSXTransformer.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/html5shiv.min.js"></script>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/respond.min.js"></script>
<![endif]-->
<style>
html {height: 100%; }
body {padding-top: 0; height: 100%; background: transparent; }
footer,
.pro-side-fixed {display: none; }
</style>
</head>
<body>
<div class="signPage pr">
			<div class="signFormBox" style="margin-top:-277px; ">
				<form method="post" class="mb-30" role="form" action="<?php echo $redis->get('site_url'); ?>/do/login.php">
					<div class="text-center mb-30">
						<a href="<?php echo $redis->get('site_url'); ?>">
							<img src="<?php echo $redis->get('site_url'); ?>/public/img/mclogi.png">
						</a>
						<h2 class="title">账号登录</h2>
					</div>
					<div class="form-group">
						<div class="input-group input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-user"></i>
							</span>
							<input class="hidden">
							<input type="text" name="user_name" class="form-control" placeholder="用户名或手机号码" value="<?php echo $_SESSION['user_name']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-lock"></i>
							</span>
							<input type="password" name="user_pass" class="form-control" placeholder="密码">
						</div>
					</div>
					<button type="submit" class="btn btn-block btn-default btn-lg">
						提交
					</button>
					<?php if($_GET['noreferer']!='yes') : ?>
					<input type="hidden" name="user_referer" class="form-control" value="<?php if($_GET['referer']==1) : echo $_SESSION['user_referer']; else : echo $_SERVER["HTTP_REFERER"]; endif; ?>">
					<?php endif; ?>
				</form>
				<?php if(maoo_social_sign()) : ?>
				<ul class="list-inline text-center mb-20">
					<?php if($redis->get('user:connect:qq:appid') && $redis->get('user:connect:qq:appkey')) : ?>
					<li>
						<a href="<?php echo $redis->get('site_url'); ?>/public/connect-qq/oauth">
							<img src="<?php echo $redis->get('site_url'); ?>/public/img/connect-qq.png" />
						</a>
					</li>
					<?php endif; ?>
					<?php if($redis->get('user:connect:weibo:appkey') && $redis->get('user:connect:weibo:appsecret')) : ?>
					<li>
						<a href="<?php echo $redis->get('site_url'); ?>/public/connect-weibo/oauth">
							<img src="<?php echo $redis->get('site_url'); ?>/public/img/connect-weibo.png" />
						</a>
					</li>
					<?php endif; ?>
				</ul>
				<?php endif; ?>
				<ul class="list-inline text-center mb-20">
					<li><a href="<?php echo $redis->get('site_url'); ?>?m=user&a=register<?php if($_GET['admin']=='logout') : echo '&noreferer=yes'; endif; ?>">注册账号</a></li>
					<li><a href="<?php echo $redis->get('site_url'); ?>?m=user&a=lostpass">忘记密码？</a></li>
				</ul>
				<div class="text-center">
					Powered By <a target="_blank" href="http://www.mao10.com">Mao10CMS</a>
				</div>
				<div class="bg"></div>
			</div>
	<?php
		if($redis->get('site:signbg1')) :
			$signbg[] = $redis->get('site:signbg1');
		endif;
		if($redis->get('site:signbg2')) :
			$signbg[] = $redis->get('site:signbg2');
		endif;
		if($redis->get('site:signbg3')) :
			$signbg[] = $redis->get('site:signbg3');
		endif;
		if($signbg) :
			$bgimg = $signbg[array_rand($signbg)];
		else :
			$bgimg = $redis->get('site_url').'/public/img/signbg.jpg';
		endif;
	?>
	<div class="bg" style="background-image:url(<?php echo $bgimg; ?>);"></div>
</div>
<?php include('footer.php'); ?>
