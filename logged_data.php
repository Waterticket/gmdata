<?php
define('__XE__', true);
require_once("common/autoload.php");
$oContext = &Context::getInstance();
$oContext->init();
$logged_info = Context::get('logged_info');

//echo json_encode($logged_info);
print_r($_COOKIE);