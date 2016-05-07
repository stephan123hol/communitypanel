<?php
if ($_SESSION['login'] == 1)
{ 
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."' AND paneel='".$tag."' ");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if (($PaneelLevel['toegang_beheer'] == 1) or ($PaneelLevel['toegang_rangniveau'] == 1))
	{ 
?>
<h2>Beheer</h2>
<?php
		if ($PaneelLevel['toegang_beheer'] == 1)
		{
?>
	<font color="#CCCCCC">&diams;</font>  <a href="/beheer/paneel_leden">Paneel medewerkers</a><br />
    <br />
	<font color="#CCCCCC">&diams;</font>  <a href="/beheer/paneel_lid_toevoegen">Paneel medewerker toevoegen</a><br />
    <br />
<?php
		}
		$sql_paneeloptiesM = mysql_query("SELECT * FROM paneel_beheer WHERE paneel='".$tag."' ");
		$paneeloptiesM = mysql_fetch_assoc($sql_paneeloptiesM);
		if ($PaneelLevel['toegang_promotag'] == 1 && $paneeloptiesM['promotag'] == 1)
		{
?>
	    <font color="#CCCCCC">&diams;</font>  <a href="/beheer/promotag_toevoegen">Promotag toevoegen</a><br /><br />
      <font color="#CCCCCC">&diams;</font>  <a href="/beheer/promotags">Promotags beheren</a><br /><br />
<?php
		}
			if( (in_array(strtolower($_SESSION['habbonaam']), $bedrijf_beheerder)) or  (in_array(strtolower($_SESSION['habbonaam']), $hfc_beheerder))  )
			{
?>					
    		<font color="#CCCCCC">&diams;</font>  <a href="/beheer/rangniveau_toevoegen">Rangniveau toevoegen</a><br /><br />
      		<font color="#CCCCCC">&diams;</font>  <a href="/beheer/rangniveaus">Rangniveaus beheren</a><br /><br />
      
<?php	
		}
		
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