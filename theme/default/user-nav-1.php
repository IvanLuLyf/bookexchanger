<ul class="nav nav-pills nav-justified nav-userhome mb-40">
	<li role="presentation" <?php if($_GET['a']=='post') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','post',array('id'=>$_GET['id'])); ?>">
			文章
		</a>
	</li>
	<li role="presentation" <?php if($_GET['a']=='comment') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','comment',array('id'=>$_GET['id'])); ?>">
			评论
		</a>
	</li>
	<?php if($user_id==maoo_user_id()) : ?>
	<li role="presentation" <?php if($_GET['a']=='like') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','like',array('id'=>$_GET['id'])); ?>">
			喜欢
		</a>
	</li>
	<li role="presentation" <?php if($_GET['a']=='guanzhu') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','guanzhu',array('id'=>$_GET['id'])); ?>">
			关注
		</a>
	</li>
    <?php endif; ?>
</ul>