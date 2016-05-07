<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."' AND paneel='".$tag."' ");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_beheer'] == 1)
	{ 
?>
        <h1>Paneel medewerker toevoegen</h1>
        <hr />

<?php 
		if(!isset($_POST['opslaan']))
		{
?>
            Op deze pagina kun je handmatig leden toevoegen op het beheer paneel. Dat wil ook zeggen dat je zelf kunt bepalen waartoe deze Habbo toegang heeft op het paneel. Let wel op dat dit alleen gaat om het feit of ze de pagina's kunnen bezoeken. Op de pagina's zelf zijn extra restricties op rang tot in hoeverre ze de pagina ook kunnen gebruiken. Later kun je dat alsnog aanpassen natuurlijk. Let er wel op dat de persoon in kwestie een account moet hebben geregistreerd! <br />
<br />
In het registratie-systeem is een automatische controle gemaakt, gekoppeld aan de personeelslijst. Als een Habbo zich registreert in het systeem en blijkt volgens zijn of haar rang al op een bepaald toegangsniveau te zitten, zullen deze ook automatisch worden uitgedeeld. Mocht hierin iets mis gaan, meld dit dan aan de beheerders, zodat zij het systeem kunnen optimaliseren en verbeteren. Gebruik deze optie dus eigenlijk alleen om leden toe te voegen als uitzondering, dus zonder rang in het systeem.<br /><br />

<form id="form1" method="post" action="">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td style="font-weight:bold;">Habbonaam</td>
        <td colspan="2"><input type="text" class="textbox" name="habbonaam" id="habbonaam" value="" /></td>
    </tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang beheer</td>
        <td><input type="radio" name="toegang_beheer" id="toegang_beheer" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_beheer" id="toegang_beheer" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang trainingen</td>
        <td><input type="radio" name="toegang_trainingen" id="toegang_trainingen" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_trainingen" id="toegang_trainingen" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang afwezigen</td>
        <td><input type="radio" name="toegang_afwezigen" id="toegang_afwezigen" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_afwezigen" id="toegang_afwezigen" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang waarschuwingen</td>
        <td><input type="radio" name="toegang_warn" id="toegang_warn" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_warn" id="toegang_warn" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang promotie</td>
        <td><input type="radio" name="toegang_promotie" id="toegang_promotie" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_promotie" id="toegang_promotie" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang degradatie</td>
        <td><input type="radio" name="toegang_degradatie" id="toegang_degradatie" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_degradatie" id="toegang_degradatie" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang ontslag</td>
        <td><input type="radio" name="toegang_ontslag" id="toegang_ontslag" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_ontslag" id="toegang_ontslag" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang promotag</td>
        <td><input type="radio" name="toegang_promotag" id="toegang_promotag" value="1" /><img border="0" src="http://panelify.com/images/fancenter/icons/v20_4.gif" /></td>
        <td><input type="radio" name="toegang_promotag" id="toegang_promotag" value="0" /><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></td>
	</tr>
	<tr>
    	<td></td>
        <td colspan="2"><input type="submit" class="submit" name="opslaan" id="opslaan" value="opslaan"></td>
    </tr>
</table>    
</form>

<?php
		}
		else if(isset($_POST['opslaan']))
		{
			if(empty($_POST['habbonaam']) or ($_POST['toegang_beheer'] == "") or ($_POST['toegang_trainingen'] == "") or ($_POST['toegang_afwezigen'] == "") or ($_POST['toegang_warn'] == "") or ($_POST['toegang_promotie'] == "") or ($_POST['toegang_degradatie'] == "") or ($_POST['toegang_ontslag'] == "") or ($_POST['toegang_promotag'] == "") )
			{
				echo 'Niet alle velden zijn ingevuld. Probeer het opnieuw.';
			}
			else
			{
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
				$sql_ledenhfc = mysql_query("SELECT * FROM leden WHERE habbonaam='".$habbonaam."'");
				$num_ledenhfc = mysql_num_rows($sql_ledenhfc);
				if ($num_ledenhfc == '0')
				{
					echo 'De opgegeven Habbonaam heeft zich nog niet geregistreerd op panelify.com en dus ook niet voor het paneel.';
				}
				elseif ($num_ledenhfc == '1')
				{
					$sql_beheercontrole = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$habbonaam."'");
					$num_beheercontrol = mysql_num_rows($sql_beheercontrole);
					if ($num_beheercontrol == '0')
					{
						$toegang_beheer = mysql_real_escape_string(htmlspecialchars($_POST["toegang_beheer"]));
						$toegang_trainingen = mysql_real_escape_string(htmlspecialchars($_POST["toegang_trainingen"]));
						$toegang_afwezigen = mysql_real_escape_string(htmlspecialchars($_POST["toegang_afwezigen"]));
						$toegang_warn = mysql_real_escape_string(htmlspecialchars($_POST["toegang_warn"]));
						$toegang_promotie = mysql_real_escape_string(htmlspecialchars($_POST["toegang_promotie"]));
						$toegang_degradatie = mysql_real_escape_string(htmlspecialchars($_POST["toegang_degradatie"]));
						$toegang_ontslag = mysql_real_escape_string(htmlspecialchars($_POST["toegang_ontslag"]));
						$toegang_promotag = mysql_real_escape_string(htmlspecialchars($_POST["toegang_promotag"]));
			
						mysql_query("INSERT INTO `paneel_personeel` (`habbonaam`, `toegang_beheer`, `toegang_trainingen`, `toegang_afwezigen`, `toegang_warn`, `toegang_promotie`, `toegang_degradatie`, `toegang_ontslag`, `toegang_promotag`, `datum`) VALUES ('".$habbonaam."', '".$toegang_beheer."', '".$toegang_trainingen."', '".$toegang_afwezigen."', '".$toegang_warn."', '".$toegang_promotie."', '".$toegang_degradatie."', '".$toegang_ontslag."', '".$toegang_promotag."', NOW())");
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een paneel medewerker toegevoegd.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						echo 'De rang is succesvol toegevoegd. Je wordt doorgestuurd naar de lijst van paneel medewerkers.';
						echo '<META http-equiv="refresh" content="5; URL=/beheer/paneel_leden">';
					}
					else
					{
						echo 'De opgegeven Habbonaam komt al voor in het systeem als paneel medewerker.';
					}
				}
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>