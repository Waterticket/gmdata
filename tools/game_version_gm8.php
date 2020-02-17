<?php 
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$oGMdataModel = getModel('gmdata');
$token = Context::get('token');
if(!$token){
	echo "-1";
}else{
	$out=$oGMdataModel->getgameVersion($token);
	if(!$out->game_version) {
		echo $out;
	}else{
		echo $out->game_update_link.'@'.$out->game_version;
	}
}
?>
