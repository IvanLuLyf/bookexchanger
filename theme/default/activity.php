<?php
$activityShow = $redis->sort('activity_id',array('sort'=>'desc','limit'=>array(0,20)));
foreach($activityShow as $key=>$val) :
    if($redis->hget('activity:'.$val,'private')==1) :
        unset($activityShow[$key]);
    endif;
endforeach;
$activityShow = array_slice($activityShow,0,5);
if($activityShow) : ?>
<div class="activityShowList <?php if($_SESSION['dont_show_activity']!=1) : ?>active<?php endif; ?> hidden-xs hidden-sm">
    <div class="toggle">
        <i class="fa fa-angle-down"></i>
        <i class="fa fa-angle-up"></i>
    </div>
    <h4 class="title"><i class="fa fa-feed"></i> 最新动态</h4>
<div class="media-list mb-0">
                        <?php foreach($activityShow as $page_id) : $author = $redis->hget('activity:'.$page_id,'author'); ?>
                        <div class="media">
                            <div class="media-left">
                                <a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                                    <img class="media-object" src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    <a class="wto" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                                        <?php echo maoo_user_display_name($author); ?>
                                    </a>
                                    <span class="date">
                                        <?php echo maoo_format_date($redis->hget('activity:'.$page_id,'date')); ?>
                                    </span>
                                    <div class="clearfix"></div>
                                </h4>
                                <div class="content wto">
                                    <?php echo $redis->hget('activity:'.$page_id,'content'); ?> <?php 
                                    $imgs = $redis->hget('activity:'.$page_id,'imgs'); if($imgs) : ?><span>[有图]</span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
</div>
<script>
    $('.activityShowList .fa-angle-down').click(function(){
        $('.activityShowList').animate({bottom:"-306px"});
        $('.activityShowList').removeClass('active');
        $.ajax({
            url: '<?php echo $redis->get('site_url'); ?>/do/dont-show-activity.php?type=1',
            type: 'GET',
            dataType: 'json',
            timeout: 9000,
            error: function() {
            },
            success: function(date) {
            }
        }); 
    });
    $('.activityShowList .fa-angle-up').click(function(){
        $('.activityShowList').animate({bottom:"-1px"});
        $('.activityShowList').addClass('active');
        $.ajax({
            url: '<?php echo $redis->get('site_url'); ?>/do/dont-show-activity.php?type=2',
            type: 'GET',
            dataType: 'json',
            timeout: 9000,
            error: function() {
            },
            success: function(date) {
            }
        }); 
    });
</script>
<?php endif; ?>