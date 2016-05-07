<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/user.class.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/classes/input.filter.class.php");

$user = new User();
$inputFilter  = new InputFilter("panelify");
$permission = $user->checkPermission($_SESSION["habbonaam"], "hipchat_user_maken");

if ($permission === true)
{
	echo $user->createHipChatUser($_POST["habbonaam"]);
	$user->logAction($_SESSION['ID'], "IM", "created-hipchat-account", null, $_POST["habbonaam"]);
}