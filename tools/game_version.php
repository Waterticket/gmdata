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
		echo '{"version":'.$out->game_version.',"minversion":'.$out->game_minversion.',"show_version":"'.$out->game_showversion.'","game_url":"'.$out->game_update_link.'","game_name":"'.$out->game_update_name.'"}';
	}
}
?>
