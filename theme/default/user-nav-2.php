<ul class="nav nav-pills nav-justified nav-userhome mb-40">
	<li role="presentation" <?php if($_GET['a']=='deal') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','deal',array('id'=>$_GET['id'])); ?>">
			发布的项目
		</a>
	</li>
	<li role="presentation" <?php if($_GET['a']=='reward') echo 'class="active"'; ?>>
		<a href="<?php echo maoo_url('user','reward',array('id'=>$_GET['id'])); ?>">
			支持的项目
		</a>
	</li>
</ul>