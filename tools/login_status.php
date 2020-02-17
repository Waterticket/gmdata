<?php 
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$member_srl = Context::get('mr');
$game_token = Context::get('token'); //아직 사용안함

$oMemberModel = &getModel('member');
$logged_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);
//$logged_info = Context::get('logged_info'); 

if(!$logged_info->member_srl){
	echo '-1';
	return -1;
}
if($logged_info->profile_image->src){
if(strpos($logged_info->profile_image->src, "login_status.php") !== false) { 
	$strTok = explode('login_status.php' , $logged_info->profile_image->src);
	$profile = $strTok[0].$strTok[1];
}else{
	$profile = $logged_info->profile_image->src;
}
}else{
	$profile = "https://www.cookiee.net/files/member_extra_info/profile_image/noprofile.png";
}

if($logged_info) { 
	echo '{"member_srl":'.$logged_info->member_srl.',"user_id":"'.$logged_info->user_id.'","nick_name":"'.$logged_info->nick_name.'","profile_image":"'.$profile.'"}';
} else { 
    echo "-1"; 
} 
?>
