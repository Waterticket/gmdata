<?php
define('__XE__', true);
require_once("./../config/config.inc.php");
$oContext = &Context::getInstance();
$oContext->init();

$sns_string = base64_decode($_COOKIE[sha1('ck_sns_nm'.base64_decode($_COOKIE[sha1('rand')]))]);

$logged_info_tmp = Context::get('logged_info');
$member_srl = $logged_info_tmp->member_srl;

$oMemberModel = &getModel('member');
$logged_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

if(isset($logged_info->profile_image->src)){
    $profile = $logged_info->profile_image->src;
}else{
    $profile = "https://www.cookiee.net/files/member_extra_info/profile_image/noprofile.png";
}
$nickname = $logged_info->nick_name;

//if(!empty($member_srl)){
if(1){
?>

<title>쿠키 - Login Confirm</title>
    <link rel=stylesheet href='css/login_css.css' type='text/css'>
<style>
    .layer {
        position: relative;
        vertical-align: middle;
    }

    .layer .layer_inner {
        display: block;
        text-align: center;
        top: 50%;
        margin-top: 13%;
    }

    .layer .content{
        display:inline-block;
    }
</style>
<div class="layer">
    <div class="layer_inner">
        <div class="content">
            <img src=<?=$profile?> width="150" height="150" border="1px"/>
            <br><br><h1><?=$nickname;?></h1><br>
            <button type="button" class="eq button button-rounded button-success button-stroke margin-bottom-small waves-effect" onclick="login_as_this();">현재 계정으로 로그인</button> &nbsp;
            <button type="button" class="eq button button-rounded button-primary button-stroke margin-bottom-small waves-effect" onclick="login_as_other();"><img src="images/ic_brand_<?=$sns_string;?>.svg" width="14px" height="14px"/>&nbsp;다른 계정으로 로그인</button>
        </div>
    </div>
</div>
<? }else {
    header("Location: //www.cookiee.net/logins/success");
}?>

<script type="text/javascript">
    function login_as_this() {
        document.write('Wait a second.');
        location.replace('//www.cookiee.net/tools/social_proc.redirect?login=this');
    }
    function login_as_other() {
        document.write('Wait a second.');
        location.replace('//www.cookiee.net/tools/social_proc.redirect?login=other');
    }
</script>
