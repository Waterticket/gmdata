<?php
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$oGMdataModel = getModel('gmdata');

$args = new stdClass;
$args->game_token = Context::get('token');
$args->usr_srl = Context::get('srl');


if(!$args->game_token||!$args->usr_srl){
	echo "-1";
}else{
	$out=$oGMdataModel->getgameDatas($args);
	if(!$out->datas) echo $out;
	echo $out->datas;
}
?>