<?php include('header.php'); ?>
<?php include_once('user-head.php'); ?>
<div class="container user-center">
	<div class="user-reward">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col">
                <?php include_once('user-nav-2.php'); ?>
                <div class="panel panel-default">
                    <ul class="list-group deal-reward-list">
                        <?php foreach($db as $page_id) : $deal_id = $redis->hget('deal:reward:'.$page_id,'deal'); $rewards = unserialize($redis->hget('deal:'.$deal_id,'reward')); ?>
                        <li class="list-group-item">
                            <div class="media mb-0">
                                <div class="media-left media-middle">
                                    <div class="img-div">
                                        <img class="media-object" src="<?php echo maoo_user_avatar($user_id); ?>" alt="<?php echo maoo_user_display_name($user_id); ?>">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        你于 <?php echo date('Y/m/d',$redis->hget('deal:reward:'.$page_id,'date')); ?> 支持项目 <a href="<?php echo maoo_url('deal','single',array('id'=>$deal_id)); ?>"><?php echo $redis->hget('deal:'.$deal_id,'title'); ?></a> <?php echo $redis->hget('deal:reward:'.$page_id,'price'); ?> 元
                                    </h4>
                                    <div class="mb-0"><?php echo $redis->hget('deal:reward:'.$page_id,'somewords'); ?></div>
                                </div>
                            </div>
                            <div class="well mb-0 mt-20">
                                <p>回报内容：<?php echo $rewards[$redis->hget('deal:reward:'.$page_id,'rewardkey')]['content']; ?></p>
                                <p>收货地址：<?php echo $redis->hget('deal:reward:'.$page_id,'address'); ?></p>
                                订单号：<?php echo $redis->hget('deal:reward:'.$page_id,'out_trade_no'); ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
	</div>
</div>
<?php include('footer.php'); ?>
