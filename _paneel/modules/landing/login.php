<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/landing.class.php");

$landing  = new Landing();
$response = $landing->login($_POST["username"], $_POST["password"]);

if ($response === true)
{
	echo "correct";
}
else
{
	echo "Ongeldige gebruikersnaam/wachtwoord combinatie.";
}