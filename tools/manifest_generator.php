<?php
define('__XE__', true);
require_once("./../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$oGMdataModel = getModel('gmdata');
$result_str = "";
if(!empty($_REQUEST['encoded'])){
    $post_value = $_REQUEST['encoded'];
    $token = $post_value;
    $out=$oGMdataModel->getgameVersion($token);
    if(empty($out)) $result_str = $out->status;
    else{
        $result_str =  '{
        "manifest_url": "https://www.cookiee.net/tools/get_updates?token='.$token.'",
        "current_version":	"",
        "latest_version":	"-1",
        "url": "'.$out->game_update_link.'",
        "executable_path": "'.$out->game_update_name.'",
        "notice_board_srl": "'.$out->game_notice.'"
    }';
        $result_str = base64_encode($result_str);
    }

    $result = $result_str;
}else{
    $post_value = "";
    $result = "";
}

$pre_result = $post_value;

?>

<title>Manifest Generator - Cookiee</title>
<style>
    body {
        vertical-align: middle;
    }
</style>

<body>
<h2>GM:S Manifest Generator</h2>
<form action="" method="post">
    <p>토큰을 입력해주세요!</p>
    <textarea name="encoded" cols="80" rows="1" ><?=$pre_result?></textarea><br><br>
    <input type="submit" value="Convert!"/><br><br>
    <textarea name="decoded" cols="80" rows="15" ><?=$result?></textarea><br>
</form>
</body>
