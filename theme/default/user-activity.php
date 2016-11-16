<?php include('header.php'); ?>
<div class="container">
    <?php echo maoo_ad('home1'); ?>
	<div class="row">
        <div class="col-md-3 col">
            <div class="home-side-box side-author">
                <div class="avatar">
					<img src="<?php echo maoo_user_avatar($user_id); ?>" alt="<?php echo maoo_user_display_name($user_id); ?>">
				</div>
                <div class="clearfix mb-10"></div>
				<div class="name wto mb-10"><?php echo maoo_user_display_name($user_id); ?></div>
                <?php if($redis->hget('user:'.$user_id,'description')) : ?>
                <div class="excerpt mb-10"><?php echo $redis->hget('user:'.$user_id,'description'); ?></div>
                <?php endif; ?>
                <div class="clearfix"></div>
                <ul class="list-inline mb-20">
                    <li><a href="<?php echo maoo_url('user','index',array('id'=>$user_id)); ?>">动态 <?php echo $redis->scard('user_activity_id:'.$user_id); ?></a></li>
                    <li><a href="<?php echo maoo_url('user','post',array('id'=>$user_id)); ?>">文章 <?php echo $redis->scard('user_post_id:'.$user_id); ?></a></li>
                    <li><a href="<?php echo maoo_url('user','comment',array('id'=>$user_id)); ?>">评论 <?php echo $redis->scard('user_comment_id:'.$user_id); ?></a></li>
                </ul>
                <div class="text-center">
                    <?php if($user_id==maoo_user_id()) : ?>
                    <a href="<?php echo maoo_url('user','set'); ?>">
                        <i class="fa fa-cogs"></i> 账号设置
                    </a>
                    <?php else : ?>
                    <?php echo maoo_guanzhu_btn($user_id,'timeline-guanzhu-btn'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="home-side-box side-latest-post">
				<h4 class="title mt-0 mb-10">
					<i class="fa fa-bars"></i> <?php echo $who; ?>的文章
					<a class="pull-right" href="<?php echo maoo_url('user','post',array('id'=>$user_id)); ?>">更多</a>
				</h4>
				<ul class="media-list">
					<?php $myposts = $redis->sort('user_post_id:'.$user_id,array('sort'=>'desc','limit'=>array(0,5))); ?>
					<?php foreach($myposts as $page_id) : ?>
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
					<?php endforeach; ?>
				</ul>
			</div>
        </div>
        <div class="col-md-6 col">
            <div class="panel panel-default panel-timeline">
                <div class="panel-heading">
                    <i class="fa fa-commenting-o"></i> 动态详情
                </div>
                <div class="panel-body">
                    <ul class="media-list mb-0">
                        <li class="media" id="activity-<?php echo $id; ?>">
                            <div class="media-left">
                                <a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                                    <img class="media-object" src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a class="wto" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                                        <?php echo maoo_user_display_name($author); ?>
                                    </a>
                                    <span class="date">
                                        <?php echo maoo_format_date($redis->hget('activity:'.$id,'date')); ?>
                                    </span>
                                    <?php if($author==maoo_user_id() || $redis->hget('user:'.maoo_user_id(),'user_level')==10) : ?>
                                    <a class="del" href="<?php echo $redis->get('site_url'); ?>/do/delete.php?type=activity&id=<?php echo $id; ?>">删除</a>
                                    <?php endif; ?>
                                    <div class="clearfix"></div>
                                </h4>
                                <div class="content">
                                    <?php echo $redis->hget('activity:'.$id,'content'); ?>
                                </div>
                                <?php 
                                    $imgs = $redis->hget('activity:'.$id,'imgs');
                                    if($imgs) :
                                    $imgs = maoo_unserialize($imgs);
                                    $imgscount = count($imgs);
                                ?>
                                <div class="imgs-box imgs-box-<?php if($imgscount>4) : echo 4; else : echo $imgscount; endif; ?>">
                                    <?php foreach($imgs as $img) : ?>
                                    <div class="img">
                                        <a class="img-div" href="javascript:showimg('<?php echo $img; ?>');" style="background-image:url(<?php echo $img; ?>);"></a>
                                    </div>
                                    <?php endforeach; ?>
                                    <div class="clearfix"></div>
                                </div>
                                <?php endif; ?>
                                <?php if($redis->smembers('activity_zan_id:'.$id)) : ?>
                                <div class="zan-list">
                                    <i class="fa fa-heart-o"></i>
                                    <?php foreach($redis->smembers('activity_zan_id:'.$id) as $zan_user_id) : ?>
                                    <a href="<?php echo maoo_url('user','index',array('id'=>$zan_user_id)); ?>">
                                        <?php echo maoo_user_display_name($zan_user_id); ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="comment-list" id="comment-list-<?php echo $id; ?>">
                                    <?php foreach($redis->smembers('activity_comment_id:'.$id) as $comment_id) : $comment_user_id = $redis->hget('comment:'.$comment_id,'author'); ?>
                                    <div class="comment-list-item">
                                        <a href="<?php echo maoo_url('user','index',array('id'=>$comment_user_id)); ?>"><?php echo maoo_user_display_name($comment_user_id); ?></a>：<?php echo $redis->hget('comment:'.$comment_id,'content'); ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <ul class="btn-list list-inline mt-10 mb-0">
                                    <li><?php echo maoo_zan_btn($id); ?></li>
                                    <li><a href="javascript:commentFrom(<?php echo $id; ?>);"><i class="fa fa-commenting-o"></i> 评论</a></li>
                                    <li class="ctu">
                                        <a href="javascript:;"><i class="fa fa-plus-square-o"></i> 打赏</a>
                                        <ul class="list-inline mb-0">
                                            <?php $coins_array = array(1,3,5,10,20); foreach($coins_array as $coins) : ?>
                                            <li><a href="<?php echo $redis->get('site_url'); ?>/do/coins-to-user.php?pid=<?php echo $user_id; ?>&coins=<?php echo $coins; ?>&type=user"><?php echo $coins; ?>积分</a></li>
                                            <?php endforeach; ?>
                                            <div class="clearfix"></div>
                                        </ul>
                                    </li>
                                    <div class="clearfix"></div>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <?php echo maoo_pagenavi($count,$page_now,$page_size); ?>
                    <?php echo maoo_zan_js(); ?>
                    <script>
                        function commentFrom(id) {
                            var activity = $('#activity-'+id);
                            $('.media-body form',activity).remove();
                            $('.media-body',activity).append('<form class="comment-box" id="comment-box-'+id+'"><input type="text" class="form-control" placeholder="写下您的评论内容" /></form>');
                            $('#comment-box-'+id).submit(function(e){
                                e.preventDefault();
                                var commentBox = $('#comment-box-'+id);
                                var text = $('input',commentBox).val();
                                $(commentBox).remove();
                                if(text) {
                                    data = {
                                        text: text,
                                        pid: id
                                    };
                                    $.ajax({
                                        url: '<?php echo $redis->get('site_url'); ?>/do/activity-comment.php',
                                        type: 'POST',
                                        data : data,
                                        dataType: 'json',
                                        timeout: 9000,
                                        error: function() {
                                            alert('提交失败！');
                                            return false;
                                        },
                                        success: function(date) {
                                            if(date.code==3) {
                                                var commentList = $('#comment-list-'+id);
                                                $(commentList).append('<div class="comment-list-item">'+date.user+'：'+date.content+'</div>');
                                            } else {
                                                alert(date.des);
                                            };
                                            return false;
                                        }
                                    });  
                                } else {
                                    return false;
                                };
                            });
                            
                        };
                        $('.ctu').click(function(){
                            $('ul',this).animate({width:'255px'});
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-3 col">
            <?php if($redis->get('promod')!=1) : ?>
			<div class="home-side-box side-pro-list">
				<h4 class="title mt-0 mb-10">
					<i class="fa fa-bookmark-o"></i> 会员专购
					<a class="pull-right" href="<?php echo maoo_url('pro'); ?>">更多</a>
				</h4>
				<?php
					$pros = $redis->zrevrange('pro_id',0,4);
				?>
				<ul class="media-list">
					<?php foreach($pros as $page_id) : $cover_images = unserialize($redis->hget('pro:'.$page_id,'cover_image')); ?>
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
            <div class="home-side-box side-guanzhu-list">
                <h4 class="title mt-0 mb-10">
					<i class="fa fa-star-o"></i> <?php echo $who; ?>关注的人
					<a class="pull-right" href="<?php echo maoo_url('user','guanzhu',array('id'=>$user_id)); ?>">更多</a>
				</h4>
                <?php $guanzhus = $redis->zrevrange('user_guanzhu:'.$user_id,0,4); if($guanzhus) : ?>
                <ul class="media-list">
                    <?php foreach($guanzhus as $page_id) : ?>
					<li class="media">
						<a class="media-left img-div" href="<?php echo maoo_url('user','index',array('id'=>$page_id)); ?>">
							<img class="media-object" src="<?php echo maoo_user_avatar($page_id); ?>">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('user','index',array('id'=>$page_id)); ?>">
                                    <?php echo maoo_user_display_name($page_id); ?>
                                </a>
							</h4>
                            <div class="pt-5"><?php echo maoo_guanzhu_btn($page_id,'timeline-guanzhu-btn'); ?></div>
						</div>
					</li>
					<?php endforeach; ?>
                </ul>
                <?php else : ?>
                <div class="noguanzhu">
                    还没有任何关注的用户
                </div>
                <?php endif; ?>
            </div>
            <div class="home-side-box side-guanzhu-list">
                <h4 class="title mt-0 mb-10">
					<i class="fa fa-smile-o"></i> 最近访客
				</h4>
                <?php 
                    if(maoo_user_id() && maoo_user_id()!=$user_id) :
                        $redis->zadd('user_visitor:'.$user_id,strtotime("now"),maoo_user_id());
                    endif;
                    $visitors = $redis->zrevrange('user_visitor:'.$user_id,0,4);
                    if($visitors) :
                ?>
                <ul class="media-list">
                    <?php foreach($visitors as $page_id) : ?>
					<li class="media">
						<a class="media-left img-div" href="<?php echo maoo_url('user','index',array('id'=>$page_id)); ?>">
							<img class="media-object" src="<?php echo maoo_user_avatar($page_id); ?>">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?php echo maoo_url('user','index',array('id'=>$page_id)); ?>">
                                    <?php echo maoo_user_display_name($page_id); ?>
                                </a>
							</h4>
                            <div class="pt-5"><?php echo maoo_guanzhu_btn($page_id,'timeline-guanzhu-btn'); ?></div>
						</div>
					</li>
					<?php endforeach; ?>
                </ul>
                <?php else : ?>
                <div class="noguanzhu">
                    还没有任何新的访客
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<a id="activityImgShow" href="javascript:hiddenimg();">
    <div class="img-box">
        <div class="img-box-in">
            <img src="" />
        </div>
    </div>
    <div class="bg"></div>
</a>
<script>
    function showimg(img) {
        $('#activityImgShow img').attr('src',img);
        $('#activityImgShow').css('display','block');
    };
    function hiddenimg() {
        $('#activityImgShow img').attr('src','');
        $('#activityImgShow').css('display','none');
    };
</script>
<?php include('footer.php'); ?>
