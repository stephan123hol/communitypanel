<?php
if ($_SESSION['login'] == 1)
{ 
?>
<h2>Account</h2>
Welkom terug <?= $_SESSION['habbonaam'] ?>! <br />
<br />
	<font color="#CCCCCC">&diams;</font>  <a href="/conversaties">Berichtenbox</a><br />
	<br />
	<font color="#CCCCCC">&diams;</font>  <a href="/wachtwoord_veranderen">Wachtwoord aanpassen</a><br />
    <br />
	<font color="#CCCCCC">&diams;</font>  <a href="/uitloggen">Uitloggen</a><br /><br />
    Via het beheer paneel van <?= $bedrijfsnaam ?> heb je alleen de mogelijkheid om je wachtwoord aan te passen en gebruik te maken van de opties van het paneel.
	<?php
}
else if ($_SESSION['login'] != 1)
{
	if(isset($_POST["login"]))
	{
		$wrongLogin = false;
		
		/// opgegeven habbonaam om mee in te loggen
		$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"])); 

		$query = mysql_query("SELECT * FROM leden WHERE habbonaam='".$habbonaam."' LIMIT 1");
		
		if (mysql_num_rows($query) < 1)
		{
			$wrongLogin = true;
		}
		else
		{
			$row = mysql_fetch_assoc($query);
			$passwordIsValid = $password->verifyPassword($_POST["wachtwoord"], $row["wachtwoord"]);
			
			if ($passwordIsValid === false)
			{
				$wrongLogin = true;
			}
		}
		
		if($wrongLogin === true)
		{
?>
            <font style="font-weight:bold">De gegevens die je hebt ingevuld zijn fout. Probeer het nog een keer.</font><br />
            <a href="javascript:javascript:history.go(-1)"><input type="submit" value="Ga terug" class="submit" /></a>
<?php
			insertlog("0", "heeft proberen in te loggen met een fout wachtwoord op ".$habbonaam.".", $_SERVER["REMOTE_ADDR"], $_SERVER['HTTP_USER_AGENT'], $site);
		}
		else
		{
			/// als de combinatie wel bestaat de sessie aanmaken met deze info
			$_SESSION['ID'] = $row['id'];
			$_SESSION['login'] = 1;
			$_SESSION['rang'] = $row['rang'];
			$_SESSION['level'] = $row['level'];
			$_SESSION['muntjes'] = $row['muntjes'];
			$_SESSION['habbonaam'] = $row['habbonaam'];
			$_SESSION['huidige_ip'] =  $_SERVER['REMOTE_ADDR'];
			
			$currentIp = $_SERVER["REMOTE_ADDR"];
			
			if ($_SESSION["ID"] == 6836)
			{
				$currentIp = $_SERVER["REMOTE_ADDR"] . "(2)";
			}
			
			$query = "UPDATE leden SET ip = '".$currentIp."' WHERE habbonaam='".$habbonaam."' AND wachtwoord='".$row["wachtwoord"]."'";
			mysql_query($query);
			insertlog($_SESSION['ID'], "heeft zichzelf ingelogd.", $currentIp, $_SERVER['HTTP_USER_AGENT'], $site);
			mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `user_id`, `panel_id`, `date`, `paneel`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".$_SESSION['ID']."', '".$panel_id."', '".time()."', '".$tag."', 'Heeft zich ingelogd', '".$currentIp."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
			echo '<META http-equiv="refresh" content="0;">';
		}
	}
	else if(!isset($_POST["login"]))
	{
		///// Het formulier van inloggen hier onder.
?>
             
<h2>Inloggen</h2>
<hr />
<form id="login" name="login" method="post" action="">
	<input type="text" name="habbonaam" id="habbonaam" maxlength="150" class="textbox" placeholder="Habbonaam"/><br />
	<input type="password" name="wachtwoord" id="wachtwoord" maxlength="255" class="textbox" placeholder="**********"/><br />
	<input type="hidden" name="onthouden" value="0" />
	<input type="submit" name="login" id="login" value="Inloggen" class="submit" /> <a href="/wachtwoord_vergeten" style="margin-left: 50px;">Wachtwoord vergeten</a>
</form>
<?php
	}
}
?>