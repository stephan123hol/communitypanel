<?php
session_start();

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

include_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/include/functions.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

mysql_connect($host,$gebruikersnaam,$wachtwoord);
mysql_select_db($databasenaam);

date_default_timezone_set('Europe/Amsterdam');
error_reporting(E_ALL & ~E_NOTICE);
ob_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/classes/input.filter.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/conversaties.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/user.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/bans.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/core.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/forum.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/landing.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/classes/password.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/classes/site.status.class.php";

$siteStatus   = new SiteStatus();
$siteStatus->inMaintenance("panelify");

$bans		  = new Bans();
$core 		  = new Core();
$password 	  = new Password();
$inputFilter  = new InputFilter("panelify");
$conversaties = new Conversaties();
$user 		  = new User();
$forum		  = new Forum();
$landing  	  = new Landing();


$landing->destroyUniqueKey();

if (!isset($_SESSION["ID"]))
{
	$landing->setUniqueKey();
	require_once($_SERVER["DOCUMENT_ROOT"] . "/landing.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/paneel_index.php");
}

?>
