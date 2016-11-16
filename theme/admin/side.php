			<style>
			.user-center-side .list-group a.<?php echo $_GET['a']; ?> {background-color: #f67769; }
			</style>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=index" class="list-group-item index">
					网站设置
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=cashlist" class="list-group-item cashlist">
					充值监控
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=donttouchmymoney" class="list-group-item donttouchmymoney">
					提现审核
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=postlist" class="list-group-item postlist">
					全部文章
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=deletedposts" class="list-group-item deletedposts">
					已删文章
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=prolist" class="list-group-item prolist">
					全部商品
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=order" class="list-group-item order">
					订单管理
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=deletedpros" class="list-group-item deletedpros">
					已删商品
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=bbslist" class="list-group-item bbslist">
					全部帖子
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=deallist" class="list-group-item deallist">
					全部项目
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=deal" class="list-group-item deal">
					待审项目
				</a>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=term" class="list-group-item term">
					分类管理
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=user" class="list-group-item user">
					用户管理
				</a>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=image" class="list-group-item image">
					图片管理
				</a>
				<?php if($mao10_db_type=='redis') : ?>
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=db" class="list-group-item db">
					数据管理
				</a>
				<?php endif; ?>
			</div>
			<div class="list-group">
				<a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=page" class="list-group-item page">
					全部页面
				</a>
			</div>
