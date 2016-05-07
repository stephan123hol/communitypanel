<?php
if ($_SESSION['login'] == 1)
{ 
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."' AND paneel='".$tag."' ");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
?>
<h2>Medewerkers</h2>
	<font color="#CCCCCC">&diams;</font>  <a href="/medewerkers">Overzicht medewerkers</a><br />
    <br />
<?php
		if ($PaneelLevel['toegang_promotie'] == 1)
		{
?>
	<font color="#CCCCCC">&diams;</font>  <a href="/promoveren">Promotie geven</a><br />
    <br />
<?php
		}
		$sql_paneeloptiesM = mysql_query("SELECT * FROM paneel_beheer WHERE paneel='".$tag."' ");
		$paneeloptiesM = mysql_fetch_assoc($sql_paneeloptiesM);
		if ($PaneelLevel['toegang_warn'] == 1 && $paneeloptiesM['warn'] == 1)
		{
?>
	<font color="#CCCCCC">&diams;</font>  <a href="/waarschuwingen">Waarschuwingen</a><br />
    <br />
	<font color="#CCCCCC">&diams;</font>  <a href="/waarschuwing_toevoegen">Waarschuwing geven</a><br />
    <br />
<?php
		}
		if ($PaneelLevel['toegang_degradatie'] == 1)
		{
?>
	<font color="#CCCCCC">&diams;</font>  <a href="/degraderen">Degradatie geven</a><br />
    <br />
<?php
		}
		if ($PaneelLevel['toegang_ontslag'] == 1)
		{
?>
	<font color="#CCCCCC">&diams;</font>  <a href="/ontslaan">Ontslag geven</a><br />
    <br />
<?php
		}
}
else
{
	echo '<META http-equiv="refresh" content="0; URL=/">';
}
?>