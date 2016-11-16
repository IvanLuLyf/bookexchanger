<?php
require 'functions.php';
$code = str_replace('REF','',$_POST['code']);
if($code>0 && $redis->hget('user:'.$code,'user_name')!='' && $code!=maoo_user_id() && strstr($_POST['code'],'REF')) :
    echo '<span class="text-success">推荐码可用</span>';
else :
    echo '<span class="text-danger">推荐码无效</span>';
endif;
?>
