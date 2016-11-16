<?php include('header.php'); ?>
<div class="container page-single">
	<div class="row">
		<div class="col-sm-3 col col-page-side">
			<div class="page-side">
				<?php include_once('page-side.php'); ?>
			</div>
		</div>
		<div class="col-sm-9 col col-page-body">
			<h1 class="title mt-0"><?php echo $redis->hget('post:page:'.$id,'title'); ?></h1>
			<?php echo maoo_magic_out($redis->hget('post:page:'.$id,'content')); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>