<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<?php 
                $count = $redis->scard('cash_id');
                $page_now = $_GET['page'];
                $page_size = $redis->get('page_size');
                if(empty($page_now) || $page_now<1) :
                    $page_now = 1;
                else :
                    $page_now = $_GET['page'];
                endif;
                $offset = ($page_now-1)*$page_size;
                $db = $redis->sort('cash_id',array('sort'=>'desc','limit'=>array($offset,$page_size)));
            ?>
            <?php if($db) : ?>
            <ul class="list-group">
            <?php foreach($db as $cash_id) : ?>
                <li class="list-group-item">
                    <?php echo maoo_user_display_name($redis->hget('cash:'.$cash_id,'user_id')); ?> 于 <?php echo date('Y-m-d H:i:s',$redis->hget('cash:'.$cash_id,'date')); ?> <?php echo $redis->hget('cash:'.$cash_id,'des'); ?>，<?php if($redis->hget('cash:'.$cash_id,'des')=='充值') : ?><span class="text-success">+<?php echo $redis->hget('cash:'.$cash_id,'total'); ?></span><?php else : ?><span class="text-danger">-<?php echo $redis->hget('cash:'.$cash_id,'total'); ?></span><?php endif; ?>元
                    <?php if($redis->hget('cash:'.$cash_id,'status')==2) : ?><span class="badge ml-10">单号：<?php echo $redis->hget('cash:'.$cash_id,'out_trade_no'); ?></span><?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
			<?php echo maoo_pagenavi($count,$page_now); ?>
            <?php endif; ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
