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
<link href="<?php echo $redis->get('site_url'); ?>/public/css/admin.css" rel="stylesheet">
<script src="<?php echo $redis->get('site_url'); ?>/public/js/jquery.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/react.min.js"></script>
<!--[if lt IE 9]>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/html5shiv.min.js"></script>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<header id="header">
	<span class="bg"></span>
	<div class="container">
		<div class="row">
			<div class="col-xs-5 col">
				<a class="logo pull-left" href="<?php echo $redis->get('site_url'); ?>">
					<img src="<?php echo $redis->get('site_url'); ?>/public/img/logo.png">
				</a>
			</div>
			<div class="col-xs-4 col">

			</div>
			<div class="col-xs-3 col text-right">
				<ul class="list-inline mb-0">
					<li>
						<a href="<?php echo $redis->get('site_url'); ?>">
							返回前台
						</a>
					</li>
					<li>
						<a href="<?php echo maoo_url('user','logout'); ?>">
							退出登录
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
