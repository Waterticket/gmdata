<?php
date_default_timezone_set("Asia/Seoul");
define('__XE__', true);
require_once("./../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$oGMdataModel = getModel('gmdata');

$args = new stdClass;
$args->log_time = date("Y-m-d H:i:s",time());
$args->game_token = Context::get('token');
$args->usr_srl = Context::get('srl');
$args->name = Context::get('uname');
$args->chat_datas = Context::get('ch');

$args->name = base64_decode($args->name);
$args->chat_datas = base64_decode($args->chat_datas);

if(empty($args->game_token)||empty($args->usr_srl)||empty($args->chat_datas)||empty($args->name)){
    echo "-1";
    return -1;
}else{
    $out=$oGMdataModel->sendChats($args);
    echo $out;
}
?>