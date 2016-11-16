<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<h4 class="title">添加现有链接</h4>
			<form class="mb-30" method="post" action="<?php echo $redis->get('site_url'); ?>/do/nav.php">
				<input type="hidden" name="type" value="1">
				<div class="row">
					<div class="col-xs-2 col">
						<input type="text" name="number" class="form-control" placeholder="序号">
					</div>
					<div class="col-xs-8 col">
						<select name="page" class="form-control">
							<option>
								请选择...
							</option>
							<option value="a">
								首页
							</option>
							<option value="b">
								最新
							</option>
							<option value="c">
								话题
							</option>
							<option value="d">
								作者
							</option>
							<option value="e">
								社区
							</option>
							<option value="f">
								商品
							</option>
							<option value="g">
								晒单
							</option>
							<option value="h">
								众筹
							</option>
							<?php $db = $redis->lrange('new_post_id:page',0,99); ?>
							<?php foreach($db as $page_id) : ?>
							<option value="<?php echo $page_id; ?>">
								<?php echo $redis->hget('post:page:'.$page_id,'title'); ?>
							</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-2 col">
						<button type="submit" class="btn btn-default">
							提交
						</button>
					</div>
				</div>
			</form>
			<h4 class="title">添加自定义链接</h4>
			<form class="mb-30" method="post" action="<?php echo $redis->get('site_url'); ?>/do/nav.php">
				<input type="hidden" name="type" value="2">
				<div class="row">
					<div class="col-xs-2 col">
						<input type="text" name="number" class="form-control" placeholder="序号">
					</div>
					<div class="col-xs-5 col">
						<input type="text" name="link" class="form-control" placeholder="链接">
					</div>
					<div class="col-xs-3 col">
						<input type="text" name="text" class="form-control" placeholder="文字">
					</div>
					<div class="col-xs-2 col">
						<button type="submit" class="btn btn-default">
							提交
						</button>
					</div>
				</div>
			</form>
			<h4 class="title">全部导航项目</h4>
			<?php $db = $redis->zrevrange('nav:list',0,99); ?>
			<?php if($db) : ?>
			<?php foreach($db as $page_id) : $number = $redis->zscore('nav:list',$page_id); ?>
			<form class="mb-30" method="post" action="<?php echo $redis->get('site_url'); ?>/do/nav.php">
				<input type="hidden" name="type" value="3">
				<input type="hidden" name="id" value="<?php echo $page_id; ?>">
				<div class="row">
					<div class="col-xs-2 col">
						<input type="text" name="number" class="form-control" value="<?php echo $number; ?>" placeholder="序号">
					</div>
					<div class="col-xs-4 col">
						<input type="text" name="link" class="form-control" value="<?php echo $redis->hget('nav:'.$page_id,'link'); ?>" placeholder="链接">
					</div>
					<div class="col-xs-3 col">
						<input type="text" name="text" class="form-control" value="<?php echo $redis->hget('nav:'.$page_id,'text'); ?>" placeholder="文字">
					</div>
					<div class="col-xs-3 col">
						<button type="submit" class="btn btn-warning">
							修改
						</button>
						<a class="btn btn-default" href="<?php echo $redis->get('site_url'); ?>/do/nav.php?del=<?php echo $page_id; ?>">
							移除
						</a>
					</div>
				</div>
			</form>
			<?php endforeach; ?>
			<?php else : ?>
			<div class="nothing">
				暂无任何导航项目，将使用默认导航
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>