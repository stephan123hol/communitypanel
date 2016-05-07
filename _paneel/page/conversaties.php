<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/global.php");
if (!isset($_GET['actie']))
{
	if (isset($dev) && $dev === true) {
		require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/conversaties/view_new.php");
	} else {
		require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/conversaties/view.php");
	}
}
else if (isset($_GET['actie']) && $_GET['actie'] == 'bekijk')
{
	if (isset($_GET['convo_id']) && ctype_digit($_GET['convo_id']))
	{
		if ($conversaties->checkInConversation($_SESSION['ID'], $_GET['convo_id']) === true)
		{
			require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/conversaties/conversation.php");
		}
		else
		{
			echo "Je bent geen lid van deze conversatie!";
		}
	}
	else
	{
		echo "De opgegeven conversatie is incorrect!";
	}
}
else if (isset($_GET['actie']) && $_GET['actie'] == 'nieuw')
{
	require_once($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/conversaties/new.php");
}
else
{
	echo "Pagina niet gevonden!";
}
?>