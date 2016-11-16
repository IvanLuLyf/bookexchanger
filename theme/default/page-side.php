<style>
	.page-single > .row > .col.col-page-side a.page-<?php echo $id; ?> {background-color: #fff; border-left-color: #db4031; color: #2f2f2f; }
</style>
<div class="list-group">
	<?php 
		$db = $redis->zrevrange('post_id:page:rank',0,20);
	?>
					<?php foreach($db as $page_id) : ?>
	<a href="<?php echo maoo_url('index','page',array('id'=>$page_id)); ?>" class="list-group-item page-<?php echo $page_id; ?>">
		<?php echo $redis->hget('post:page:'.$page_id,'title'); ?>
	</a>
	<?php endforeach; ?>
</div>