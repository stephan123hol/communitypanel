<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/user.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/classes/input.filter.class.php");

$user = new User();
$inputFilter  = new InputFilter("panelify");
$permission = $user->checkPermission($_SESSION["habbonaam"], "grant_pw_change");

if ($permission === true)
{
	$user->setUserVar("allow_pw_change", 1, $_POST["habbonaam"]);
	$user->logAction($_SESSION['ID'], "IM", "granted-pw-change", null, $_POST["habbonaam"]);
}