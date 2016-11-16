<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-image admin-body">
			<div class="row">
			<?php 
				$count = $redis->zcard('site_img_list');
				$page_now = $_GET['page'];
				$page_size = 12;
				if(empty($page_now) || $page_now<1) :
					$page_now = 1;
				else :
					$page_now = $_GET['page'];
				endif;
				$offset = ($page_now-1)*$page_size;
				$db = $redis->zrevrange('site_img_list',$offset,$offset+$page_size-1);
			?>
			<?php foreach($db as $img) : ?>
				<div class="col-xs-4 col">
					<div class="img-div">
						<img src="<?php echo $img; ?>">
					</div>
                    <?php if(strstr($img,$redis->get('site_url'))) : ?>
                    <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/image-delete.php">
                        <div class="input-group">
                            <input name="img" class="form-control" type="text" value="<?php echo $img; ?>">
                            <a href="#" class="input-group-addon">
                                本地删除
                            </a>
                        </div>
                    </form>
                    <?php elseif( strstr($img,$redis->get('upyun_url')) ) : ?>
                    <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/upyun-delete.php">
                        <div class="input-group">
                            <input name="img" class="form-control" type="text" value="<?php echo $img; ?>">
                            <a href="#" class="input-group-addon">
                                又拍删除
                            </a>
                        </div>
                    </form>
                    <?php elseif( strstr($img,$redis->get('qiniu_url')) ) : ?>
                    <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/qiniu-delete.php">
                        <div class="input-group">
                            <input name="img" class="form-control" type="text" value="<?php echo $img; ?>">
                            <a href="#" class="input-group-addon">
                                七牛删除
                            </a>
                        </div>
                    </form>
                    <?php else : ?>
                    <div class="input-group">
                            <input name="img" class="form-control" type="text" value="<?php echo $img; ?>">
                            <span class="input-group-addon">
                                外链
                            </span>
                        </div>
                    <?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
            <script>
                $('.admin-image .input-group-addon').click(function(){
                    $(this).parent('.input-group').parent('form').submit();
                    return false;
                });
            </script>
			<?php echo maoo_pagenavi($count,$page_now,$page_size); ?>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>