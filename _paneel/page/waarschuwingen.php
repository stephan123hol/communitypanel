<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_warn'] == 1)
	{ 
?>
        <h1>Overzicht waarschuwingen</h1>
        <hr />
<?php
		if ($_GET['actie'] == '')
		{
			$sql_afwezigen = mysql_query("SELECT * FROM paneel_warn WHERE wis='0' ORDER BY id DESC")or die (mysql_error());
			$aantelregels = mysql_num_rows($sql_afwezigen);
			if($aantelregels == 0)
			{
				echo "Nog geen waarschuwingen.";
			}
			else
			{
				$i = 0;
?>			
            <table width="100%" cellpadding="2" cellspacing="0" bgcolor="#424242">
                <tr>
                    <td width="20%" style="color:#FFFFFF;"><b>Habbonaam</b></td>
                    <td width="40%" style="color:#FFFFFF;"><b>Waarschuwing</b></td>
                    <td width="15%" style="color:#FFFFFF;"><b>Datum</b></td>
                    <td width="20%" style="color:#FFFFFF;"><b>Gegeven door</b></td>
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
<td width="20%" bgcolor="<?= $bgcolor ?>"><b><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $row['warn_ontvanger']); echo $mijn_dossier; ?>" style="text-decoration:none;"><?= $row['warn_ontvanger']; ?></a></b></td>
<td width="40%" bgcolor="<?= $bgcolor ?>"><?= $row['warn'] ?></td>
<td width="15%" bgcolor="<?= $bgcolor ?>"><?= datumrewrite($row['warn_op']) ?></td>
<td width="20%" bgcolor="<?= $bgcolor ?>"><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $row['warn_gever']); echo $mijn_dossier; ?>" style="text-decoration:none;"><?= $row['warn_gever']; ?></a></td>
</tr>
<?php		
				} 
				echo "</table>";	
			}
		}
		elseif ($_GET['actie'] == 'del')
		{
			$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
			$sgl_ledenhfcid = mysql_query("SELECT * FROM paneel_warn WHERE id='".$id."'");
			$num_ledenhfcid = mysql_num_rows($sgl_ledenhfcid);
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven waarschuwing bestaat niet';
			}
			elseif ($num_ledenhfcid == '1')
			{
				mysql_query("UPDATE paneel_warn SET wis='1' WHERE id='".$id."'");
				mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een waarschuwing gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
				echo '<META http-equiv="refresh" content="0; URL=/waarschuwingen">';
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>
