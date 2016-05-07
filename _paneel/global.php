<?php
if (!isset($_SESSION) || $_SESSION['login'] != 1)
{
    die(header("Location: http://" . $_SERVER['HTTP_HOST']));
}

if (!isset($_SESSION))
{
	session_start();
}

date_default_timezone_set("Europe/Amsterdam");

error_reporting(E_ALL & ~E_NOTICE);

// Classes

require_once($_SERVER["DOCUMENT_ROOT"] . '/_paneel/classes/conversaties.class.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/_paneel/classes/user.class.php');

$conversaties = new Conversaties();
$user = new User();
?>