<?php
require __DIR__."/do/functions.php";
if(maoo_is_install()) :
    if(strlen($_GET['m'])<20) :
        $m = maoo_magic_in($_GET['m']);
    endif;
    if(strlen($_GET['a'])<20) :
        $a = maoo_magic_in($_GET['a']);
    endif;
    $mod = strtolower(isset($m) ? $m : "index");
    $act = strtolower(isset($a) ? $a : "index");
    if($redis->get('promod')==1 && $mod=='pro') :
        $mod = 'index';
        $act = 'index';
    elseif($redis->get('bbsmod')==1 && $mod=='bbs') :
        $mod = 'index';
        $act = 'index';
    elseif($redis->get('dealmod')==1 && $mod=='deal') :
        $mod = 'index';
        $act = 'index';
    endif;
    require __DIR__."/mod/{$mod}.php";
    $app = New Maoo();
    $app->$act();
else :
    require __DIR__."/install.php";
endif;
$redis->close();