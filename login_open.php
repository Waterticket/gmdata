<?php
define('__XE__', true); 
require_once("./config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$oGMdataModel = getModel('gmdata'); 
if(!empty($_COOKIE[sha1('rand')])){
    $g_rand = $_COOKIE[sha1('rand')];
}
if(!empty($_COOKIE[sha1('g_token'.$g_rand)]) || !empty($_COOKIE[sha1('s_hash'.$g_rand)]) || !empty($_COOKIE[sha1('member_srl'.$g_rand)]) || !empty($_COOKIE[sha1('rand')])){ //쿠키가 존재하면 초기화
	setcookie (sha1('rand'), '', 1, '/');
	setcookie (sha1('g_token'.$g_rand), '', 1, '/');
	setcookie (sha1('s_hash'.$g_rand), '', 1, '/');
	setcookie (sha1('member_srl'.$g_rand), '', 1, '/');
}

$game_token = Context::get('token'); 
$hash = Context::get('hash'); 
$rand = mt_rand(1, 9999);

if(!$game_token || !$hash || !$rand){
	header("Location: //www.cookiee.net/logins/failed");
}else{
	setcookie (sha1('rand'), base64_encode($rand), 0, '/');
	setcookie (sha1('s_hash'.$rand), base64_encode($hash), 0, '/');
	setcookie (sha1('g_token'.$rand), base64_encode($game_token), 0, '/');
	header("Location: //www.cookiee.net/login");
}

?>