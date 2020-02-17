<?php
define('__XE__', true);
require_once("./config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$oSocialxeModel = getModel('socialxe');
if(!empty($_COOKIE[sha1('rand')])){
    $g_rand = $_COOKIE[sha1('rand')];
}
if(!empty($_COOKIE[sha1('g_token'.$g_rand)]) || !empty($_COOKIE[sha1('s_hash'.$g_rand)]) || !empty($_COOKIE[sha1('member_srl'.$g_rand)]) || !empty($_COOKIE[sha1('rand')])){ //쿠키가 존재하면 초기화
    setcookie (sha1('rand'), '', 1, '/');
    setcookie (sha1('g_token'.$g_rand), '', 1, '/');
    setcookie (sha1('s_hash'.$g_rand), '', 1, '/');
    setcookie (sha1('member_srl'.$g_rand), '', 1, '/');
    setcookie (sha1('ck_sns'.$g_rand), '', 1, '/');
    setcookie (sha1('ck_sns_nm'.$g_rand), '', 1, '/');
}

$game_token = urldecode(Context::get('token'));
$hash = urldecode(Context::get('hash'));
$loginModel = urldecode(Context::get('sns'));
$rand = mt_rand(1, 99999);
$trashgap = sha1(mt_rand(1, 999));
$sns_redirect_url = "";
$sns_correct_name = "";
//twitter, google, naver, kakao, facebook
switch($loginModel) {
    case 'twitter':
        $sns_correct_name = "twitter";
        $sns_redirect_url = "//www.cookiee.net/index.php?act=dispSocialxeConnectSns&service=twitter&type=login&rdset=".$trashgap;
        break;

    case 'google':
        $sns_correct_name = "google";
        $sns_redirect_url = "//www.cookiee.net/index.php?act=dispSocialxeConnectSns&service=google&type=login&rdset=".$trashgap;
        break;

    case 'naver':
        $sns_correct_name = "naver";
        $sns_redirect_url = "//www.cookiee.net/index.php?act=dispSocialxeConnectSns&service=naver&type=login&rdset=".$trashgap;
        break;

    case 'kakao':
        $sns_correct_name = "kakao";
        $sns_redirect_url = "//www.cookiee.net/index.php?act=dispSocialxeConnectSns&service=kakao&type=login&rdset=".$trashgap;
        break;

    case 'facebook':
        $sns_correct_name = "facebook";
        $sns_redirect_url = "//www.cookiee.net/index.php?act=dispSocialxeConnectSns&service=facebook&type=login&rdset=".$trashgap;
        break;

    default:
        $sns_correct_name = "none";
        $sns_redirect_url = "//www.cookiee.net/login";
        break;
}

if(!$game_token || !$hash || !$rand){
    header("Location: //www.cookiee.net/logins/failed");
}else{
    setcookie (sha1('rand'), base64_encode($rand), 0, '/');
    setcookie (sha1('s_hash'.$rand), base64_encode($hash), 0, '/');
    setcookie (sha1('g_token'.$rand), base64_encode($game_token), 0, '/');
    setcookie (sha1('ck_sns'.$rand), base64_encode($sns_redirect_url), 0, '/');
    setcookie (sha1('ck_sns_nm'.$rand), base64_encode($sns_correct_name), 0, '/');

    $logged_info = Context::get('logged_info');
    if(!empty($logged_info->member_srl)){
        header("Location: //www.cookiee.net/logins/confirm");
    }else {
        header("Location: " . $sns_redirect_url);
    }
}

?>