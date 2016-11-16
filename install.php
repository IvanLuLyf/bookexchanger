<?php
header("Content-Type: text/html; charset=UTF-8");
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('PHP版本必须为5.3以上！');
require_once __DIR__."/do/functions.php";
$site_url = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
$site_url = preg_replace("/\/[a-z0-9]+\.php.*/is", "", $site_url);

$setup = true;
$dbprefix = $redis->prefix;
$table[1] = "CREATE TABLE IF NOT EXISTS ".$dbprefix."string (
	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	name varchar(50),
	content varchar(200)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$table[2] = "CREATE TABLE IF NOT EXISTS ".$dbprefix."hash (
	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	name varchar(50),
	content longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$table[3] = "CREATE TABLE IF NOT EXISTS ".$dbprefix."set (
	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	name varchar(50),
	content varchar(200)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$table[4] = "CREATE TABLE IF NOT EXISTS ".$dbprefix."zset (
	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	name varchar(50),
	score bigint(20),
	content varchar(200)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$table[5] = "CREATE TABLE IF NOT EXISTS ".$dbprefix."list (
	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	name varchar(50),
	content longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
foreach($table as $sql) :
	if($redis->query($sql)) :
		//success
	else:
		$setup = false;
	endif;
endforeach;
if($setup) :
	$redis->set('site_url',$site_url);
	$redis->set('site_name','Mao10CMS');
	$redis->set('page_size','10');
	$redis->set('fmimg','/public/img/upload.jpg');
endif;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
	安装Mao10CMS
</title>
<link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<style>
html {text-rendering: optimizeLegibility;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale; height: 101%;}
body {font-family: "Microsoft Yahei","Helvetica Neue"; font-size: 18px;  color: #34495e; padding-top: 90px; background-color: #f3f6f9; }
a {color:#369;outline:medium none; text-decoration:none; star:expression(this.onFocus=this.blur());}
a:hover {text-decoration: none; color: #3498db;}
.mb-0 {margin-bottom: 0px!important;}
.mt-0 {margin-top: 0px!important;}
.mt-10 {margin-top: 10px;}
.mb-5 {margin-bottom: 5px;}
.mb-10 {margin-bottom: 10px;}
.mb-20 {margin-bottom: 20px;}
.mb-30 {margin-bottom: 30px;}
.mb-40 {margin-bottom: 40px;}
.mt-20 {margin-top: 20px;}
.mt-30 {margin-top: 30px;}
.mt-40 {margin-top: 40px;}
.ml-5 {margin-left: 5px;}
.ml-10 {margin-left: 10px;}
.mr-5 {margin-right: 5px;}
.mr-10 {margin-right: 10px;}
.mr-20 {margin-right: 20px;}
.bb-0 {border-bottom: 0;}
.pt-0 {padding-top: 0!important;}
.pt-10 {padding-top: 10px;}

.panel-default {border: 0;}
.panel-body {padding: 35px; }
.nothing { text-align: center; line-height: 150px; font-size: 20px; background-color: #f5f5f5; }
</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="text-center mb-40">
						<img src="<?php echo $site_url; ?>/public/img/mclogi.png">
					</div>
					<div class="nothing">
						<?php if($setup) : ?>
						安装成功
						<?php else : ?>
						安装失败，请检查数据库信息是否配置正确！
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
