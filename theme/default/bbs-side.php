<div id="react-chat-box" class="mb-20"></div>
			<script type="text/jsx" src="<?php echo $redis->get('site_url'); ?>/theme/default/react/chat.js"></script>
			<script type="text/jsx">
			React.render(
				<ChatBox url="<?php echo $redis->get('site_url'); ?>/do/chat.php" pollInterval={1000} />,
				document.getElementById('react-chat-box')
			);
			</script>
<?php if($redis->hget('user:'.maoo_user_id(),'user_level')==10) : ?>
<a class="btn btn-block btn-danger mt-20 mb-20" href="<?php echo $redis->get('site_url'); ?>/do/chatflush.php">
    <i class="glyphicon glyphicon-repeat"></i> 让我们重新开始吧
</a>
<?php endif; ?>
<?php echo maoo_ad('bbs1'); ?>
