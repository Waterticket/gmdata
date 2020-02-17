<?php
define('__XE__',true);
require_once("../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$oGMdataModel = getModel('gmdata');
$game_token = Context::get('token');
if(empty($game_token)){
    echo "-1";
    return -1;
}

$out=$oGMdataModel->getChatDatas($game_token);
$cnt = count($out);
$result_data = '{"chatting":[';
foreach ($out as $key => $row) {
    $result_data = $result_data . '{"user_srl":'.$row->usr_srl.',"user_name":"'.$row->name.'","chat":"'.$row->chat_datas.'","time":"'.$row->log_time.'"}';
    if($key+1!=$cnt){
        $result_data = $result_data . ',';
    }
}
$result_data = $result_data . ']}';
echo base64_encode($result_data);
?>
