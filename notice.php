<?php
	define('__XE__', true);
	require_once('./config/config.inc.php');
	$oContext = &Context::getInstance();
	$oContext->init();
	$args = new stdClass();

	$args->document_srl = Context::get("srl");
	$boardcontent = executeQuery('document.getDocument',$args)->data;
?>
<!--
뭐 소스코드 캐보려고?
//Made By Waterticket

⊂_ヽ 
　 ＼＼  Λ＿Λ 
　　 ＼ ('ㅅ')  두둠칫 
　　　 >　⌒ヽ 
　　　/ 　 へ＼ 
　　 /　　/　＼＼ 
　　 ﾚ　ノ　　 ヽ_つ 
　　/　/ 두둠칫 
　 /　/| 
　(　(ヽ 
　|　|、＼ 
　| 丿 ＼ ⌒) 
　| |　　) / 
(`ノ )　　Lﾉ

-->
<style>
p { text-align:center; }
</style>
<link rel="stylesheet" href="//www.cookiee.net/score/css/bootstrap.min.css" />
<link rel="icon" href="//www.cookiee.net/favicon.ico" type="image/x-icon" />
<title>게임 공지사항 - 쿠키넷</title>
<section class="xm bs3-wrap">
<div id="general">
	<div style="width:100%; height:20px; font-weight: bold; padding-top: 10px; padding-bottom: 10px; background: #e0e0e0 fixed;"><center><div><?=$boardcontent->title;?></div></center></div><!--
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<ins class="adsbygoogle"
		 style="display:inline-block;width:728px;height:90px"
		 data-ad-client="ca-pub-1032849332108543"
		 data-ad-slot="9580407941"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>-->
	<div id="content">
		<?=$boardcontent->content;?><br>
	</div>
</div>