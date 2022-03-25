<?php
/*------------------------------------------------------------*/
session_start();
/*------------------------------------------------------------*/
date_default_timezone_set("Asia/Jerusalem");
/*------------------------------------------------------------*/
$ua = @$_SERVER['HTTP_USER_AGENT'];
if (
	! $ua
	|| stristr($ua, "bot")
	|| stristr($ua, "crawl")
	|| stristr($ua, "spider")
	) {
	http_response_code(204);
	exit;
}
/*------------------------------------------------------------*/
require_once("rftConfig.php");
$mdir = M_DIR ;
require_once("$mdir/mfiles.php");
require_once("Rft.class.php");
/*------------------------------------------------------------*/
$Mview = new Mview();
$Mmodel = new Mmodel();
$rft = new Rft($Mmodel, $Mview);
$rft->control();
/*------------------------------------------------------------*/
?>
