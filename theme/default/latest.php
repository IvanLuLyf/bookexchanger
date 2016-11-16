<?php include('header.php'); ?>
<div class="container" id="latest">
    <?php echo maoo_ad('post1'); ?>
    <div class="panel panel-default terms-panel">
        <div class="panel-body">
            <div class="terms-title">
                全部话题：
            </div>
            <div class="terms-item-list">
                <a class="terms-item active" href="<?php echo maoo_url('post','latest'); ?>">全部</a>
                <?php foreach($redis->zrange('term:post',0,-1) as $title) : ?>
                <a class="terms-item" href="<?php echo maoo_url('post','term',array('id'=>$redis->zscore('term:post',$title))); ?>"><?php echo $title; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row">
		<div class="col-sm-9 col">
            <div class="post-list">
                <div class="home-side-box mb-0">
                    <h4 class="title mt-0 mb-10 hidden-xs hidden-sm"><i class="fa fa-bars"></i> 最新文章</h4>
                </div>
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
                                    <?php if($redis->hget('post:'.$page_id,'term')>0) : ?>
                                    <li><i class="glyphicon glyphicon-tag"></i> <a href="<?php echo maoo_url('post','term',array('id'=>$redis->hget('post:'.$page_id,'term'))); ?>"><?php echo maoo_term_title($redis->hget('post:'.$page_id,'term')); ?></a></li>
                                    <?php endif; ?>
                                    <li><i class="glyphicon glyphicon-heart"></i> <?php echo maoo_like_count($page_id); ?></li>
                                    <li><i class="glyphicon glyphicon-eye-open"></i> <?php echo maoo_get_views($page_id); ?></li>
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
		<div class="col-sm-3 col hidden-xs hidden-sm">
			<div class="home-side-box side-latest-post">
				<h4 class="title mt-0 mb-10">
					<i class="fa fa-fire"></i> 热门文章
				</h4>
				<ul class="media-list">
					<?php $db = $redis->zrevrange('rank_list',0,4); ?>
					<?php foreach($db as $page_id) :  if($redis->hget('post:'.$page_id,'title')) : ?>
					<li class="media">
						<div class="media-left">
							<a class="wto" href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>">
								<img class="media-object" src="<?php echo maoo_fmimg($page_id); ?>" alt="<?php echo $redis->hget('post:'.$page_id,'title'); ?>">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('post','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('post:'.$page_id,'title'); ?></a>
							</h4>
							<div class="excerpt">
								<?php echo maoo_cut_str(strip_tags($redis->hget('post:'.$page_id,'content')),21); ?>
							</div>
						</div>
					</li>
                    <?php elseif($redis->hget('post:'.$page_id,'pro')>0) : $pro_id = $redis->hget('post:'.$page_id,'pro'); $cover_images = unserialize($redis->hget('pro:'.$pro_id,'cover_image'));  ?>
                    <li class="media">
						<div class="media-left">
							<a class="wto" href="<?php echo maoo_url('pro','single',array('id'=>$pro_id)); ?>">
								<img class="media-object" src="<?php echo $cover_images[1]; ?>" alt="<?php echo $redis->hget('pro:'.$pro_id,'title'); ?>">
							</a>
						</div>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('pro','single',array('id'=>$page_id)); ?>"><?php echo $redis->hget('pro:'.$pro_id,'title'); ?></a>
							</h4>
							<div class="excerpt">
								<?php echo maoo_cut_str(strip_tags($redis->hget('pro:'.$pro_id,'content')),21); ?>
							</div>
						</div>
					</li>
                    <?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
            <div class="home-side-box side-comment-list">
					<h4 class="title mt-0 mb-10">
						<i class="fa fa-commenting-o"></i> 最新评论
					</h4>
					<?php $comments = $redis->sort('comment_id',array('sort'=>'desc','limit'=>array(0,4))); ?>
					<ul class="media-list">
						<?php foreach($comments as $comment_id) : ?>
                        <?php 
                            $comment_post_id = $redis->hget('comment:'.$comment_id,'post');
                            $comment_type = $redis->hget('comment:'.$comment_id,'type');
                        ?>
						<li class="media">
							<?php $comment_user_id = $redis->hget('comment:'.$comment_id,'author'); ?>
							<div class="media-left">
								<a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$comment_user_id)); ?>">
									<img class="media-object" src="<?php echo maoo_user_avatar($comment_user_id); ?>" alt="<?php echo maoo_user_display_name($comment_user_id); ?>">
								</a>
							</div>
							<div class="media-body">
								<h4 class="media-heading mb-10">
									<a href="<?php echo maoo_url('user','index',array('id'=>$comment_user_id)); ?>"><?php echo maoo_user_display_name($comment_user_id); ?></a> :
								</h4>
								<div class="content mb-10">
									<?php echo $redis->hget('comment:'.$comment_id,'content'); ?>
								</div>
								<div class="time">
									<i class="glyphicon glyphicon-time"></i> <?php echo maoo_format_date($redis->hget('comment:'.$comment_id,'date')); ?>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
            <?php if($redis->get('promod')!=1) : ?>
			<div class="home-side-box side-pro-list">
				<h4 class="title mt-0 mb-10">
					<i class="fa fa-bookmark-o"></i> 会员专购
					<a class="pull-right" href="<?php echo maoo_url('pro'); ?>">更多</a>
				</h4>
				<?php
					$db = $redis->zrevrange('pro_id',0,4);
				?>
				<ul class="media-list">
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
            <?php endif; ?>
            <?php echo maoo_ad('post2'); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>