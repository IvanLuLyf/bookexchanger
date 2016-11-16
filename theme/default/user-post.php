<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2 col">
			<?php include_once('user-nav-1.php'); ?>
			<div class="post-list">
				<?php foreach($db as $page_id) : if($redis->hget('post:'.$page_id,'title')) : ?>
				<div class="post-<?php echo $page_id; ?> post mb-20">
                            <a class="pull-left img-div" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
                                <img src="<?php echo maoo_fmimg($page_id); ?>">
                            </a>
                            <div class="post-right">
                                <h2 class="title">
                                    <a class="wto" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
                                        <?php echo $redis->hget('post:'.$page_id,'title'); ?>
                                    </a>
                                </h2>
                                <?php $author = $redis->hget('post:'.$page_id,'author'); ?>
                                <div class="author mb-10">
                                    <a class="avatar" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><img src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>"></a> <a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a><span class="dian">•</span><span><?php echo date('Y/m/d',$redis->hget('post:'.$page_id,'date')); ?></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="entry mb-10">
                                    <?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),33); ?>
                                </div>
                                <ul class="list-inline mb-0">
                                    <li><i class="fa fa-heart-o"></i> <?php echo maoo_like_count($page_id); ?></li>
                                    <li><i class="fa fa-eye"></i> <?php echo maoo_get_views($page_id); ?></li>
                                    <?php if($redis->hget('term:post:'.$id,'author')==maoo_user_id()) : ?>
                                    <li><i class="fa fa-minus-square-o"></i> <a href="<?php echo $redis->get('site_url'); ?>/do/remove-from-term.php?id=<?php echo $page_id; ?>&t=<?php echo $id; ?>">从话题中移除</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php elseif($redis->hget('post:'.$page_id,'pro')>0) : $pro_id = $redis->hget('post:'.$page_id,'pro'); $cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image'));  ?>
                <div class="post-<?php echo $page_id; ?> post mb-20">
                            <a class="pull-left img-div" href="<?php echo maoo_url('pro','single',array('id'=>$pro_id)); ?>">
                                <img src="<?php echo $cover_images[1]; ?>">
                            </a>
                            <div class="post-right">
                                <h2 class="title">
                                    <a class="wto" href="<?php echo maoo_url('pro','single',array('id'=>$pro_id)); ?>">
                                        <?php echo $redis->hget('pro:'.$pro_id,'title'); ?>
                                    </a>
                                </h2>
                                <?php $author = $redis->hget('pro:'.$pro_id,'author'); ?>
                                <div class="author mb-10">
                                    <a class="avatar" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><img src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>"></a> <a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a><span class="dian">•</span><span><?php echo date('Y/m/d',$redis->hget('pro:'.$pro_id,'date')); ?></span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="entry mb-10">
                                    <?php echo maoo_cut_str(strip_tags($redis->hget('pro:'.$pro_id,'content')),33); ?>
                                </div>
                                <ul class="list-inline mb-0">
                                    <?php if($redis->hget('pro:'.$pro_id,'post_term')>0) : ?>
                                    <li><i class="glyphicon glyphicon-tag"></i> <a href="<?php echo maoo_url('post','term',array('id'=>$redis->hget('pro:'.$pro_id,'post_term'))); ?>"><?php echo maoo_term_title($redis->hget('pro:'.$pro_id,'post_term')); ?></a></li>
                                    <?php endif; ?>
                                    <li><i class="glyphicon glyphicon-eye-open"></i> <?php echo maoo_get_views($pro_id,'pro'); ?></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php endif; ?>
				<?php endforeach; ?>
                <?php echo maoo_pagenavi($count,$page_now); ?>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>