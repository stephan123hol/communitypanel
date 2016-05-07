<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/landing.class.php");

$landing  = new Landing();
$response = $landing->passwordReset($_POST["username"], $_POST["password"], $_POST["password_repeat"]);

if ($response === true)
{
	echo "correct";
}
else
{
	header('Content-Type: application/json');
	echo json_encode($response);
}