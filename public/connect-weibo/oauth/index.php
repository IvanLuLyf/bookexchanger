<?php
session_start();
require_once __DIR__."/../../../do/functions.php";
include __DIR__.'/../config.php';
include __DIR__.'/../saetv2.ex.class.php';

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );

Header("Location:$code_url");

?>
