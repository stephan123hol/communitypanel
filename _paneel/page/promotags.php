<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_promotag'] == 1)
	{ 
?>
        <h1>Promotag overzicht</h1>
        <hr />
<?php
		if ($_GET['actie'] == '')
		{
			$sql_afwezigen = mysql_query("SELECT * FROM paneel_promotag WHERE wis='0' ORDER BY habbonaam ASC")or die (mysql_error());
			$aantelregels = mysql_num_rows($sql_afwezigen);
			if($aantelregels == 0)
			{
				echo "Nog geen promotags.";
			}
			else
			{
				$i = 0;
?>			
            <table width="100%" cellpadding="2" cellspacing="0" bgcolor="#424242">
                <tr>
                    <td width="30%" style="color:#FFFFFF;"><b>Habbonaam</b></td>
                    <td width="30%" style="color:#FFFFFF;"><b>promotag</b></td>
                    <td width="30%" style="color:#FFFFFF;"><b>toegevoegd op</b></td>
                    <td width="10%" style="color:#FFFFFF;"><b>Wis</b></td>
                </tr>
            </table>
            
            <table width="100%" cellpadding="2" cellspacing="0">
    
<?php
				while($row = mysql_fetch_assoc($sql_afwezigen)) 
				{
					if($i == 1)
					{
						 $bgcolor = '#EEEEEE';
						 $i--;
					}
					else
					{
						 $bgcolor = '#FFFFFF';
						 $i++;
					}
?>
<tr>
<td width="30%" bgcolor="<?= $bgcolor ?>"><b><a href="/dossier/<?php $mijn_dossier = str_replace("?", "%3F", $row['habbonaam']); echo $mijn_dossier; ?>" style="text-decoration:none;"><?= $row['habbonaam']; ?></a></b></td>
<td width="30%" bgcolor="<?= $bgcolor ?>"><?= $row['promotag'] ?></td>
<td width="30%" bgcolor="<?= $bgcolor ?>"><?= $row['datum'] ?></td>
<td width="10%" bgcolor="<?= $bgcolor ?>"><a href="/beheer/promotag/wis/<?= $row['id'] ?>"><img src="http://habbofanclub.nl/images/HELP_Bobba.png"  border="0" /></a></td>
</tr>
<?php		
				} 
				echo "</table>";	
			}
		}
		elseif ($_GET['actie'] == 'del')
		{
			$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
			$sgl_ledenhfcid = mysql_query("SELECT * FROM paneel_promotag WHERE id='".$id."'");
			$num_ledenhfcid = mysql_num_rows($sgl_ledenhfcid);
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven promotag bestaat niet';
			}
			elseif ($num_ledenhfcid == '1')
			{
				mysql_query("UPDATE paneel_promotag SET wis='1' WHERE id='".$id."'");
				mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een promotag gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
				echo '<META http-equiv="refresh" content="0; URL=/beheer/promotags">';
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>
