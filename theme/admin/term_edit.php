<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/term_edit.php">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="hidden" name="type" value="<?php echo $type; ?>">
				<div class="form-group">
					<label>
						标题
					</label>
					<input type="text" name="page[title]" class="form-control" value="<?php echo $redis->hget('term:'.$type.':'.$id,'title'); ?>">
				</div>
				<div class="form-group">
					<label>
						父分类
					</label>
					<select class="form-control" name="page[parent]">
						<option>请选择...</option>
						<?php 
						foreach($redis->zrange('term:'.$type,0,-1) as $title) : 
						$tid = $redis->zscore('term:'.$type,$title);
                        if($tid!=$id) :
						?>
						<option value="<?php echo $tid; ?>" <?php if($redis->hget('term:'.$type.':'.$id,'parent')==$tid) echo 'selected'; ?>><?php echo $title; ?></option>
						<?php endif; endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>
						描述
					</label>
					<textarea name="page[content]" class="form-control" rows="3"><?php echo $redis->hget('term:'.$type.':'.$id,'content'); ?></textarea>
				</div>
				<button type="submit" class="btn btn-block btn-default">
					保存
				</button>
			</form>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>