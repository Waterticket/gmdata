<?php
define('__XE__', true);
require_once("../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$g_rand =  base64_decode($_COOKIE[sha1('rand')]);
$logged_info = Context::get('logged_info');

if(!empty($_COOKIE[sha1('g_token'.$g_rand)]) && !empty($_COOKIE[sha1('s_hash'.$g_rand)]) && is_numeric($g_rand)){
    $g_token = base64_decode($_COOKIE[sha1('g_token'.$g_rand)]);
    $s_hash = base64_decode($_COOKIE[sha1('s_hash'.$g_rand)]);
    $member_srl = $logged_info->member_srl;
    $g_error = 0;
}else{
    $g_error = 1;
}

//쿠키 지우기
setcookie (sha1('rand'), '', 1, '/');
setcookie (sha1('g_token'.$g_rand), '', 1, '/');
setcookie (sha1('s_hash'.$g_rand), '', 1, '/');
setcookie (sha1('ck_sns'.$g_rand), '', 1, '/');

if(empty($g_token) || empty($s_hash) || empty($member_srl)){
    $g_error = 1;
}

//echo '<script>alert("token: '.$g_token.', hash: '.$s_hash.', srl: '.$member_srl.'")</script>';

if($g_error != 0){
    header("Location: //www.cookiee.net/logins/failed");
}else{
    $oGMdataModel = getModel('gmdata');
    $output = $oGMdataModel->GMdataLoginSuccess($g_token, $s_hash, $member_srl);

    if($output){
        header("Location: //www.cookiee.net/logins/success");
    }else{
        header("Location: //www.cookiee.net/logins/failed");
    }
}
?>