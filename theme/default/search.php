<?php include('header.php'); ?>
<div class="container search">
	<div class="row post-list">
		<div class="col-sm-8 col-sm-offset-2 col">
			<h1 class="title mt-0 mb-40 text-center">
				搜索“<?php echo $_GET['s']; ?>”
			</h1>
			<ul class="nav nav-pills nav-justified mb-40">
				<li role="presentation" <?php if($_GET['type']!=2 && $_GET['type']!=3 && $_GET['type']!=4) echo 'class="active"'; ?>>
					<a href="<?php echo $redis->get('site_url'); ?>?s=<?php echo $s; ?>&type=1">
						文章
					</a>
				</li>
				<li role="presentation" <?php if($_GET['type']==2) echo 'class="active"'; ?>>
					<a href="<?php echo $redis->get('site_url'); ?>?s=<?php echo $s; ?>&type=2">
						话题
					</a>
				</li>
				<li role="presentation" <?php if($_GET['type']==3) echo 'class="active"'; ?>>
					<a href="<?php echo $redis->get('site_url'); ?>?s=<?php echo $s; ?>&type=3">
						用户
					</a>
				</li>
				<li role="presentation" <?php if($_GET['type']==4) echo 'class="active"'; ?>>
					<a href="<?php echo $redis->get('site_url'); ?>?s=<?php echo $s; ?>&type=4">
						商品
					</a>
				</li>
			</ul>
			<?php if($db) : ?>
			<?php if($_GET['type']==2) : ?>
				<div class="topic-list row">
					<?php foreach($db as $page_id) : ?>
					<div class="topic-<?php echo $page_id; ?> topic col-xs-6 col">
						<div class="topic-pr pr">
							<div class="topic-img" style="background-image: url(<?php echo maoo_fmimg($page_id,'topic'); ?>);"></div>
							<div class="topic-bg"></div>
							<a class="topic-txt" href="<?php echo maoo_url('post','topic',array('id'=>$page_id)); ?>">
								<h2 class="title">
									<?php echo $redis->hget('topic:'.$page_id,'title'); ?>
								</h2>
								<?php echo maoo_sub_count($page_id); ?>人订阅
							</a>
							<?php echo maoo_sub_btn($page_id); ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			<?php elseif($_GET['type']==3) : ?>
				<div class="search-user-list">
					<ul class="media-list mb-0">
						<?php foreach($db as $user_id) : ?>
						<li class="media">
							<div class="media-left">
								<a href="<?php echo maoo_url('user','index',array('id'=>$user_id)); ?>">
									<img class="media-object" src="<?php echo maoo_user_avatar($user_id); ?>" alt="<?php echo maoo_user_display_name($user_id); ?>">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading">
									<a href="<?php echo maoo_url('user','index',array('id'=>$user_id)); ?>"><?php echo maoo_user_display_name($user_id); ?></a>
								</h4>
								<?php echo $redis->hget('user:'.$user_id,'description'); ?>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php elseif($_GET['type']==4) : ?>
				<div class="search-pro-list">
					<ul class="media-list mb-0">
						<?php foreach($db as $page_id) : $cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
						<li class="media">
                            <a class="media-left img-div" href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>">
                                <img class="media-object" src="<?php echo $cover_images[1]; ?>">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('pro:'.$page_id,'title'); ?></a>
                                </h4>
                                <div class="price"><?php echo maoo_pro_min_price($page_id); ?>元</div>
                            </div>
                        </li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php else : ?>
				<?php foreach($db as $page_id) : ?>
				<div class="post-<?php echo $page_id; ?> post">
					<a class="pull-left" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
						<img class="mb-10" src="<?php echo maoo_fmimg($page_id); ?>">
					</a>
					<div class="post-right">
						<h2 class="title">
							<a href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
								<?php echo $redis->hget('post:'.$page_id,'title'); ?>
							</a>
						</h2>
						<?php $author = $redis->hget('post:'.$page_id,'author'); ?>
						<div class="author mb-10">
							<a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a> • <?php echo date('Y/m/d',$redis->hget('post:'.$page_id,'date')); ?>
						</div>
						<div class="entry mb-10">
							<?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),70); ?>
						</div>
						<ul class="list-inline mb-0">
							<?php if($redis->hget('post:'.$page_id,'topic')>0) : ?>
							<li><i class="glyphicon glyphicon-tag"></i> <a href="<?php echo maoo_url('post','topic',array('id'=>$redis->hget('post:'.$page_id,'topic'))); ?>"><?php echo $redis->hget('topic:'.$redis->hget('post:'.$page_id,'topic'),'title'); ?></a></li>
							<?php endif; ?>
							<li><i class="glyphicon glyphicon-heart"></i> <?php echo maoo_like_count($page_id); ?></li>
							<li><i class="glyphicon glyphicon-eye-open"></i> <?php echo maoo_get_views($page_id); ?></li>
						</ul>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php echo maoo_pagenavi($count,$page_now); ?>
			<?php else : ?>
				<div class="nothing">没有搜索到任何结果！</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
