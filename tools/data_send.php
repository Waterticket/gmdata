<?php
date_default_timezone_set("Asia/Seoul");
define('__XE__', true); 
require_once("./../config/config.inc.php"); 
$oContext = &Context::getInstance(); 
$oContext->init(); 

$oGMdataModel = getModel('gmdata');

$args = new stdClass;
$args->game_token = Context::get('token');
$args->usr_srl = Context::get('srl');
$args->datas = Context::get('ds');
$args->hash = Context::get('hs');
$args->log_time = date("Y-m-d H:i:s",time());

if(!$args->game_token||!$args->usr_srl||!$args->datas){
	echo "-1";
}else{
	$out=$oGMdataModel->sendDatas($args);
	$args->log_stat = $out;
	$oGMdataModel->insertLogs($args);
	echo $out;
}
?>