<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_warn'] == 1)
	{ 
////////////////	
?>
        <h1>Waarschuwing toevoegen</h1>
        <hr />

<?php
		if(!isset($_POST['opslaan']))
		{
?>
            <form id="form1" method="post" action="">
            <table width="100%">
			<tr>
            <td width="75%"><input type="text" style="width:400px;" name="wie" class="textbox" id="wie" placeholder="Habbonaam gewaarschuwde" /></td>
            </tr>
			<tr>
            <td width="75%"><textarea name="reden" class="textbox" id="reden" placeholder="Waarschuwing"  rows="5"></textarea></td>
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
			if(empty($_POST['wie']) or empty($_POST['reden'])) 
			{
				echo "Niet alle velden zijn ingevuld.";
			}
			else
			{
				$reden = mysql_real_escape_string(htmlspecialchars($_POST["reden"]));
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["wie"]));
				$afgemeld_bij = $_SESSION["habbonaam"];
				
				$sql_medewerker_check = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' AND (habbonaam, rang_op) IN (SELECT habbonaam, max(rang_op) FROM paneel_rangverandering GROUP BY habbonaam) ORDER BY habbonaam ASC" );
				$row = mysql_fetch_assoc($sql_medewerker_check);
				if($row['rang_nieuw'] == 0)
				{
				echo 'De opgegeven habbonaam komt niet voor in het personeelsbestand.';
				}
				else
				{
					mysql_query("INSERT INTO `paneel_warn` (`warn_ontvanger`, `warn`, `warn_gever`, `warn_op`) VALUES ('".$habbonaam."', '".$reden."', '".$afgemeld_bij."',  NOW())");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een waarschuwing toegevoegd.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0; URL=/waarschuwingen">';
				}
			}
		}
///////////////////
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>
