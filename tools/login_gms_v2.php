<?php
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$user_id = Context::get('uid'); 
$password = Context::get('ups'); 
$keep_login = "Y";

if(Context::getRequestMethod() != 'POST'){
    echo '{"status":-2, "detail":"Request Method is not POST!", "user_srl":-1}';
    return -2;
}
if(!$user_id || !$password){
	echo '{"status":-1, "detail":"UserID or UserPW was not input", "user_srl":-1}';
	return -1;
}

$oMemberController = getController('member'); 

$usersrl = $oMemberController->procMemberLoginuserSrl($user_id, $password, $keep_login);
if(empty($usersrl)||$usersrl<=0){
    echo '{"status":-3, "detail":"UserID or UserPW is invaild", "user_srl":-1}';
    return -3;
}else{
    if($usersrl->error<0) {
        echo '{"status":'.(intval($usersrl->error)-3).', "detail":"UserID or UserPW is invaild", "user_srl":-1}';
    }else{
        echo '{"status":1, "detail":"Success!", "user_srl":' . $usersrl->data->member_srl . '}';
    }
}

?>