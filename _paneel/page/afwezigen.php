<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_afwezigen'] == 1)
	{ 
?>
        <h1>Overzicht afwezigen</h1>
        <hr />
<?php
		if ($_GET['actie'] == '')
		{
			$sql_afwezigen = mysql_query("SELECT * FROM paneel_afwezigen WHERE wis='0' ORDER BY habbonaam DESC") or die (mysql_error());
			$aantelregels = mysql_num_rows($sql_afwezigen);
			if($aantelregels == 0)
			{
				echo "Nog geen afwezigheden.";
			}
			else
			{
				$i = 0;
?>			
            <table width="100%" cellpadding="2" cellspacing="0" bgcolor="#424242">
                <tr>
                    <td width="15%" style="color:#FFFFFF;"><b>Habbonaam</b></td>
                    <td width="30%" style="color:#FFFFFF;"><b>Reden</b></td>
                    <td width="15%" style="color:#FFFFFF;"><b>Van</b></td>
                    <td width="15%" style="color:#FFFFFF;"><b>Tot</b></td>
                    <td width="15%" style="color:#FFFFFF;"><b>Afgemeld bij</b></td>
                    <td width="5%" style="color:#FFFFFF;"><b>Wis</b></td>
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
<td width="15%" bgcolor="<?= $bgcolor ?>"><b><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $row['habbonaam']); echo $mijn_dossier; ?>" style="text-decoration:none;"><?= $row['habbonaam']; ?></a></b></td>
<td width="30%" bgcolor="<?= $bgcolor ?>"><?= $row['reden'] ?></td>
<td width="15%" bgcolor="<?= $bgcolor ?>"><?= datumrewrite($row['van']) ?></td>
<td width="15%" bgcolor="<?= $bgcolor ?>"><?= datumrewrite($row['tot']) ?></td>
<td width="15%" bgcolor="<?= $bgcolor ?>"><a href="/profiel/<?= $row['afgemeld_bij']; ?>" style="text-decoration:none;"><?= $row['afgemeld_bij']; ?></a></td>
<td width="5%" bgcolor="<?= $bgcolor ?>"><a href="/profiel/afmelding/wis/<?= $row['id'] ?>"><img src="http://www.panelify.com/images/HELP_Bobba.png"  border="0" /></a></td>
</tr>
<?php		
				} 
				echo "</table>";	
			}
		}
		elseif ($_GET['actie'] == 'del')
		{
			$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
			$sgl_ledenhfcid = mysql_query("SELECT * FROM paneel_afwezigen WHERE id='".$id."'");
			$num_ledenhfcid = mysql_num_rows($sgl_ledenhfcid);
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven afmelding bestaat niet';
			}
			elseif ($num_ledenhfcid == '1')
			{
				mysql_query("UPDATE paneel_afwezigen SET wis='1' WHERE id='".$id."'");
				mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een afmelding gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
				echo '<META http-equiv="refresh" content="0; URL=/afwezigen">';
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>
