<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_ontslag'] == 1)
	{ 
?>
        <h1>Ontslag toevoegen</h1>
        <hr />

<?php 
		if(!isset($_POST['zoek']))
		{
?>
            Voor we gaan ontslaan, pakken we eerst even het dossier van de betreffende Habbo er bij. Om welke Habbo gaat het?<br /><br />
            <form id="form1" method="post" action="">
            <input type="text" class="textbox" name="habbonaam" id="habbonaam" value="" /><br />
            <input type="submit" class="submit" name="zoek" id="zoek" value="Zoek">
            </form>

<?php
		}
		else if(isset($_POST['zoek']))
		{
			if(empty($_POST['habbonaam']))
			{
				echo 'Je moet wel een Habbonaam opgeven slimmerik!';
			}
			else
			{
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
				$habbonaam = str_replace(' ', '', $habbonaam);
				$habbonaam = str_replace(' ', '', $habbonaam);
				$sql_medewerker = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' ORDER BY rang_op DESC" );
				$medewerker = mysql_fetch_assoc($sql_medewerker);
				$num_medewerker = mysql_num_rows($sql_medewerker);
				
				if(!isset($_POST['opslaan']))
				{
					$sql_promoveerder = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$_SESSION['habbonaam']."' ORDER BY rang_op DESC" );
					$promoveerder = mysql_fetch_assoc($sql_promoveerder);
					$sql_promovatiegrens = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$promoveerder['rang_nieuw'] ."' ");
					$promovatiegrens = mysql_fetch_assoc($sql_promovatiegrens);
?>
                
            <form id="form2" method="post" action="">
            <b>Habbonaam:</b> <?= $habbonaam ?><br />
            <?php
					if ($num_medewerker != 0)
					{
						$sql_rangbijlvl = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level='".$medewerker['rang_nieuw']."' ");
						$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
						echo '<b>Huidige rang:</b> '.$rangbijlvl['rang_naam'].'<br />'; 
 ?>
             <b>Rang sinds:</b> <?= $medewerker['rang_op'] ?><br />
<?php 
						if ($promovatiegrens['ontslaan_tot'] >= $rangbijlvl['rang_level']
							&& (!in_array(strtolower($habbonaam), $bedrijf_beheerder)
							|| !in_array(strtolower($habbonaam), $hfc_beheerder) )
							)
						{ 
?>
           
            <br /><br />
			<i>Let op! Als je iemand ontslaat, zal je ook al zijn toegang tot gesloten delen van het forum afnemen. Als je zeker weet dat je deze Habbo moet ontslaan, klik dan op "Ontslaan".</i><br />
            <b>Reden:</b> <input type="text" class="textbox" name="reden" id="reden" value="" /><br />
            <input type="hidden" name="habbonaam" id="habbonaam" value="<?= $habbonaam ?>" />
            <input type="submit" class="submit" name="opslaan" id="opslaan" value="Ontslaan">
<?php 
						} 
						else 
						{
							echo 'De rang van deze medewerker valt buiten jouw ontslag grens'; 
						} 
					}
					else
					{
						echo 'Deze Habbonaam komt niet voor in het personeelsbestand. Probeer het opnieuw.';
					}
				}
			}
		}
		
		
				if(isset($_POST['opslaan']))
				{
					$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
					$reden = mysql_real_escape_string(htmlspecialchars($_POST["reden"]));
					
					$sql_medewerker = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' ORDER BY rang_op DESC" );
					$medewerker = mysql_fetch_assoc($sql_medewerker);
					$num_medewerker = mysql_num_rows($sql_medewerker);
					
					if ($num_medewerker != 0)
					{
						$rang_oud = $medewerker['rang_nieuw'];
					}
					else
					{
						$rang_oud = 0;
					}
						$sql_personeelcontrole = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
						$personeelcontrole = mysql_fetch_assoc($sql_personeelcontrole);
						$num_personeelcontrole = mysql_num_rows($sql_personeelcontrole);
						if($num_personeelcontrole == 1)
						{
							mysql_query(" DELETE FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
						}
						
						$HipChatUserID = $user->getUserVar($habbonaam, 'hipchat_user_id');
						
						if (!is_null($HipChatUserID))
						{
							$user->deleteHipChatUser($HipChatUserID);
						}
						
						mysql_query("INSERT INTO `paneel_rangverandering` (`habbonaam`, `rang_oud`, `rang_nieuw`, `rang_door`, `rang_op`, `rang_soort`, `reden`) VALUES ('".$habbonaam."', '".$rang_oud."', '1', '".$_SESSION['habbonaam']."', NOW(), 'Ontslag', '".$reden."')");
						mysql_query("DELETE FROM `paneel_FLeden` WHERE habbonaam = '" . $habbonaam . "'");
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `user_id`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".$_SESSION['ID']."', '".time()."', 'heeft ".$habbonaam." ontslag gegeven.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						echo '<META http-equiv="refresh" content="0; URL=/ontslaan">';
				}
				
				
				
						
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>