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
		<li>
			<a href="<?php echo maoo_url('bbs','term',array('id'=>$term_id)); ?>">
				<?php echo maoo_term_title($term_id,'bbs'); ?>
			</a>
		</li>
		<li class="active">
			<?php echo $redis->hget('bbs:'.$id,'title'); ?>
		</li>
		<a class="btn btn-primary btn-sm pull-right hidden-xs hidden-sm" href="<?php echo maoo_url('bbs','publish'); ?>"><i class="glyphicon glyphicon-edit"></i> 发表新帖</a>
	</ol>
	<div class="row">
		<div class="col-md-8 col">
			<div class="bbs-single">
				<div class="panel panel-default panel-single-post">
					<div class="panel-heading">
						<h1 class="title mt-0 mb-10"><?php echo $redis->hget('bbs:'.$id,'title'); ?></h1>
						<ul class="list-unstyled bbs-single-info mb-0">
							<li>
								<a class="avatar hidden-xs hidden-sm" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
									<img src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
								</a>
								<a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a>
								<span class="ml-5 mr-5">发表于</span>
								<?php echo date('Y/m/d',$redis->hget('bbs:'.$id,'date')); ?>
							</li>
							<li class="hidden-xs hidden-sm">·</li>
							<li>
								<a href="<?php echo maoo_url('bbs','term',array('id'=>$term_id)); ?>">
									<?php echo maoo_term_title($term_id,'bbs'); ?>
								</a>
							</li>
							<li class="hidden-xs hidden-sm">·</li>
							<li class="hidden-xs hidden-sm"><?php echo maoo_get_views($id,'bbs'); ?>次浏览</li>
							<li class="hidden-xs hidden-sm">·</li>
							<li class="hidden-xs hidden-sm"><?php echo $redis->scard('bbs_comment_id:'.$id); ?>条评论</li>
						</ul>
					</div>
					<div class="panel-body">
                        <?php echo maoo_ad('bbs2'); ?>
						<div class="entry">
							<?php echo $redis->hget('bbs:'.$id,'content'); ?>
						</div>
						<?php if($redis->hget('user:'.maoo_user_id(),'user_level')>7 || maoo_user_id()==$author) : ?>
						<hr>
						<div class="text-center">
							<a class="btn btn-default" href="<?php echo $redis->get('site_url'); ?>?m=bbs&a=edit&id=<?php echo $id; ?>">编辑</a> <a class="btn btn-default" href="<?php echo $redis->get('site_url'); ?>/do/delete.php?id=<?php echo $id; ?>&type=bbs">删除</a>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php if(maoo_user_id()=='') : ?>
				<style>.btn-reply {display: none; }</style>
				<?php endif; ?>
				<div id="react-bbs-comment-box"></div>
				<script type="text/jsx" src="<?php echo $redis->get('site_url'); ?>/theme/default/react/comment.js"></script>
				<script type="text/jsx">
				React.render(
					<BbsCommentBox url="<?php echo $redis->get('site_url'); ?>" type="bbs" uid="<?php echo maoo_user_id(); ?>" ulevel="<?php echo $redis->hget('user:'.maoo_user_id(),'user_level'); ?>" postId="<?php echo $id; ?>" pollInterval={10000} />,
					document.getElementById('react-bbs-comment-box')
				);
				</script>
			</div>
		</div>
		<div class="col-md-4 col hidden-xs hidden-sm">
			<?php include('bbs-side.php'); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
