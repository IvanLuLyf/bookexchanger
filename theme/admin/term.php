<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<h4>新建分类</h4>
			<hr>
			<form class="mb-40" method="post" action="<?php echo $redis->get('site_url'); ?>/do/term.php">
				<div class="form-group">
					<label>
						标题
					</label>
					<input type="text" name="title" class="form-control" placeholder="">
				</div>
				<div class="form-group">
					<label>
						类型
					</label>
					<select name="type" class="form-control">
                        <?php $types = array('post','bbs','pro','deal'); ?>
						<?php foreach($types as $type) : ?>
						<option value="<?php echo $type; ?>">
							<?php echo $type; ?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<button type="submit" class="btn btn-default">
					提交
				</button>
			</form>
			<hr class="mb-40">
			<?php foreach($types as $type) : ?>
			<h4 class="mb-20"><?php echo $type; ?>分类</h4>
			<table class="table table-striped table-bordered table-hover mb-40">
				<thead>
					<tr>
						<th>ID</th>
						<th class="post-title">名称</th>
						<th>内容</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($redis->zrange('term:'.$type,0,-1) as $title) :
					$id = $redis->zscore('term:'.$type,$title);
					if($redis->hget('term:'.$type.':'.$id,'title')=='') :
						$redis->hset('term:'.$type.':'.$id,'title',$title);
					endif;
					?>
					<tr id="maoo-post-id-<?php echo $id; ?>">
						<th><?php echo $id; ?></th>
						<th class="post-title">
							<a href="<?php echo maoo_url($type,'term',array('id'=>$id)); ?>"><?php echo $title; ?></a>
							<?php if($redis->hget('term:'.$type.':'.$id,'parent')>0) : ?>
							(父分类:<?php echo $redis->hget('term:'.$type.':'.$redis->hget('term:'.$type.':'.$id,'parent'),'title').'#'.$redis->hget('term:'.$type.':'.$id,'parent'); ?>)
							<?php endif; ?>
						</th>
						<th>
							<?php if($type=='pro') : ?>
							<?php echo $redis->zcard('term_'.$type.'_id:'.$id); ?>
							<?php else : ?>
							<?php echo $redis->scard('term_'.$type.'_id:'.$id); ?>
							<?php endif; ?>
						</th>
						<th><a class="ml-10" href="<?php echo maoo_url('admin','termedit',array('id'=>$id,'type'=>$type)); ?>">编辑</a> <a class="ml-10" href="<?php echo $redis->get('site_url'); ?>/do/term_delete.php?id=<?php echo $id; ?>&type=<?php echo $type; ?>">删除</a></th>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>
