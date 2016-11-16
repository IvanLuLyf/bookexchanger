<footer>
	<div class="container">
		Powered By <a href="http://www.mao10.com/">Mao10CMS</a>
	</div>
</footer>
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="get" action="<?php echo $redis->get('site_url'); ?>">
				    <div class="form-group">
				        <input type="text" class="form-control" name="s" placeholder="搜索文章、话题、用户">
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
<!--[if lte IE 8]>
<div class="browser-msg text-center">
	<p class="txt">
		为了获得更好的浏览体验，建议使用以下浏览器：
	</p>
	<ul class="browsers">
		<li>
			<a href="http://www.google.cn/intl/zh-cn/chrome/browser/desktop/index.html"
			target="_blank">
				<img class="icon" src="<?php echo $redis->get('site_url'); ?>/public/img/chrome.png" width="50" height="50">
				<span class="name">
					Chrome
				</span>
			</a>
		</li>
		<li>
			<a href="http://www.firefox.com.cn" target="_blank">
				<img class="icon" src="<?php echo $redis->get('site_url'); ?>/public/img/firefox.png" width="50" height="50">
				<span class="name">
					Firefox
				</span>
			</a>
		</li>
		<li>
			<a href="http://www.apple.com/cn/safari/" target="_blank">
				<img class="icon" src="<?php echo $redis->get('site_url'); ?>/public/img/safari.png" width="50" height="50">
				<span class="name">
					Safari
				</span>
			</a>
		</li>
		<li>
			<a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank">
				<img class="icon" src="<?php echo $redis->get('site_url'); ?>/public/img/ie.png" width="50" height="50">
				<span class="name">
					IE9及更高版
				</span>
			</a>
		</li>
	</ul>
</div>
<![endif]-->
<script src="<?php echo $redis->get('site_url'); ?>/public/js/bootstrap.min.js"></script>
<script src="<?php echo $redis->get('site_url'); ?>/public/js/cat.js"></script>
<?php if($_GET['done']) : ?>
<div class="done-message animated">
	<?php echo $_GET['done']; ?>
	<i class="glyphicon glyphicon-remove"></i>
</div>
<script>
	 $('.done-message i').click(function(){
		 $('.done-message').animate({top:"-61px"});
	 });
	 setTimeout("$('.done-message').animate({top:'-61px'})",3000);
</script>
<?php endif; ?>
</body>
</html>
