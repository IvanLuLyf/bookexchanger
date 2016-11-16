<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<?php $db = $redis->keys('*'); ?>
			<ul class="list-group">
			<?php 
				$count = count($db);
				$page_now = $_GET['page'];
				$page_size = 12;
				if(empty($page_now) || $page_now<1) :
					$page_now = 1;
				else :
					$page_now = $_GET['page'];
				endif;
				$offset = ($page_now-1)*$page_size;
				$db = array_slice($db,$offset,$page_size);
			?>
			<?php foreach($db as $date) : ?>
				<li class="list-group-item">
					<?php echo $date; ?> 
					[ <?php 
						if($redis->type($date)==1) :
							echo 'string';
						elseif($redis->type($date)==2) :
							echo 'set';
						elseif($redis->type($date)==3) :
							echo 'list';
						elseif($redis->type($date)==4) :
							echo 'zset';
						elseif($redis->type($date)==5) :
							echo 'hash';
						else :
							echo 'none';
						endif;
					?> ]
				</li>
			<?php endforeach; ?>
			</ul>
			<?php echo maoo_pagenavi($count,$page_now,12); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>