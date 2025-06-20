<?php
$qcount = 0;
include_once("inc/config.inc.php");
include_once("class/mod.class.php");
include_once("class/db.class.php");
session_start();

// Variablen sicher abrufen (sowohl GET als auch POST)
$page = isset($_POST['page']) ? $_POST['page'] : (isset($_GET['page']) ? $_GET['page'] : 'main');
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$section = isset($_POST['section']) ? $_POST['section'] : (isset($_GET['section']) ? $_GET['section'] : '');
$field = isset($_POST['field']) ? $_POST['field'] : (isset($_GET['field']) ? $_GET['field'] : '');
$col = isset($_POST['col']) ? $_POST['col'] : (isset($_GET['col']) ? $_GET['col'] : '');
$class = isset($_POST['class']) ? $_POST['class'] : (isset($_GET['class']) ? $_GET['class'] : '');
$classid = isset($_POST['classid']) ? $_POST['classid'] : (isset($_GET['classid']) ? $_GET['classid'] : '');
$shipid = isset($_POST['shipid']) ? $_POST['shipid'] : (isset($_GET['shipid']) ? $_GET['shipid'] : '');
$id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : '');
$tpa = isset($_POST['tpa']) ? $_POST['tpa'] : (isset($_GET['tpa']) ? $_GET['tpa'] : '');
$tid = isset($_POST['tid']) ? $_POST['tid'] : (isset($_GET['tid']) ? $_GET['tid'] : '');
$tsec = isset($_POST['tsec']) ? $_POST['tsec'] : (isset($_GET['tsec']) ? $_GET['tsec'] : '');
$tfie = isset($_POST['tfie']) ? $_POST['tfie'] : (isset($_GET['tfie']) ? $_GET['tfie'] : '');
$tcol = isset($_POST['tcol']) ? $_POST['tcol'] : (isset($_GET['tcol']) ? $_GET['tcol'] : '');
$tcla = isset($_POST['tcla']) ? $_POST['tcla'] : (isset($_GET['tcla']) ? $_GET['tcla'] : '');
$tclai = isset($_POST['tclai']) ? $_POST['tclai'] : (isset($_GET['tclai']) ? $_GET['tclai'] : '');
$tsid = isset($_POST['tsid']) ? $_POST['tsid'] : (isset($_GET['tsid']) ? $_GET['tsid'] : '');
$tuid = isset($_POST['tuid']) ? $_POST['tuid'] : (isset($_GET['tuid']) ? $_GET['tuid'] : '');


// Überprüfung der Cookies
if (!$_SESSION["user"] || !$_SESSION["chk"]) {
	$page = "error";
	$errorId = "996";
}
// Datenbankverbindung herstellen
$user = $_SESSION["user"];

$myDB = new db;
if ($sqlerr == 1) {
	$page = "error";
	$errorid = 998;
	addlog("998", "1", $user, "Datenbankfehler");
} else {
	// Start der Session bzw. Überprüfung einer bereitss bestehenden Session
	include_once("class/usersess.class.php");
	$mySession = new usersess;
	$login = $mySession->checkcookie();
	if ($login != 1) {
		$errorid = 996;
		echo "<head>
		<link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css>
	  </head>";
		include_once("error.php");
		exit;
	}
	// Include der restlichen Klassen für Schiffssteuerung, etc
	include_once("class/game.class.php");
	$myGame = new game;
	if ($myGame->getvalue('tick') == 1) {
		$errorid = 100;
		echo "<head>
		<link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css>
	  </head>";
		include_once("error.php");
		exit;
	}
	include_once("class/user.class.php");
	$myUser = new user;
	$grafik = $myUser->ugrafik;
	include_once("class/comm.class.php");
	$myComm = new comm;
	include_once("class/ship.class.php");
	$myShip = new ship;
	include_once("class/fleet.class.php");
	$myFleet = new fleet;
	include_once("class/colony.class.php");
	$myColony = new colony;
	include_once("class/map.class.php");
	$myMap = new map;
	include_once("class/ally.class.php");
	$myAlly = new ally;
	include_once("class/trade.class.php");
	$myTrade = new trade;
	include_once("class/history.class.php");
	$myHistory = new history;
	$myGame->loguser(getenv("REMOTE_ADDR"), getenv("HTTP_USER_AGENT"));
	if ($page != "main" && $page != "options" && $page != "logout" && $page && $myUser->udelmark == 1) $myUser->updateUserById($user, 0, "delmark");
}
if ($page != "error") $result = $mySession->sessioncheck();
if ($page == "logout") $mySession->logout();
if ($page != "error") $wartung = $myGame->getvalue("wartung");
if (($user > 102) && ($wartung == 1)) $page = "wartung";
if (($page != "wartung") && ($page != "tick") && ($page != "error")) {
	//$myColony->finishProcesses();
	//$myColony->setdaytime();
}
if ($myUser->umozilla == 1) {
	$css = "gfx/css/style.css";
	$mcss = "../gfx/css/style.css";
} else {
	$css = $grafik . "/css/style.css";
	$mcss = $grafik . "/css/style.css";
}
if ($login == 1) {
	if (isset($_POST["avm"]) && $_POST["avm"] == "on" && $myUser->upvac > 0) {
		$mySession->logout();
		$myUser->avm();
		$errorid = 700;
		echo "<head><link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css></head>";
		include_once("error.php");
		$myGame->addlog("700", "5", $user, "Urlaubsmodus aktiviert");
		exit;
	}
	$mdvm = 0;
	if ($myUser->uvac == 1) {
		$mdvm = 1;
		$myUser->dvm();
	}
	$result = $myComm->checknewmsg($user);
	if ($action == "ignorepm") {
		$myComm->markallpmasread($user);
		echo "<script language=Javascript>parent.frames[1].location.href=\"static/leftbottom.php?grafik=$grafik&css=$mcss\";</script>";
		$result = 0;
	}
	if (($result != 0) && ($section != "delall") && ($page != "npm")) {
		if (($page == "showinfo") || ($page == "shiphelp") || ($page == "help")) {
			$tpa = $page;
			$tid = $id;
			$tsec = $section;
			$tfie = $field;
			$tcol = $col;
			$tcla = $class;
			$tclai = $classid;
			$tsid = $shipid;
		}
		echo "<script language=Javascript>parent.frames[1].location.href=\"main.php?page=npm&tpa=" . $tpa . "&tid=" . $tid . "&tsec=" . $tsec . "&tfie=" . $tfie . "&tcol=" . $tcol . "&tcla=" . $tcla . "&tclai=" . $tclai . "&tsid=" . $tsid . "\";</script>";
	}
}
switch ($page) {
	default:
		$inc = "desk.php";
	case "main":
		$inc = "desk.php";
		break;
	case "comm":
		$inc = "comm.php";
		break;
	case "options":
		$inc = "options.php";
		break;
	case "colony":
		$inc = "colony.php";
		break;
	case "ship":
		$inc = "ship.php";
		break;
	case "trade":
		$inc = "trade.php";
		break;
	case "ally":
		$inc = "ally.php";
		break;
	case "hally":
		$inc = "hally.php";
		break;
	case "showinfo":
		$inc = "showinfo.php";
		break;
	case "help":
		$inc = "help.php";
		break;
	case "shiphelp":
		$inc = "shiphelp.php";
		break;
	case "starmap":
		$inc = "starmap.php";
		break;
	case "history":
		$inc = "history.php";
		break;
	case "stats":
		$inc = "stats.php";
		break;
	case "npc":
		$inc = "npc.php";
		break;
	case "folist":
		$inc = "folist.php";
		break;
	case "npm":
		$inc = "npm.php";
		break;
	case "shiptest":
		$inc = "shiptest.php";
		break;
	case "logout":
		$inc = "logout.php";
		break;
	case "error":
		$inc = "error.php";
		break;
	case "wartung":
		$inc = "error.php";
		$errorid = 999;
		break;
	case "tick":
		$inc = "error.php";
		$errorid = 100;
		break;
}
if (!$grafik) $grafik = "gfx/";
echo "<head>
	<link rel=\"STYLESHEET\" type=\"text/css\" href=\"" . $css . "\">
	<SCRIPT LANGUAGE='JavaScript'><!--
	
	var Win = null;
	
	function openfl()
	{
	        str=\"main.php?page=folist\";
	        Win = window.open(str,'Win','width=300,height=400,resizeable=no,scrollbars=yes');
	        window.open(str,'Win','width=300,height=400');
	        Win.opener = self;
	}
	
	function cp(objekt,datei)
	{
		document.images['objekt'].src = \"" . $grafik . "/\" + datei + \".gif\"
	}
	//-->
	</SCRIPT>";
if ($page == "folist") echo "<title>STU Kontaktliste</title>";
echo "</head>";
if ($page == "folist") echo "<meta http-equiv=\"REFRESH\" content=\"200; url=http://www.stuniverse.de/main.php?page=folist\">";
unset($result);
include_once($inc);
echo "<br>Queries: " . $qcount;
if (time() >= $myDB->query("SELECT value FROM stu_game WHERE fielddescr='proceed_time'", 1)) {
	$myDB->query("UPDATE stu_game SET value=" . (time() + 15) . " WHERE fielddescr='proceed_time'");
	$myDB->query("UPDATE stu_game SET value=" . $user . " WHERE fielddescr='proceed_user'");
}
