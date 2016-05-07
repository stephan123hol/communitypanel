<?php
if ($user->isAdmin($_SESSION["habbonaam"]) === true)
{
	$action = (!isset($_GET["action"])) ? "home" : $_GET["action"];

	if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/admin/" . $action . ".php"))
	{
		include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/admin/" . $action . ".php");
	}
	else
	{
		echo "Pagina bestaat niet!";
	}
}
else
{
	header("Location: /");
	exit;
}
?>