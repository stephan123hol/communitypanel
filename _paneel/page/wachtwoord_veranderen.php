<?php 
if($_SESSION['login'] == 1)
{
	// Je met ingelogd zijn anders is het "wachtwoord vergeten".
?>
	<img src="http://www.panelify.com/_paneel/assets/afbeelding/icon-aanpassen.png" align="left"  /> 
	<h1> Wachtwoord veranderen</h1>
	<hr  />
	Als je je wachtwoord wilt veranderen vul je onderstaande velden in. <br />Zorg dat je missie <b>IM-Reset</b> is voordat je op 'Verander' klikt. Vergeet ook niet na het aanpassen je missie weer terug te veranderen of leeg te maken ;)
	<br /><br />

	<form name="form1" method="post" action="">
		<table width="441" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="150"><strong>Huidige wachtwoord:</strong></td>
				<td width="200"><label for="owachtwoord"></label>
				<input  autocomplete="off" type="password" name="owachtwoord" id="owachtwoord" class="textbox"></td>
			</tr>
			<tr>
				<td height="32"><strong>Nieuw wachtwoord:</strong></td>
				<td><input autocomplete="off" type="password" name="wachtwoord" class="textbox" id="wachtwoord"></td>
			</tr>
			<tr>
				<td><strong>Nieuw wachtwoord:</strong></td>
				<td><input autocomplete="off" style="margin-top:1px;" type="password" name="wachtwoord2" id="wachtwoord2" class="textbox" placeholder="Herhaal wachtwoord"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" id="submit" class="submit" value="Verander"></td>
			</tr>
		</table>
	</form>
<?php
	if(isset($_POST["submit"]))
	{
		$wrongLogin = false;

		$query = mysql_query("SELECT wachtwoord FROM leden WHERE habbonaam='".mysql_real_escape_string($_SESSION["habbonaam"])."' LIMIT 1");
		
		if (mysql_num_rows($query) < 1)
		{
			$wrongLogin = true;
		}
		else
		{
			$row = mysql_fetch_assoc($query);
			$passwordIsValid = $password->verifyPassword($_POST["owachtwoord"], $row["wachtwoord"]);
			
			if ($passwordIsValid === false)
			{
				$wrongLogin = true;
			}
		}
		
		if ($wrongLogin === false)
		{
			$passwordCheck = $password->validatePassword($_POST["wachtwoord"], $_POST["wachtwoord2"]);
			
			if ($passwordCheck === true)
			{
				// wat de habbonaam is en missie moet zijn
				$mottocode = $tag.'-Reset';

				$mottoCorrect = $user->checkMotto($_SESSION['habbonaam'], $mottocode);
				
				if($mottoCorrect === true)
				{
					/// het nieuwe wachtwoord in $hash zetten
					$hash = $password->hashPassword($_POST["wachtwoord"]);
					
					// Nieuwe ww opslaan in de database
					mysql_query("UPDATE leden SET wachtwoord='".mysql_real_escape_string($hash)."' WHERE id='".$_SESSION['ID']."'");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft zijn/haar wachtwoord aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo "Je hebt met succes je wachtwoord aangepast :D";
				}
				else
				{
					echo "Je habbo-missie is niet correct! Je missie is nu: <br>".$motto_new."";
				}
			}
			else
			{
				echo "<strong>Er is iets fout gegaan:</strong><br />";
				
				foreach($passwordCheck as $key => $value)
				{
					echo "- " . $value . "<br />";
				}
			}
		}
		else
		{
			mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft zijn/haar wachtwoord verkeerd ingevuld.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
			echo "Het huidige wachtwoord klopt niet!";
		}
	}
} 
?>