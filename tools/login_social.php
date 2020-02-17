<?php 
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$game_token = Context::get('token');
$hash = Context::get('hs');
$member_srl = Context::get('srl');

if(empty($game_token)||empty($hash)){
    echo "-1";
    return -1;
}

/*
$mode = -1;

if(!empty($game_token) && !empty($hash) && empty($member_srl)){
	$mode = 1;
}else if(!empty($game_token) && empty($hash) && !empty($member_srl)){
	$mode = 2
}
*/

/*
* mode = 1 : 토큰과 해시만 주어질경우, 첫 로그인일경우
* mode = 2 : 토큰과 멤버 토큰만 주어질경우, srl로 로그인이 가능한지 (false면 mode 1로 재로그인 필요)
*/
$mode = 1;

$oGMdataModel = getModel('gmdata');
if($mode == 1) {$output = $oGMdataModel->GMdataGmsLogin($game_token, $hash);}
if($mode == 2) {$output = $oGMdataModel->GMdataGmsLogin_srl($game_token, $member_srl);}

if(empty($output) || $output<=0){ //데이터 없을경우 intval($output)!=$output
    echo '{"status":-1,"detail":"No User Datas.","user_srl":-1}';
}else{ //데이터 있을경우
    echo '{"status":1,"detail":"Success!","user_srl":'.$output.'}';
}

?>