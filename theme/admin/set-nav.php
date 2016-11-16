<ul class="nav nav-tabs mb-30">
    <li role="presentation" class="<?php if($_GET['a']=='index') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=index" class="index">
            综合
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='slider') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=slider" class="slider">
            幻灯
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='set') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=seo" class="seo">
            SEO
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='nav') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=nav" class="nav">
            导航
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='sign') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=sign" class="sign">
            登录
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='coinsset') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=coinsset" class="coinsset">
            积分
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='pay') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=pay" class="pay">
            支付
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='link') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=link" class="link">
            友链
        </a>
    </li>
    <li role="presentation" class="<?php if($_GET['a']=='ad') echo 'active'; ?>">
        <a href="<?php echo $redis->get('site_url'); ?>?m=admin&a=ad" class="ad">
            广告
        </a>
    </li>
</ul>