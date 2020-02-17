<?php
define('__XE__', true);
require_once("./../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$oGMdataModel = getModel('gmdata');

if(empty(Context::get('token'))){
    echo "-1";
}else{
    $token = Context::get('token');
    $out=$oGMdataModel->getgameVersion($token);
    if(empty($out)) echo $out->status;
    else{
        $result_str =  '{
            "manifest_url": "https://www.cookiee.net/tools/get_updates?token='.$token.'",
            "current_version":	"",
            "latest_version":	"'.$out->game_showversion.'",
            "url": "'.$out->game_update_link.'",
            "executable_path": "'.$out->game_update_name.'",
            "notice_board_srl": "'.$out->game_notice.'"
        }';
        echo base64_encode($result_str);
    }
}
?>