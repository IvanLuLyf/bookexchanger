<?php include('header.php'); ?>
<div class="container deal-page">
	<h1 class="title text-center mb-40">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3 col">
                全部项目
            </div>
            <div class="col-xs-3 col">
                <a class="pull-right btn btn-warning" href="<?php echo maoo_url('deal','publish'); ?>">
                    <i class="glyphicon glyphicon-cloud-upload"></i> 发起项目
                </a>
            </div>
        </div>
    </h1>
    <div class="row">
		<?php foreach($db as $page_id) : $author = $redis->hget('deal:'.$page_id,'author'); $status = maoo_deal_status($page_id); ?>
        <div class="col-sm-4 col">
            <a class="thumbnail deal-index-list pr" href="<?php echo maoo_url('deal','single',array('id'=>$page_id)); ?>">
                <?php if($status==3) : echo '<span class="status status-3">已结束</span>'; elseif($status==2) : echo '<span class="status status-2">已达成</span>'; else : echo '<span class="status status-1">进行中</span>'; endif; ?>
                <div class="img-div">
                    <img src="<?php echo maoo_fmimg($page_id,'deal'); ?>" alt="<?php echo $redis->hget('deal:'.$page_id,'title'); ?>">
                </div>
                <div class="progress mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo maoo_deal_percent($id); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo maoo_deal_percent($page_id); ?>%;"></div>
                </div>
                <div class="caption">
                    <h2 class="title mt-0 text-center">
                        <?php echo $redis->hget('deal:'.$page_id,'title'); ?>
                    </h2>
                    <div class="text-center mb-20">
                        <span class="author"><?php echo maoo_user_display_name($author); ?></span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <span>筹款目标</span>
                            <strong><?php echo $redis->hget('deal:'.$page_id,'goal'); ?>元</strong>
                        </li>
                        <li>
                            <span>已筹金额</span>
                            <strong><?php echo $redis->hget('deal:'.$page_id,'total'); ?>元</strong>
                        </li>
                        <li>
                            <span>支持次数</span>
                            <strong><?php echo maoo_deal_reward_count($page_id); ?>次</strong>
                        </li>
                    </ul>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include('footer.php'); ?>
