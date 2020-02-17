<?php
define('__XE__', true); 
require_once("../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$g_rand =  base64_decode($_COOKIE[sha1('rand')]);

if($_COOKIE[sha1('g_token'.$g_rand)] && $_COOKIE[sha1('s_hash'.$g_rand)] && $_COOKIE[sha1('member_srl'.$g_rand)] && is_numeric($g_rand)){
	$g_token = base64_decode($_COOKIE[sha1('g_token'.$g_rand)]);
	$s_hash = base64_decode($_COOKIE[sha1('s_hash'.$g_rand)]);
	$member_srl = (base64_decode($_COOKIE[sha1('member_srl'.$g_rand)]))/$g_rand;
	$g_error = 0;
}else{
	$g_error = 1;
}

//쿠키 지우기
setcookie (sha1('rand'), '', 1, '/');
setcookie (sha1('g_token'.$g_rand), '', 1, '/');
setcookie (sha1('s_hash'.$g_rand), '', 1, '/');
setcookie (sha1('member_srl'.$g_rand), '', 1, '/');

if(!$g_token || !$s_hash || !$member_srl){
	$g_error = 1;
}

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