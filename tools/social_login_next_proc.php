<?php
define('__XE__', true);
require_once("../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$tr_rand = mt_rand(1,99);
setcookie (sha1('confirms'), sha1($tr_rand), 0, '/');

$getlogin = Context::get('login');
if($getlogin=="other") {
    $oMemberController = getController('member');
    $output = $oMemberController->procMemberLogout();
}

$g_rand =  base64_decode($_COOKIE[sha1('rand')]);

if(!empty($_COOKIE[sha1('ck_sns'.$g_rand)]) &&!empty($_COOKIE[sha1('g_token'.$g_rand)]) && !empty($_COOKIE[sha1('s_hash'.$g_rand)]) && is_numeric($g_rand)){
    $g_error = 0;
}else{
    $g_error = 1;
}

if($g_error != 0){
    header("Location: //www.cookiee.net/logins/failed");
}else{
    if($getlogin=="other") {
        header("Location: " . base64_decode($_COOKIE[sha1('ck_sns' . $g_rand)]));
    }else if($getlogin=="this"){
        header("Location: //www.cookiee.net/tools/social_router.redirect");
    }else{
        header("Location: //www.cookiee.net/logins/failed");
    }
}
?>