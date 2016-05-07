<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if (($PaneelLevel['toegang_beheer'] == 1) or ($PaneelLevel['toegang_rangniveau'] == 1))
	{ 
?>
<h1>Beheer</h1>
<hr />
Welkom op het beheer paneel van Imperial Maffia. Op deze pagina kun je leden toevoegen, wijzigen en ontslaan. Tevens bieden we je de mogelijkheid om gedetailleerde logs in te zien zodat je weet wie er wijzigingen heeft doorgevoerd.
<?php
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>