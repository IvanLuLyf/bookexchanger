<footer>
	<div class="container">
		©2014-2016 <a href="http://www.mao10.com/">Mao10CMS V6</a> 内容型网络商城建站系统
	</div>
</footer>
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="get" action="<?php echo $redis->get('site_url'); ?>">
				    <div class="form-group">
				        <input type="text" class="form-control" name="s" placeholder="搜索文章、话题、用户、商品">
				    </div>
				</form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#searchModal').on('show.bs.modal', function (e) {
        $('#header').removeClass('navshow');
        $('#nav-show-bg').css('display','none');
    });
</script>
<?php include('cart.php'); ?>
<?php include('activity.php'); ?>
<div class="mobile-foot-nav visible-xs-block visible-sm-block">
    <a class="mobile-foot-nav-item <?php if($_GET['m']=='' && $_GET['a']=='') echo 'active'; ?>" href="<?php echo $redis->get('site_url'); ?>">
        <i class="fa fa-home"></i>
    </a>
    <a class="mobile-foot-nav-item <?php if($_GET['m']=='post' && $_GET['a']=='topic') echo 'active'; ?>" href="<?php echo maoo_url('post','topic'); ?>">
        <i class="fa fa-archive"></i>
    </a>
    <a class="mobile-foot-nav-item <?php if($_GET['m']=='user' && $_GET['a']=='like') echo 'active'; ?>" href="<?php echo maoo_url('user','like'); ?>">
        <i class="fa fa-star"></i>
    </a>
    <a class="mobile-foot-nav-item <?php if($_GET['m']=='user' && $_GET['a']=='set') echo 'active'; ?>" href="<?php echo maoo_url('user','set'); ?>">
        <i class="fa fa-gears"></i>
    </a>
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
<?php echo maoo_guanzhu_js(); ?>
<?php echo $redis->get('statistical_code'); ?>
</body>
</html>
