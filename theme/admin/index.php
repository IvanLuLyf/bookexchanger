<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<?php $version = '20160722'; $new_version = file_get_contents('http://www.mao10.com/version.txt'); if($new_version>$version) : ?>
			<div class="well">
				<h3 class="mt-0">发现新版本</h3>
				<div class="mb-10"><?php echo file_get_contents('http://www.mao10.com/update.txt'); ?></div>
				<a class="btn btn-danger btn-lg" href="<?php echo $redis->get('site_url'); ?>/do/update.php">
					<i class="fa fa-refresh"></i> 升级
				</a>
				<div class="clearfix mb-10"></div>
				<small>升级前请确认开启了php的ZipArchive扩展</small>
			</div>
			<?php endif; ?>
			<?php if(DB_TYPE=='redis') : ?>
			<div class="well">
				<ul class="list-unstyled mb-0">
					<li>数据库版本：<?php $redis_info = $redis->info(); echo $redis_info['redis_version']; ?></li>
					<li>数据总数：<?php echo $redis->dbsize(); ?></li>
					<li>内存占用：<?php echo round(memory_get_usage()/1024,2); ?>KB</li>
					<li>最后备份时间：<?php echo date('Y/m/d H:i:s',$redis->lastSave()); ?> <a class="ml-10" href="<?php echo $redis->get('site_url'); ?>/do/bgsave.php">立即备份</a></li>
					<li>备份位置：<?php $dir = $redis->config("GET", "dir"); echo $dir['dir']; ?></li>
				</ul>
			</div>
			<?php endif; ?>
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/control.php">
				<div class="form-group">
					<label>
						网站名称
					</label>
					<input type="text" name="name" class="form-control" value="<?php echo $redis->get('site_name'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						网站地址
					</label>
					<input type="url" name="url" class="form-control" value="<?php echo $redis->get('site_url'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						首页默认展示
					</label>
					<select class="form-control" name="hometheme">
						<option value="1" <?php if($redis->get('hometheme')!=2) echo 'selected'; ?>>默认</option>
						<option value="2" <?php if($redis->get('hometheme')==2) echo 'selected'; ?>>商品</option>
					</select>
				</div>
				<div class="form-group">
					<label>
						主题
				    </label>
				    <div class="row">
				    	<div class="col-md-4 col-lg-3">
				    		<select name="theme" class="form-control">
						        <?php $dir = __DIR__."/../../theme"; if (is_dir($dir)) : if ($dh = opendir($dir)) : while (($file = readdir($dh))!= false) : $filePath = $dir.'/'.$file; if (is_dir($filePath)) : if($file!='.' && $file!='..' && $file!='admin' && $file!='mobile') : ?>
						    	<option value="<?php echo $file; ?>" <?php if($redis->get('theme')==$file) : ?>selected<?php endif; ?>><?php echo $file; ?></option>
						    	<?php endif;endif; endwhile; closedir($dh); endif; endif; ?>
						    </select>
						</div>
					</div>
				    <p class="help-block">
				        网站使用的主题，默认为<code>default</code>
				    </p>
				</div>
				<div class="form-group">
					<label>
						商品模块
					</label>
					<select class="form-control" name="promod">
						<option value="1" <?php if($redis->get('promod')==1) echo 'selected'; ?>>关闭</option>
						<option value="0" <?php if($redis->get('promod')!=1) echo 'selected'; ?>>开启</option>
					</select>
				    <p class="help-block">
				        关闭模块后，需要在<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=nav">导航设置</a>中移除对应模块相关链接。
				    </p>
				</div>
				<div class="form-group">
					<label>
						社区模块
					</label>
					<select class="form-control" name="bbsmod">
						<option value="1" <?php if($redis->get('bbsmod')==1) echo 'selected'; ?>>关闭</option>
						<option value="0" <?php if($redis->get('bbsmod')!=1) echo 'selected'; ?>>开启</option>
					</select>
				    <p class="help-block">
				        关闭模块后，需要在<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=nav">导航设置</a>中移除对应模块相关链接。
				    </p>
				</div>
				<div class="form-group">
					<label>
						众筹模块
					</label>
					<select class="form-control" name="dealmod">
						<option value="1" <?php if($redis->get('dealmod')==1) echo 'selected'; ?>>关闭</option>
						<option value="0" <?php if($redis->get('dealmod')!=1) echo 'selected'; ?>>开启</option>
					</select>
				    <p class="help-block">
				        关闭模块后，需要在<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=nav">导航设置</a>中移除对应模块相关链接。
				    </p>
				</div>
				<div class="form-group">
					<label>
						每页文章数量
					</label>
					<input type="text" name="page_size" class="form-control" value="<?php echo $redis->get('page_size'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						登录注册页面背景图片
					</label>
					<input type="text" name="signbg1" class="form-control" value="<?php echo $redis->get('site:signbg1'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<input type="text" name="signbg2" class="form-control" value="<?php echo $redis->get('site:signbg2'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<input type="text" name="signbg3" class="form-control" value="<?php echo $redis->get('site:signbg3'); ?>" placeholder="">
					<p class="help-block">仅支持外链图片地址。</p>
				</div>
				<div class="form-group">
					<label>
						创建话题权限
					</label>
					<select class="form-control" name="topic_permission">
						<option value="1" <?php if($redis->get('topic_permission')!=2) echo 'selected'; ?>>所有人可创建</option>
						<option value="2" <?php if($redis->get('topic_permission')==2) echo 'selected'; ?>>仅管理员可创建</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="topic_number" class="form-control" value="<?php echo $redis->get('topic_number'); ?>" placeholder="每个用户建立话题的最大数量（管理员不受此影响，0为不限制）">
				</div>
				<div class="form-group">
					<label>
						统计代码
					</label>
					<textarea rows="3" name="statistical_code" class="form-control"><?php echo $redis->get('statistical_code'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						又拍云接口设置
					</label>
					<select class="form-control" name="upyun">
						<option value="1" <?php if($redis->get('upyun')!=2) echo 'selected'; ?>>关闭</option>
						<option value="2" <?php if($redis->get('upyun')==2) echo 'selected'; ?>>开启</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="upyun_bucket" class="form-control" value="<?php echo $redis->get('upyun_bucket'); ?>" placeholder="空间名">
				</div>
				<div class="form-group">
					<input type="text" name="upyun_user" class="form-control" value="<?php echo $redis->get('upyun_user'); ?>" placeholder="操作员账号">
				</div>
				<div class="form-group">
					<input type="text" name="upyun_pwd" class="form-control" value="<?php echo $redis->get('upyun_pwd'); ?>" placeholder="密码">
				</div>
				<div class="form-group">
					<input type="url" name="upyun_url" class="form-control" value="<?php echo $redis->get('upyun_url'); ?>" placeholder="访问地址">
				</div>
				<div class="form-group">
					<label>
						七牛云接口设置
					</label>
					<select class="form-control" name="qiniu">
						<option value="1" <?php if($redis->get('qiniu')!=2) echo 'selected'; ?>>关闭</option>
						<option value="2" <?php if($redis->get('qiniu')==2) echo 'selected'; ?>>开启</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="qiniu_bucket" class="form-control" value="<?php echo $redis->get('qiniu_bucket'); ?>" placeholder="空间名">
				</div>
				<div class="form-group">
					<input type="text" name="qiniu_ak" class="form-control" value="<?php echo $redis->get('qiniu_ak'); ?>" placeholder="AK">
				</div>
				<div class="form-group">
					<input type="text" name="qiniu_sk" class="form-control" value="<?php echo $redis->get('qiniu_sk'); ?>" placeholder="SK">
				</div>
				<div class="form-group">
					<input type="url" name="qiniu_url" class="form-control" value="<?php echo $redis->get('qiniu_url'); ?>" placeholder="访问地址">
				</div>
				<div class="form-group">
					<label>
						伪静态
					</label>
					<select class="form-control" name="rewrite">
						<option value="1" <?php if($redis->get('rewrite')!=2) echo 'selected'; ?>>关闭</option>
						<option value="2" <?php if($redis->get('rewrite')==2) echo 'selected'; ?>>开启</option>
					</select>
				</div>
				<p class="help-block">开启伪静态后需配置伪静态规则，以下伪静态规则只适用于apache服务器。</p>
				<?php if($redis->get('rewrite')==2) : ?>
				<h5 class="title">.htaccess内容</h5>
				<div class="well mb-10">
					<p>&lt;IfModule mod_rewrite.c&gt;</p>
					<p>RewriteEngine on</p>
					<p>RewriteBase /</p>
					<p>RewriteRule ([a-zA-Z]{1,})-([a-zA-Z]{1,}).html$ index.php?m=$1&a=$2&%{QUERY_STRING} [L]</p>
					<p>RewriteRule post-([0-9]{1,}).html$ index.php?m=post&a=single&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule pro-([0-9]{1,}).html$ index.php?m=pro&a=single&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule bbs-([0-9]{1,}).html$ index.php?m=bbs&a=single&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule deal-([0-9]{1,}).html$ index.php?m=deal&a=single&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule user-([0-9]{1,}).html$ index.php?m=user&a=index&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule topic-([0-9]{1,}).html$ index.php?m=post&a=topic&id=$1&%{QUERY_STRING} [L]</p>
					<p>RewriteRule ([a-zA-Z]{1,})-term-([0-9]{1,}).html$ index.php?m=$1&a=term&id=$2&%{QUERY_STRING} [L]</p>
					<p>errorDocument 404 /404.php</p>
					<p class="mb-0">&lt;/IfModule&gt;</p>
				</div>
				<?php endif; ?>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
