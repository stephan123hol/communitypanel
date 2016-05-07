<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_promotag'] == 1)
	{ 
////////////////	
?>
        <h1>Promotag toevoegen</h1>
        <hr />
<?php
		if(!isset($_POST['opslaan']))
		{
?>
            <form id="form1" method="post" action="">
            <table width="100%">
			<tr>
            <td width="75%"><input type="text" name="habbonaam" class="textbox" id="habbonaam" placeholder="Habbonaam" /></td>
            </tr>
			<tr>
            <td width="75%"><input type="text" name="promotag" class="textbox" id="promotag" placeholder="promotag zoals BK (zonder haakjes)" /></td>
            </tr>
            <tr>
            <td width="75%"><input type="submit" class="submit" name="opslaan" id="opslaan" value="Opslaan"></td>            
            </tr>
            </table>
            </form>
			<?php
		}
		else
		{
			if(empty($_POST['habbonaam']) or empty($_POST['promotag']) ) 
			{
				echo "Niet alle velden zijn ingevuld.";
			}
			else
			{
				$promotag = mysql_real_escape_string(htmlspecialchars($_POST["promotag"]));
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
				
				$sql_medewerker_check = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' AND (habbonaam, rang_op) IN (SELECT habbonaam, max(rang_op) FROM paneel_rangverandering GROUP BY habbonaam) ORDER BY habbonaam ASC" );
				$row = mysql_fetch_assoc($sql_medewerker_check);
				if($row['rang_nieuw'] == 0)
				{
				echo 'De opgegeven habbonaam komt niet voor in het personeelsbestand.';
				}
				else
				{
					$sql_checkdubbeltag = mysql_query("SELECT * FROM paneel_promotag WHERE promotag='".$promotag."' AND wis='0' ");
					$num_checkdubbeltag = mysql_num_rows($sql_checkdubbeltag);
					if($num_checkdubbeltag != 0)
					{
						echo 'De opgegeven promotag is al in gebruik.';
					}
					else
					{
						$sql_checkdubbelhabbonaam = mysql_query("SELECT * FROM paneel_promotag WHERE habbonaam='".$habbonaam."' AND wis='0' ");
						$num_checkdubbelhabbonaam = mysql_num_rows($sql_checkdubbelhabbonaam);
						if($num_checkdubbelhabbonaam != 0)
						{
							echo 'De opgegeven habbonaam heeft al een promotag.';
						}
						else
						{
					
					mysql_query("INSERT INTO `paneel_promotag` (`habbonaam`, `gegeven_door`, `promotag`, `datum`) VALUES ('".$habbonaam."', '".$_SESSION['habbonaam']."', '".$promotag."', NOW() )");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een promotag toegevoegd.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0; URL=/beheer/promotags">';
						}
					}
				}
			}
		}
///////////////////
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL='.$site.'">';
	}
?>
