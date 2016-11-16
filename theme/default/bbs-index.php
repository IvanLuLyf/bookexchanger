<?php include('header.php'); ?>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/react.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/JSXTransformer.js"></script>
<div class="container">
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo maoo_url('bbs'); ?>">
				社区首页
			</a>
		</li>
		<li class="active">
			全部帖子
		</li>
		<a class="btn btn-primary btn-sm pull-right hidden-xs hidden-sm" href="<?php echo maoo_url('bbs','publish'); ?>"><i class="glyphicon glyphicon-edit"></i> 发表新帖</a>
	</ol>
	<div class="row">
		<div class="col-md-8 col">
			<div class="panel panel-default bbs-term">
				<div class="panel-heading hidden-xs hidden-sm">
					<ul class="nav nav-pills">
						<li role="presentation" class="active">
							<a href="<?php echo maoo_url('bbs'); ?>">
								<i class="glyphicon glyphicon-th-list"></i> 社区首页
							</a>
						</li>
						<?php foreach($redis->zrange('term:bbs',0,-1) as $title) : $term_id = $redis->zscore('term:bbs',$title); ?>
						<li role="presentation">
							<a href="<?php echo maoo_url('bbs','term',array('id'=>$term_id)); ?>">
								<i class="glyphicon glyphicon-th-list"></i> <?php echo $title; ?>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<ul class="list-group">
					<?php foreach($db as $page_id) : $author = $redis->hget('bbs:'.$page_id,'author'); ?>
					<li class="list-group-item">
						<div class="media">
							<div class="media-left">
								<a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
									<img class="media-object" src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading">
									<a class="wto" href="<?php echo maoo_url('bbs','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('bbs:'.$page_id,'title'); ?></a>
								</h4>
								<ul class="list-unstyled bbs-single-info">
									<li>
										<a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a> 
										<span class="ml-5 mr-5">发表于</span> 
										<?php echo date('Y/m/d',$redis->hget('bbs:'.$page_id,'date')); ?>
									</li>
									<li>·</li>
									<li><?php echo maoo_get_views($page_id,'bbs'); ?>次浏览</li>
									<li>·</li>
									<li><?php echo $redis->scard('bbs_comment_id:'.$page_id); ?>条评论</li>
								</ul>
							</div>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php echo maoo_pagenavi($count,$page_now); ?>
			<?php include('bbs-quickpub.php'); ?>
		</div>
		<div class="col-md-4 col hidden-xs hidden-sm">
			<?php include('bbs-side.php'); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>