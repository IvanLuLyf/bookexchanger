<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<table class="table table-striped table-bordered table-hover mb-40">
				<thead>
					<tr>
						<th class="post-title">标题</th>
						<th>等级</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$count = $redis->llen('new_user_id');
						$page_now = $_GET['page'];
						$page_size = 12;
						if(empty($page_now) || $page_now<1) :
							$page_now = 1;
						else :
							$page_now = $_GET['page'];
						endif;
						$offset = ($page_now-1)*$page_size;
						$db = $redis->lrange('new_user_id',$offset,$offset+$page_size-1);
					?>
					<?php foreach($db as $id) : ?>
					<tr id="maoo-post-id-<?php echo $id; ?>">
						<th class="post-title"><a class="wto" href="<?php echo maoo_url('user','index',array('id'=>$id)); ?>"><?php echo $redis->hget('user:'.$id,'user_name'); ?></a></th>
						<th><?php echo $redis->hget('user:'.$id,'user_level'); ?></th>
						<th><a href="<?php echo maoo_url('user','index',array('id'=>$id)); ?>">预览</a> <a class="ml-10" href="<?php echo $redis->get('site_url'); ?>?m=admin&a=user&id=<?php echo $id; ?>">编辑</a> <a class="ml-10" href="<?php echo $redis->get('site_url'); ?>/do/delete.php?id=<?php echo $id; ?>&type=user">删除</a></th>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php echo maoo_pagenavi($count,$page_now,$size=12); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>