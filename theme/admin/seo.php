<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/seo.php">
				<div class="form-group">
					<label>
						首页Title
					</label>
					<input type="text" name="title" class="form-control" value="<?php echo $redis->get('site_title'); ?>" placeholder="">
				</div>
				<div class="form-group">
					<label>
						首页Keywords
					</label>
					<input type="text" name="keywords" class="form-control" value="<?php echo $redis->get('site_keywords'); ?>" placeholder="多个关键词以英文半角逗号隔开">
				</div>
				<div class="form-group">
					<label>
						首页Description
					</label>
					<textarea class="form-control" name="description" rows="3"><?php echo $redis->get('site_description'); ?></textarea>
				</div>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>