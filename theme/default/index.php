<?php include('header.php'); ?>
<div class="container">
	<?php if(maoo_user_id() && maoo_dayu() && $redis->hget('user:'.maoo_user_id(),'phone')=='') : ?>
	<div class="bindPhoneTips mb-20 text-center">
		您还没有绑定手机，请立刻前往用户中心<a href="<?php echo maoo_url('user','set'); ?>">绑定手机</a>，即可使用手机号码快捷登录，并获得10积分奖励。
	</div>
	<?php endif; ?>
	<?php if($redis->get('slider_img:1')) : ?>
	<div id="carousel-home" class="carousel slide" data-ride="carousel">
		<?php if($redis->get('slider_img:2')) : ?>
		<ol class="carousel-indicators">
			<li data-target="#carousel-home" data-slide-to="0" class="active"></li>
			<li data-target="#carousel-home" data-slide-to="1"></li>
			<?php if($redis->get('slider_img:3')) : ?>
			<li data-target="#carousel-home" data-slide-to="2"></li>
			<?php endif; ?>
		</ol>
		<?php endif; ?>
		<div class="carousel-inner" role="listbox">
			<a class="item active" href="<?php echo $redis->get('slider_link:1'); ?>" style="background-image: url(<?php echo $redis->get('slider_img:1'); ?>);"></a>
			<?php if($redis->get('slider_img:2')) : ?>
			<a class="item" href="<?php echo $redis->get('slider_link:2'); ?>" style="background-image: url(<?php echo $redis->get('slider_img:2'); ?>);"></a>
			<?php endif; ?>
			<?php if($redis->get('slider_img:3')) : ?>
			<a class="item" href="<?php echo $redis->get('slider_link:3'); ?>" style="background-image: url(<?php echo $redis->get('slider_img:3'); ?>);"></a>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
    <?php echo maoo_ad('home1'); ?>
	<div class="row">
		<div class="col-sm-9 col">
            <div class="post-list">
                <div class="home-side-box mb-0">
                    <h4 class="title mt-0 mb-10 hidden-xs hidden-sm"><i class="fa fa-fire"></i> 热门文章</h4>
                </div>
                <?php foreach($db as $page_id) : $numad++; if($redis->hget('post:'.$page_id,'title')) : ?>
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
                <?php if($numad==2) : ?><?php echo maoo_ad('home2'); ?><?php endif; ?>
                <?php endforeach; ?>
                <?php echo maoo_pagenavi($count,$page_now); ?>
            </div>
		</div>
		<div class="col-sm-3 col hidden-xs hidden-sm">
            <?php echo maoo_ad('home3'); ?>
			<?php include('side.php'); ?>
		</div>
	</div>
</div>
<?php echo maoo_link(); ?>
<?php include('footer.php'); ?>
