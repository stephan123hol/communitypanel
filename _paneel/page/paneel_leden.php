<?php
if ($_SESSION['login'] == 1)
{ 
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_beheer'] == 1)
	{ 
?>
        <img src="http://habbofanclub.nl/_paneel/assets/afbeelding/icon-aanpassen.png" align="left"  /><h1>Paneel medewerkers beheren</h1>
        <hr />

<?php 
		if($_GET['edit'] == '' && $_GET['del'] == '')
		{
			$sql_ledenlijst = mysql_query("SELECT * FROM paneel_personeel ORDER BY habbonaam ASC");
?>
<img src="http://habbofanclub.nl/images/fancenter/icons/status_closed.gif" /> = Nog geen promotie ontvangen.
	<table width="100%" border="0" cellpadding="1" cellspacing="0" style="background-color:#6C6C6C; color:#FFFFFF; font-size:9px;">
	  	<tr>
			<td width="23%">Habbonaam</td>
			<td width="7%">Beheer</td>
			<td width="9%">Trainingen</td>
			<td width="7%">Warn</td>
			<td width="8%">Promotie</td>
			<td width="9%">Degradatie</td>
			<td width="7%">Ontslag</td>
			<td width="9%">promotag</td>
			<td width="7%">HipChat</td>
			<td width="7%">Ww reset toestaan</td>
			<td width="5%"></td>
			<td width="5%"></td>
	  	</tr>
		</table>
<?php		
			while($ll = mysql_fetch_assoc($sql_ledenlijst)) 
			{
				$sql_controle = mysql_query("SELECT habbonaam FROM paneel_rangverandering WHERE habbonaam='".$ll['habbonaam']."'");
			$mc = mysql_num_rows($sql_controle);
?>		
		<table width="100%" border="0" cellpadding="1" cellspacing="0" onMouseover="this.style.backgroundColor='lightgray';" onMouseout="this.style.backgroundColor='#FFFFFF';">
            <tr>
                <td width="26%"><b><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $ll['habbonaam']); echo $mijn_dossier; ?>" style="text-decoration:none;"><?= $ll['habbonaam']; ?></a></b>
                <?php if($mc == 0) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/status_closed.gif">'; }  ?></td>
                <td width="7%"><?php if($ll['toegang_beheer'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="9%"><?php if($ll['toegang_trainingen'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="7%"><?php if($ll['toegang_warn'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="8%"><?php if($ll['toegang_promotie'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="9%"><?php if($ll['toegang_degradatie'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="7%"><?php if($ll['toegang_ontslag'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="9%"><?php if($ll['toegang_promotag'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="9%"><?php if($ll['hipchat_user_maken'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="9%"><?php if($ll['grant_pw_change'] == 1) { echo'<img src="http://habbofanclub.nl/images/fancenter/icons/v20_4.gif" />'; }  ?></td>
                <td width="5%"><a href="/beheer/edit_paneellid/<?= $mijn_dossier ?>"><img border="0" src="http://habbofanclub.nl/images/fancenter/icons/tools_edit.gif" /></a></td>
                <td width="5%"><a href="/beheer/wis_paneellid/<?= $mijn_dossier ?>"><img border="0" src="http://habbofanclub.nl/images/fancenter/icons/v22_3.gif" /></a></td>
          	</tr>
		</table>
		
<?php 		
			}
//////////////
		}
		if($_GET['del'] == '1')
		{
			$habbonaam = mysql_real_escape_string(htmlspecialchars($_GET['habbonaam']));
			$sgl_ledenhfcid = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
			$num_ledenhfcid = mysql_num_rows($sgl_ledenhfcid);
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven habbonaam is geen geregistreerde paneel medewerker.';
			}
			elseif ($num_ledenhfcid == '1')
			{
				mysql_query("DELETE FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
				mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een paneel medewerker gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
				echo 'De medewerker is succesvol gewist. Je wordt doorgestuurd naar de lijst van paneel medewerkers.';
				echo '<META http-equiv="refresh" content="5; URL=/beheer/paneel_leden">';
			}

///////////////
		}
		elseif ($_GET['edit'] == '1')
		{
			$habbonaam = mysql_real_escape_string(htmlspecialchars($_GET['habbonaam']));
			$sql_ledenid = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
			$num_ledenid = mysql_num_rows($sql_ledenid);
			if ($num_ledenid == '0')
			{
				echo 'De opgegeven habbonaam is geen geregistreerde paneel medewerker.';
			}
			elseif ($num_ledenid == '1')
			{
					$lidedit = mysql_fetch_assoc($sql_ledenid);
					if(!isset($_POST['opslaan']))
					{
?>
<form id="form1" method="post" action="">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td style="font-weight:bold;">Habbonaam</td>
        <td colspan="2"><?= $habbonaam ?></td>
    </tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang beheer</td>
        <td><input type="radio" name="toegang_beheer" id="toegang_beheer" value="1" <?php if($lidedit['toegang_beheer'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_beheer" id="toegang_beheer" value="0" <?php if($lidedit['toegang_beheer'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang trainingen</td>
        <td><input type="radio" name="toegang_trainingen" id="toegang_trainingen" value="1" <?php if($lidedit['toegang_trainingen'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_trainingen" id="toegang_trainingen" value="0" <?php if($lidedit['toegang_trainingen'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang afwezigen</td>
        <td><input type="radio" name="toegang_afwezigen" id="toegang_afwezigen" value="1" <?php if($lidedit['toegang_afwezigen'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_afwezigen" id="toegang_afwezigen" value="0" <?php if($lidedit['toegang_afwezigen'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang waarschuwingen</td>
        <td><input type="radio" name="toegang_warn" id="toegang_warn" value="1" <?php if($lidedit['toegang_warn'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_warn" id="toegang_warn" value="0" <?php if($lidedit['toegang_warn'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang promotie</td>
        <td><input type="radio" name="toegang_promotie" id="toegang_promotie" value="1" <?php if($lidedit['toegang_promotie'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_promotie" id="toegang_promotie" value="0" <?php if($lidedit['toegang_promotie'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang degradatie</td>
        <td><input type="radio" name="toegang_degradatie" id="toegang_degradatie" value="1" <?php if($lidedit['toegang_degradatie'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_degradatie" id="toegang_degradatie" value="0" <?php if($lidedit['toegang_degradatie'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Toegang ontslag</td>
        <td><input type="radio" name="toegang_ontslag" id="toegang_ontslag" value="1" <?php if($lidedit['toegang_ontslag'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_ontslag" id="toegang_ontslag" value="0" <?php if($lidedit['toegang_ontslag'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Toegang promotag</td>
        <td><input type="radio" name="toegang_promotag" id="toegang_promotag" value="1" <?php if($lidedit['toegang_promotag'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="toegang_promotag" id="toegang_promotag" value="0" <?php if($lidedit['toegang_promotag'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
	<tr>
    	<td style="font-weight:bold;">HipChat gebruikers maken</td>
        <td><input type="radio" name="hipchat_user_maken" id="hipchat_user_maken" value="1" <?php if($lidedit['hipchat_user_maken'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="hipchat_user_maken" id="hipchat_user_maken" value="0" <?php if($lidedit['hipchat_user_maken'] == 0) { echo 'checked'; }?>> Nee </td>
	</tr>
	<tr>
    	<td style="font-weight:bold;">Wachtwoord resetten toestaan</td>
        <td><input type="radio" name="grant_pw_change" id="grant_pw_change" value="1" <?php if($lidedit['grant_pw_change'] == 1) { echo 'checked'; }?>> Ja </td>
        <td><input type="radio" name="grant_pw_change" id="grant_pw_change" value="0" <?php if($lidedit['grant_pw_change'] == 0) { echo 'checked'; }?>> Nee </td>
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
						if( ($_POST['toegang_beheer'] == "") or ($_POST['toegang_trainingen'] == "") or ($_POST['toegang_afwezigen'] == "") or ($_POST['toegang_warn'] == "") or ($_POST['toegang_promotie'] == "") or ($_POST['toegang_degradatie'] == "") or ($_POST['toegang_ontslag'] == "") or ($_POST['toegang_promotag'] == "") )
						{
							echo 'Niet alle velden zijn ingevuld. Probeer het opnieuw.';
						}
						else
						{
							$toegang_beheer = mysql_real_escape_string(htmlspecialchars($_POST["toegang_beheer"]));
							$toegang_trainingen = mysql_real_escape_string(htmlspecialchars($_POST["toegang_trainingen"]));
							$toegang_afwezigen = mysql_real_escape_string(htmlspecialchars($_POST["toegang_afwezigen"]));
							$toegang_warn = mysql_real_escape_string(htmlspecialchars($_POST["toegang_warn"]));
							$toegang_promotie = mysql_real_escape_string(htmlspecialchars($_POST["toegang_promotie"]));
							$toegang_degradatie = mysql_real_escape_string(htmlspecialchars($_POST["toegang_degradatie"]));
							$toegang_ontslag = mysql_real_escape_string(htmlspecialchars($_POST["toegang_ontslag"]));
							$toegang_promotag = mysql_real_escape_string(htmlspecialchars($_POST["toegang_promotag"]));
							$hipchat_user_maken = mysql_real_escape_string(htmlspecialchars($_POST["hipchat_user_maken"]));
							$grant_pw_change = mysql_real_escape_string(htmlspecialchars($_POST["grant_pw_change"]));
		
							mysql_query("UPDATE paneel_personeel SET toegang_beheer='".$toegang_beheer."', toegang_trainingen='".$toegang_trainingen."', toegang_afwezigen='".$toegang_afwezigen."', toegang_warn='".$toegang_warn."', toegang_promotie='".$toegang_promotie."', toegang_degradatie='".$toegang_degradatie."', toegang_ontslag='".$toegang_ontslag."', toegang_promotag='".$toegang_promotag."', hipchat_user_maken = '".$hipchat_user_maken."', grant_pw_change = '".$grant_pw_change."', datum=NOW() WHERE habbonaam='".$habbonaam."'");
							
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een paneel medewerker aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo 'De rang is succesvol aangepast. Je wordt doorgestuurd naar de lijst van paneel medewerkers.';
							echo '<META http-equiv="refresh" content="5; URL=/beheer/paneel_leden">';
						}
					}
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL='.$site.'">';
	}
}
else
{
	echo '<META http-equiv="refresh" content="0; URL='.$site.'">';
}
?>