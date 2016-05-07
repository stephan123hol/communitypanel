<?php
$action = (!isset($_GET["action"])) ? "home" : $_GET["action"];

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/stats/" . $action . ".php"))
{
	include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/stats.class.php");
	$stats = new Stats();
	include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/stats/" . $action . ".php");
}
else
{
	echo "Pagina bestaat niet!";
}
?>