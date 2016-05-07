<?php
$sql_paneeloptiesM = mysql_query("SELECT * FROM paneel_beheer WHERE paneel='".$tag."' ");
$paneeloptiesM = mysql_fetch_assoc($sql_paneeloptiesM);
if ($_SESSION['login'] == 1 && $paneeloptiesM['afwezig'] == 1)
{ 
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."' AND paneel='".$tag."' ");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_afwezigen'] == 1)
	{ 
?>
<h2>Afwezigen</h2>
	<font color="#CCCCCC">&diams;</font>  <a href="/afwezigen">Overzicht afwezigen</a><br />
    <br />
	<font color="#CCCCCC">&diams;</font>  <a href="/afmelding_toevoegen">Afmelding toevoegen</a>
<?php
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
}
else
{
	echo '<META http-equiv="refresh" content="0; URL=/">';
}
?>