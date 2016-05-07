<?php
$sql_paneeloptiesM = mysql_query("SELECT * FROM paneel_beheer WHERE paneel='".$tag."' ");
$paneeloptiesM = mysql_fetch_assoc($sql_paneeloptiesM);
if ($_SESSION['login'] == 1 && $paneeloptiesM['training'] == 1)
{ 
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."' AND paneel='".$tag."' ");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_trainingen'] == 1)
	{ 
?>
<h2>Trainingen</h2>
	<font color="#CCCCCC">&diams;</font>  <a href="/trainingen">Overzicht trainingen</a><br />
    <br />
	<font color="#CCCCCC">&diams;</font>  <a href="/training_toevoegen">Training toevoegen</a>
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