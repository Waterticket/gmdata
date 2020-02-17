<?php
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$user_id = Context::get('uid'); 
$password = Context::get('ups'); 
$keep_login = "Y";

//if(Context::getRequestMethod() != 'POST'){
if( $_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo "-2";
    return -2;
}
if(!$user_id || !$password){
	echo "-1";
	return -1;
}

$oMemberController = getController('member'); 

$usersrl = $oMemberController->procMemberLoginuserSrl($user_id, $password, $keep_login);
if(empty($usersrl)||$usersrl<=0||$usersrl->error<0){
    echo "-3";
    return -3;
}else {
    echo $usersrl->data->member_srl;
}

?>