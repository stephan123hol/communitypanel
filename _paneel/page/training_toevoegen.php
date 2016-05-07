<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_trainingen'] == 1)
	{ 
////////////////	
?>
        <h1>Training toevoegen</h1>
        <hr />
<?php
		if(!isset($_POST['opslaan']))
		{
?>
            <form id="form1" method="post" action="">
            <table width="100%">
			<tr>
            <td width="75%"><input type="text" name="habbonaam" class="textbox" id="habbonaam" placeholder="Habbonaam getrainde" /></td>
            </tr>
			<tr>
            <td width="75%"><input type="text" name="training" class="textbox" id="training" placeholder="Training" /></td>
            </tr>
            <tr>
			<td width="75%"><input type="radio" name="gehaald" id="gehaald" value="1" /> <strong><font color="#3E91D9">Ja</font></strong></td>
			</tr>
            <tr>
			<td width="75%"><input type="radio" name="gehaald" id="gehaald" value="0" /> <strong><font color="#3E91D9">Nee</font></strong></td>
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
			if(empty($_POST['habbonaam']) or empty($_POST['training']) or ($_POST['gehaald'] == "")) 
			{
				echo "Niet alle velden zijn ingevuld.";
			}
			else
			{
				$training = mysql_real_escape_string(htmlspecialchars($_POST["training"]));
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
				$gehaald = mysql_real_escape_string(htmlspecialchars($_POST["gehaald"]));
				
				$sql_medewerker_check = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' AND (habbonaam, rang_op) IN (SELECT habbonaam, max(rang_op) FROM paneel_rangverandering GROUP BY habbonaam) ORDER BY habbonaam ASC" );
				$row = mysql_fetch_assoc($sql_medewerker_check);
				if($row['rang_nieuw'] == 0)
				{
				echo 'De opgegeven habbonaam komt niet voor in het personeelsbestand.';
				}
				else
				{
					mysql_query("INSERT INTO `paneel_trainingen` (`habbonaam`, `training`, `gehaald`, `door`, `datum`) VALUES ('".$habbonaam."', '".$training."', '".$gehaald."', '".$_SESSION['habbonaam']."', NOW())");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een training toegevoegd.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0; URL=/trainingen">';
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
