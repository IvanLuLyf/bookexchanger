<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
            <?php include('set-nav.php'); ?>
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/ad.php">
				<div class="form-group">
					<label>
						首页 - 幻灯下方横幅广告
					</label>
					<textarea class="form-control" name="page[home1]" rows="3"><?php echo $redis->hget('ad','home1'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						首页 - 第二篇文章下方横幅广告
					</label>
					<textarea class="form-control" name="page[home2]" rows="3"><?php echo $redis->hget('ad','home2'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						首页 - 侧边栏广告
					</label>
					<textarea class="form-control" name="page[home3]" rows="3"><?php echo $redis->hget('ad','home3'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						文章 - 最新文章频道顶横幅广告
					</label>
					<textarea class="form-control" name="page[post1]" rows="3"><?php echo $redis->hget('ad','post1'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						文章 - 最新文章频道侧边栏广告
					</label>
					<textarea class="form-control" name="page[post2]" rows="3"><?php echo $redis->hget('ad','post2'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						文章 - 单个话题首页上方横幅广告
					</label>
					<textarea class="form-control" name="page[post3]" rows="3"><?php echo $redis->hget('ad','post3'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						文章 - 文章内容开始处横幅广告
					</label>
					<textarea class="form-control" name="page[post4]" rows="3"><?php echo $redis->hget('ad','post4'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						文章 - 文章内容页评论区上方横幅广告
					</label>
					<textarea class="form-control" name="page[post5]" rows="3"><?php echo $redis->hget('ad','post5'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						社区 - 侧边栏广告
					</label>
					<textarea class="form-control" name="page[bbs1]" rows="3"><?php echo $redis->hget('ad','bbs1'); ?></textarea>
				</div>
				<div class="form-group">
					<label>
						社区 - 帖子内容开始处横幅广告
					</label>
					<textarea class="form-control" name="page[bbs2]" rows="3"><?php echo $redis->hget('ad','bbs2'); ?></textarea>
				</div>
				<button type="submit" class="btn btn-block btn-default">
					提交
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>