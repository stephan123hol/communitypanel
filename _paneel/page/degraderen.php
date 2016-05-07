<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_degradatie'] == 1)
	{ 
?>
        <h1>Degradatie toevoegen</h1>
        <hr />

<?php 
		if(!isset($_REQUEST['user']))
		{
?>
            Voor we gaan degraderen, pakken we eerst even het dossier van de betreffende Habbo er bij. Om welke Habbo gaat het?<br /><br />
            <form id="form1" method="post" action="">
            <input type="text" class="textbox" name="user" id="habbonaam" value="" /><br />
            <input type="submit" class="submit" name="zoek" id="zoek" value="Zoek">
            </form>

<?php
		}
		else if(isset($_REQUEST['user']))
		{
			if(empty($_REQUEST['user']))
			{
				echo 'Geen gebruiker ingevoerd!';
			}
			else
			{
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_REQUEST['user']));
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
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td width="70px"><img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $habbonaam; ?>&head_direction=3&direction=2&gesture=sml&img_format=gif" /></td>
                	<td width="180px" style="font-weight:bold;">Habbonaam:<br /><br />
Habbo missie:<br /><br />
Laatst ingelogd op Habbo:
					</td>
                	<td><?= $habbonaam ?><br />
<?php
			$habbourl = "http://www.habbo.nl/habblet/habbosearchcontent?searchString=".$habbonaam.""; 
			$data = file_get_contents($habbourl);
			$explode = explode("<div class=\"item\">",$data);
			$readend = explode("</div>", $explode[1]);
					
			$motto1 = str_replace('<b>', '', $readend[0]);
			$motto1 = str_replace('</b>', '', $motto1);
			$motto1 = str_replace('<br/>', '', $motto1);
			$motto1 = str_ireplace($habbonaam, '', $motto1);
					
			$motto_new = $motto1. $motto1[1];
			$motto_new = trim($motto_new);
			echo $motto_new;
?><br /><br />
<?php
			$habbourl = "http://www.habbo.nl/habblet/habbosearchcontent?searchString=".$habbonaam.""; 
			$data = file_get_contents($habbourl);
	        $explode = explode("<div class=\"lastlogin\">",$data);
	        $readend = explode("</div>", $explode[1]);
					
			$print = $readend[0];
	        echo substr($print, 48); 
			?>
					</td>
				</tr>
      		</table>
<br />
<div style="margin-bottom:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:#EEEEEE; padding:5px; ">
            <?php
					if ($num_medewerker != 0)
					{
						$sql_rangbijlvl = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level='".$medewerker['rang_nieuw']."' ");
						$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
						echo '<b>Huidige rang:</b> '.$rangbijlvl['rang_naam'].'<br />'; 
 ?>
             <b>Rang sinds:</b> <?= $medewerker['rang_op'] ?><br />
             <br />
            <b>Nieuwe rang:</b>
<?php 
 
						if ( ($promovatiegrens['degraderen_tot'] >= $rangbijlvl['rang_level']))
						{ 
?>
            <select name="promo" >
<?php            
							$tussen_huidig = $rangbijlvl['rang_level'] - 1;
							$sql_rangenlijst_promogrens = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level BETWEEN 1 AND ".$tussen_huidig." ORDER BY rang_level ASC ");
							while ($rangenlijst_Promo = mysql_fetch_assoc($sql_rangenlijst_promogrens))
							{
								echo '<option value="'.$rangenlijst_Promo['rang_level'].'">'.$rangenlijst_Promo['rang_naam'].'</option>';
							}
?>		</select>
            <br />
            <b>Reden:</b> <input type="text" class="textbox" name="reden" id="reden" value="" /><br />
            <input type="hidden" name="habbonaam" id="habbonaam" value="<?= $habbonaam ?>" />
            <input type="submit" class="submit" name="opslaan" id="opslaan" value="Opslaan">
<?php 
						} 
						else 
						{
							echo 'De rang van deze medewerker valt buiten jouw degradatiegrens'; 
						} 
					}
					else
					{
						echo 'Deze Habbonaam komt niet voor in het personeelsbestand. Probeer het opnieuw.';
					}
					
				}
			}
			echo'</div>';
		}
		
		
		
				if(isset($_POST['opslaan']))
				{
					$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
					$reden = mysql_real_escape_string(htmlspecialchars($_POST["reden"]));
					$rang_nieuw = mysql_real_escape_string(htmlspecialchars($_POST["promo"]));
					
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
						$sql_rangrestricties = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rang_nieuw."' ");
						if (mysql_num_rows($sql_rangrestricties) != 0)
						{
							$sql_promoveerder = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$_SESSION['habbonaam']."' ORDER BY rang_op DESC" );
							$promoveerder = mysql_fetch_assoc($sql_promoveerder);
							$sql_promovatiegrens = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$promoveerder['rang_nieuw'] ."' ");
							$promovatiegrens = mysql_fetch_assoc($sql_promovatiegrens);
							if ( ($promovatiegrens['promoveren_tot'] >= $rang_nieuw) && ($rang_oud > $rang_nieuw))
							{ 
						$rangrestricties = mysql_fetch_assoc($sql_rangrestricties);
						if($rangrestricties['promoveren_tot'] != 0) { $rangrestricties_promoveren = 1; } else { $rangrestricties_promoveren = 0; }
						if($rangrestricties['ontslaan_tot'] != 0) { $rangrestricties_ontslaan = 1; } else { $rangrestricties_ontslaan = 0; }
						if($rangrestricties['degraderen_tot'] != 0) { $rangrestricties_degraderen = 1; } else { $rangrestricties_degraderen = 0; }
						if($rangrestricties['warn'] != 0) { $rangrestricties_warn = 1; } else { $rangrestricties_warn = 0; }
						if($rangrestricties['trainingen'] != 0) { $rangrestricties_trainingen = 1; } else { $rangrestricties_trainingen = 0; }
						
						$sql_personeelcontrole = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
						$personeelcontrole = mysql_fetch_assoc($sql_personeelcontrole);
						$num_personeelcontrole = mysql_num_rows($sql_personeelcontrole);
						if($num_personeelcontrole == 0)
						{
							if($rangrestricties_degraderen == 1 or $rangrestricties_ontslaan == 1 or $rangrestricties_promoveren == 1 or $rangrestricties_warn == 1 or $rangrestricties_trainingen == 1)
							{
								mysql_query("INSERT INTO `paneel_personeel` (`habbonaam`, `datum`, `toegang_promotie`, `toegang_degradatie`, `toegang_ontslag`, `toegang_warn`, `toegang_trainingen`) VALUES ('".$habbonaam."', NOW(), '".$rangrestricties_promoveren."', '".$rangrestricties_degraderen."', '".$rangrestricties_ontslaan."', '".$rangrestricties_warn."', '".$rangrestricties_trainingen."' )");
							}
						}
						else if($num_personeelcontrole == 1)
						{
							if($rangrestricties_degraderen == 0 && $rangrestricties_ontslaan == 0 && $rangrestricties_promoveren == 0 && $rangrestricties_warn == 0 && $rangrestricties_trainingen == 0)
							{
								mysql_query(" DELETE FROM paneel_personeel WHERE habbonaam='".$habbonaam."' ");
							}
							else
							{
								mysql_query(" UPDATE paneel_personeel SET  toegang_trainingen='".$rangrestricties_trainingen."', toegang_warn='".$rangrestricties_warn."', toegang_promotie='".$rangrestricties_promoveren."', toegang_degradatie='".$rangrestricties_degraderen."', toegang_ontslag='".$rangrestricties_ontslaan."', datum=NOW() WHERE habbonaam='".$habbonaam."' ");
							}
							
						}
						
					mysql_query("INSERT INTO `paneel_rangverandering` (`habbonaam`, `rang_oud`, `rang_nieuw`, `rang_door`, `rang_op`, `rang_soort`, `reden`) VALUES ('".$habbonaam."', '".$rang_oud."', '".$rang_nieuw."', '".$_SESSION['habbonaam']."', NOW(), 'Degradatie', '".$reden."')");
					
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft ".$habbonaam." een degradatie gegeven.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							}
				}
					echo '<META http-equiv="refresh" content="0; URL=/degraderen">';
				}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>