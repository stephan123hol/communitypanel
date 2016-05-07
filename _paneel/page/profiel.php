<?php
if (isset($_GET["user"]) && !empty($_GET["user"]))
{
	if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/profiel/view.php"))
	{
		include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/profile.class.php");
		
		$profile  = new Profile();
		$username = $user->sanitizeName($_GET["user"]);
		
		if ($profile->hasProfile($username) === true)
		{
			include($_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/profiel/view.php");
		}
		else
		{
			echo "Gebruiker heeft nog geen profiel (heeft nog geen trainingen/promoties gehad).";
			echo $_GET['user'];
		}
	}
	else
	{
		echo "Pagina bestaat niet!";
	}
}
else
{
	echo "Geen gebruiker ingevoerd.";
	echo $_GET["user"];
	echo ".";
}
?>