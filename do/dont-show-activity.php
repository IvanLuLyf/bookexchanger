<?php
require 'functions.php';
if($_GET['type']==1) :
    $_SESSION['dont_show_activity'] = 1;
else :
    $_SESSION['dont_show_activity'] = 2;
endif;
?>
