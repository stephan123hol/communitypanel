<?php
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/tos/view.php"))
{
	include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/tos/view.php");
}
else
{
	echo "Pagina bestaat niet!";
}
?>