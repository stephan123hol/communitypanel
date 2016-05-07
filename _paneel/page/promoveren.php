<?php
	$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
	$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
	if ($PaneelLevel['toegang_promotie'] == 1)
	{ 
?>
        <h1>Promotie toevoegen</h1>
        <hr />

<?php 
		if(!isset($_REQUEST['user']))
		{
?>
            Voor we gaan promoveren, pakken we eerst even het dossier van de betreffende Habbo er bij. Om welke Habbo gaat het?<br /><br />
            <form id="form1" method="post" action="">
            <input type="text" class="textbox" name="user" id="habbonaam" value="" />
            <input type="submit" class="submit" name="zoek" id="zoek" value="Zoek" />
            </form>
			<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/promoveren.html"; ?>
			
<?php
		}
		else if(isset($_REQUEST["user"]))
		{
			if(empty($_REQUEST['user']))
			{
				echo 'Geen gebruikersnaam opgegeven!';
			}
			else
			{
				$habbonaam = mysql_real_escape_string(htmlspecialchars($_REQUEST["user"]));
				$habbonaam = str_replace(' ', '', $habbonaam);
				$habbonaam = str_replace(' ', '', $habbonaam);

				$sql_medewerker = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' ORDER BY rang_op DESC LIMIT 1" );
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
					<td width="70px">
						<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $habbonaam; ?>&head_direction=3&direction=2&gesture=sml&img_format=gif" />
					</td>
                	<td width="180px" style="font-weight:bold;">
						<br />
						Habbonaam:<br />
						<br />
						Habbo status:<br />
						<br />
						Laatst ingelogd op Habbo:
					</td>
                	<td>
						<br />
						<?= $habbonaam ?><br />
						<br />
						<?= $user->getDataFromHabboAPI($habbonaam, "motto"); ?><br />
						<br />
						Tijdelijk niet beschikbaar
					</td>
				</tr>
      		</table>
            <br />
            <br />
            <div style="margin-bottom:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:#EEEEEE; padding:5px; ">
            <?php
					if ($num_medewerker != 0)
					{
						$sql_rangbijlvl = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level='".$medewerker['rang_nieuw']."' ");
						$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
			?>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td style="font-weight:bold;">Huidige rang:</td>
                	<td><?= $rangbijlvl['rang_naam'] ?></td>
				</tr>
            	<tr>
                	<td style="font-weight:bold;">Rang sinds:<br /><br /></td>
                	<td valign="top"><?= $medewerker['rang_op'] ?></td>
				</tr>
            	<tr>
                	<td style="font-weight:bold;">Nieuwe rang:</td>
                	<td>
<?php 
						if ($promovatiegrens['promoveren_tot'] > $rangbijlvl['rang_level'])
						{
?>
            <select name="promo" >
<?php            
							$tussen_huidig = $rangbijlvl['rang_level'] + 1;
							$sql_rangenlijst_promogrens = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level BETWEEN ".$tussen_huidig." AND ".$promovatiegrens['promoveren_tot']." ORDER BY rang_level ASC ");
							while ($rangenlijst_Promo = mysql_fetch_assoc($sql_rangenlijst_promogrens))
							{
								echo '<option value="'.$rangenlijst_Promo['rang_level'].'">'.$rangenlijst_Promo['rang_naam'].'</option>';
							}
?>		</select>
					</td>
				</tr>
            	<tr>
                	<td style="font-weight:bold;">Reden:</td>
                	<td><input type="text" class="textbox" name="reden" id="reden" value="" placeholder="Geen verplicht veld!" /></td>
				</tr>
            	<tr>
                	<td style="font-weight:bold;"><input type="hidden" name="habbonaam" id="habbonaam" value="<?= $habbonaam ?>" /></td>
                	<td><input type="submit" class="submit" name="opslaan" id="opslaan" value="Opslaan"></td>
				</tr>
      		</table>
<?php 
						} 
						else 
						{
							echo 'De rang van deze medewerker valt buiten jouw promovatiegrens.</td></tr></table>'; 
						} 
					}
					else
					{
 ?>
            <br />
		<i style="font-size:10px; color:#999;">Deze Habbonaam komt bij ons nog niet voor in het personeelsbestand. Ga dus alleen verder als het een nieuw personeelslid is! Als de opgegeven Habbonaam al een rang hoort te hebben, controleer dan of je de Habbonaam juist hebt getypt.</i> <br /><br />
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td style="font-weight:bold;">Nieuwe rang:</td>
                	<td>
<?php 
						if ($promovatiegrens['promoveren_tot'] > 0)
						{ 
?>
            <select name="promo" >
            
<?php            
							$sql_rangenlijst_promogrens = mysql_query("SELECT rang_naam, rang_level FROM paneel_rangniveau WHERE rang_level BETWEEN 0 AND ".$promovatiegrens['promoveren_tot']." ORDER BY rang_level ASC ");
							while ($rangenlijst_Promo = mysql_fetch_assoc($sql_rangenlijst_promogrens))
							{
								echo '<option value="'.$rangenlijst_Promo['rang_level'].'">'.$rangenlijst_Promo['rang_naam'].'</option>';
							}
?>			
			</select>
                   </td> 
                </tr>    
            	<tr>
                	<td style="font-weight:bold;">Reden:</td>
                	<td><input type="text" class="textbox" name="reden" id="reden" value="" placeholder="Geen verplicht veld!"  /></td>
				</tr>
            	<tr>
                	<td style="font-weight:bold;"><input type="hidden" name="habbonaam" id="habbonaam" value="<?= $habbonaam ?>" /></td>
                	<td><input type="submit" class="submit" name="opslaan" id="opslaan" value="Opslaan"></td>
				</tr>            
<?php 
						} 
						else 
						{
							echo 'De rang van deze medewerker valt buiten jouw promovatiegrens</td></tr>'; 
						} 
?>            
			</table>				
            </form>
            <?php
					}
					echo '</div>';
				}
			}
		}
		
		
				if(isset($_POST['opslaan']))
				{
					$habbonaam = mysql_real_escape_string(htmlspecialchars($_POST["habbonaam"]));
					$reden = mysql_real_escape_string(htmlspecialchars($_POST["reden"]));
					$rang_nieuw = mysql_real_escape_string(htmlspecialchars($_POST["promo"]));
	
				$sql_medewerker = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$habbonaam."' ORDER BY rang_op DESC LIMIT 1" );
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
							if ( ($promovatiegrens['promoveren_tot'] >= $rang_nieuw) && ($rang_oud < $rang_nieuw))
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
							mysql_query(" UPDATE paneel_personeel SET toegang_trainingen='".$rangrestricties_trainingen."', toegang_warn='".$rangrestricties_warn."', toegang_promotie='".$rangrestricties_promoveren."', toegang_degradatie='".$rangrestricties_degraderen."', toegang_ontslag='".$rangrestricties_ontslaan."', datum=NOW() WHERE habbonaam='".$habbonaam."' ");
							}
							
						}
						
					mysql_query("INSERT INTO `paneel_rangverandering` (`habbonaam`, `rang_oud`, `rang_nieuw`, `rang_door`, `rang_op`, `rang_soort`, `reden`) VALUES ('".$habbonaam."', '".$rang_oud."', '".$rang_nieuw."', '".$_SESSION['habbonaam']."', NOW(), 'Promotie', '".$reden."')");
					
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft ".$habbonaam." een promotie gegeven.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					
						}
					
						}
				echo '<META http-equiv="refresh" content="0; URL=/promoveren">';
				}
				
	
	
	
				
				
						
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>
