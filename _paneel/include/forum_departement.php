<?php
	if($_SESSION['login'] == 1)
	{
		$sql_leden = mysql_query("SELECT level, departement FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
		$leden = mysql_fetch_assoc($sql_leden);
		$num_leden = mysql_num_rows($sql_leden);
		
		if($num_leden == 0)
		{
			$level = 0;
		}
		else
		{
			$level = $leden['level'];
		}
		
		if ($userLevel > 1)
		{
			$level = 5;
		}
		else if ($userLevel == 1)
		{
			$level = 4;
		}
	}
	else
	{
		$level = 0;
	}

?>
<div onclick="location.href='<?= '/forum/'.rawurlencode($departement['naam']) ?>'" class="forum-topbar">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="padding-left:25px;"><?= $departement['naam'] ?></td>
								<td width="70" align="center" style="font-size:10px;">Topics:</td>
								<td width="70" align="center" style="font-size:10px;">Reacties:</td>
								<td width="140" align="center" style="font-size:10px;">Laatste bericht:</td>
                                <td width="20px" align="right" style="padding-right:10px;">
									<?php  
                                    if($departement['zichtbaarheid'] == 2) 
									{
										echo '
										<img src="/_paneel/assets/afbeelding/forum_icon_3.gif" alt="Dit is een besloten forum." /> 
										';
									}
                                     ?>
                                 </td>
								<td width="40px"><img src="<?= $departement['badge'] ?>" border="0" /></td>
							</tr>
						</table>
					</div>
<?php					
					$kleurrangchange = 0;
					if($_GET['departement'] == "")
					{
						$sql_categorie = mysql_query(" SELECT * FROM paneel_FCategorie WHERE prullenbak = '0' AND departement = '".$departement['id']."' ORDER BY `order` ASC, id ASC LIMIT 3 " );
					}
					 else if($_GET['departement'] != "" && $_GET['categorie'] == "")
					{
						$sql_categorie = mysql_query(" SELECT * FROM paneel_FCategorie WHERE prullenbak = '0' AND departement = '".$departement['id']."' ORDER BY `order` ASC, id ASC " );
					}
					
					while($categorie = mysql_fetch_assoc($sql_categorie))
					{
						if($kleurrangchange == 0)
						{
							 $rowcolor = '#FFFFFF';
							 $kleurrangchange--;
						}
						else
						{
							 $rowcolor = '#EEEEEE';
							 $kleurrangchange++;
						} 
																		
						if($categorie['toegang'] >= 2 || $categorie['toegang'] <= 1)
						{
							if($categorie['toegang'] <= $level)
							{
?>							
						
							<div onclick="location.href='<?= '/forum/'.rawurlencode($departement['naam']).'/'.rawurlencode($categorie['naam']) ?>'" style="height:60px; margin-top:5px; cursor: pointer; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #d6d6d6; " onmouseover="this.style.border = '1px solid #666666'" onmouseout="this.style.border = '1px solid #d6d6d6'">	
								<table width="100%" height="60px" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="50px" align="center"><img src="/_paneel/assets/afbeelding/forum_icon_7.gif" /> </td>
										<td>
											<b><?= $categorie['naam'] ?></b><br />
											<i style="word-wrap: break-word;"><?= $categorie['omschrijving'] ?></i> <br /> 
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
											<?= $forum->getTopicAmounts($categorie["id"]); ?>
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
											<?= $forum->getReactionAmounts($categorie["id"]); ?>
										</td>
										<td width="150px" align="center">
										<?php
										$sql_laatstereactie = mysql_query(" SELECT r.habbonaam, r.datum FROM paneel_FReactie AS r INNER JOIN paneel_FTopic AS t ON r.topic = t.id WHERE r.prullenbak = '0' AND t.prullenbak = '0' AND t.categorie = '".$categorie['id']."' ORDER BY r.datum DESC " );
										$laatstereactie = mysql_fetch_assoc($sql_laatstereactie);
										if (mysql_num_rows($sql_laatstereactie) != 0)
										{
										?>
										<a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $laatstereactie['habbonaam']); echo $mijn_dossier; ?>"><?= $laatstereactie['habbonaam'] ?></a><br />
										<i style="font-size:10px;"><?= datumrewrite($laatstereactie['datum']) ?> om <?= timerewrite($laatstereactie['datum']) ?> uur</i>
										</td>
										<td width="40px" align="right" style="padding-right:10px;">
										<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $laatstereactie['habbonaam'] ?>&head_direction=3&direction=4&action=sit&size=s&img_format=gif" />
										</td>
										<?php 
										} 
										else
										{
											echo '<i>Nog geen reacties</i></td><td width="40px" align="right" style="padding-right:10px;"></td>';
										}
										?>
									</tr>
								</table>
							</div>
	<?php                 
							}
						}elseif($categorie['toegang'] > 1 && $categorie['toegang'] < 2)
						{
							if($categorie['toegang'] == $level || $level >= 2)
							{
?>							
						
							<div onclick="location.href='<?= '/forum/'.rawurlencode($departement['naam']).'/'.rawurlencode($categorie['naam']) ?>'" style="height:60px; margin-top:5px; cursor: pointer; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #d6d6d6; " onmouseover="this.style.border = '1px solid #666666'" onmouseout="this.style.border = '1px solid #d6d6d6'">	
								<table width="100%" height="60px" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="50px" align="center"><img src="/_paneel/assets/afbeelding/forum_icon_7.gif" /> </td>
										<td>
											<b><?= $categorie['naam'] ?></b><br />
											<i style="word-wrap: break-word;"><?= $categorie['omschrijving'] ?></i> <br /> 
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
											<?= $forum->getTopicAmounts($categorie["id"]); ?>
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
											<?= $forum->getReactionAmounts($categorie["id"]); ?>
										</td>
										<td width="150px" align="center">
										<?php
										$sql_laatstereactie = mysql_query(" SELECT r.habbonaam, r.datum FROM paneel_FReactie AS r INNER JOIN paneel_FTopic AS t ON r.topic = t.id WHERE r.prullenbak = '0' AND t.prullenbak = '0' AND t.categorie = '".$categorie['id']."' ORDER BY r.datum DESC " );
										$laatstereactie = mysql_fetch_assoc($sql_laatstereactie);
										if (mysql_num_rows($sql_laatstereactie) != 0)
										{
										?>
										<a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $laatstereactie['habbonaam']); echo $mijn_dossier; ?>"><?= $laatstereactie['habbonaam'] ?></a><br />
										<i style="font-size:10px;"><?= datumrewrite($laatstereactie['datum']) ?> om <?= timerewrite($laatstereactie['datum']) ?> uur</i>
										</td>
										<td width="40px" align="right" style="padding-right:10px;">
										<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $laatstereactie['habbonaam'] ?>&head_direction=3&direction=4&action=sit&size=s&img_format=gif" />
										</td>
										<?php 
										} 
										else
										{
											echo '<i>Nog geen reacties</i></td><td width="40px" align="right" style="padding-right:10px;"></td>';
										}
										?>
									</tr>
								</table>
							</div>
	<?php                 
							}
						}
							
							
							
							
					}
					 if($_GET['departement'] == "")
					{
						$sql_categorie_knop = mysql_query(" SELECT * FROM paneel_FCategorie WHERE prullenbak = '0' AND departement = '".$departement['id']."' ORDER BY id ASC " );
						if(mysql_num_rows($sql_categorie_knop) > 3)
						{
							echo '<div align="right"><a href="/forum/'.rawurlencode($departement['naam']).'"><input type="submit" class="submit" value="Meer uit dit departement"></a></div>';	
						}
						else
						{
							echo '<br /><br />';
						}
					}
					
					
 //////////////////////////
 ////////////////////////// Opties begin
 //////////////////////////
if($_GET['departement'] != "" && $_GET['categorie'] == "")
{
	
	
 	if($_SESSION['login'] == 1)
	{	
		$sql_eigenlvl = mysql_query("SELECT * FROM paneel_FLevel WHERE  level = '".$level."' ");
		$eigenlvl = mysql_fetch_assoc($sql_eigenlvl);
		
/////////////////// Knopjes ////////////////////	
/////////////////// Knopjes ////////////////////	
/////////////////// Knopjes ////////////////////	
		if($eigenlvl['categorie_maken'] == 1)
		{
?>
<a style="text-decoration:none;" id="show_Categoriemaken" onclick="document.getElementById('spoiler_Categoriemaken').style.display=''; document.getElementById('close_Categoriemaken').style.display=''; document.getElementById('show_Categoriemaken').style.display='none'; document.getElementById('spoiler_departementleden').style.display='none';  document.getElementById('close_departementleden').style.display='none'; document.getElementById('show_departementleden').style.display=''; document.getElementById('spoiler_departementedit').style.display='none'; document.getElementById('close_departementedit').style.display='none'; document.getElementById('show_departementedit').style.display='';"><input type="submit" class="submit" value="Categorie aanmaken" style="float: right;"></a>

<a style="text-decoration:none; display:none;" id="close_Categoriemaken" onclick="document.getElementById('spoiler_Categoriemaken').style.display='none';  document.getElementById('close_Categoriemaken').style.display='none'; document.getElementById('show_Categoriemaken').style.display='';"><input type="submit" class="submit" value="Sluiten" style="float: right;"></a>
<?		
		}
		if($eigenlvl['Flidlevel_edit'] != 0 || $eigenlvl['Flidlevel_aanstelle'] != 0)
		{
?>
<a style="text-decoration:none;" id="show_departementleden" onclick="document.getElementById('spoiler_departementleden').style.display=''; document.getElementById('close_departementleden').style.display=''; document.getElementById('show_departementleden').style.display='none'; document.getElementById('spoiler_Categoriemaken').style.display='none'; document.getElementById('close_Categoriemaken').style.display='none'; document.getElementById('show_Categoriemaken').style.display=''; document.getElementById('spoiler_departementedit').style.display='none'; document.getElementById('close_departementedit').style.display='none'; document.getElementById('show_departementedit').style.display='';"><input type="submit" class="submit" value="Leden" style="float: right;"></a>

<a style="text-decoration:none; display:none;" id="close_departementleden" onclick="document.getElementById('spoiler_departementleden').style.display='none';  document.getElementById('close_departementleden').style.display='none'; document.getElementById('show_departementleden').style.display='';"><input type="submit" class="submit" value="Sluiten" style="float: right;"></a>
<?		
		}
		if($userLevel > 1)
		{
?>
<a style="text-decoration:none;" id="show_departementedit" onclick="document.getElementById('spoiler_departementedit').style.display=''; document.getElementById('close_departementedit').style.display=''; document.getElementById('show_departementedit').style.display='none'; document.getElementById('spoiler_Categoriemaken').style.display='none';  document.getElementById('close_Categoriemaken').style.display='none'; document.getElementById('show_Categoriemaken').style.display=''; document.getElementById('spoiler_departementleden').style.display='none';  document.getElementById('close_departementleden').style.display='none'; document.getElementById('show_departementleden').style.display='';"><input type="submit" class="submit" value="Departement aanpassen" style="float: right;"></a>

<a style="text-decoration:none; display:none;" id="close_departementedit" onclick="document.getElementById('spoiler_departementedit').style.display='none';  document.getElementById('close_departementedit').style.display='none'; document.getElementById('show_departementedit').style.display='';"><input type="submit" class="submit" value="Sluiten" style="float: right;"></a>

<?			
		}
		
		
echo '<div style="clear:both;"></div>';	

/////////////////// categorie aanmaken ////////////////////	
/////////////////// categorie aanmaken ////////////////////	
/////////////////// categorie aanmaken ////////////////////	
		if($eigenlvl['categorie_maken'] == 1)
		{
		$sql_laatstaangemaakt = mysql_query("SELECT * FROM paneel_FCategorie WHERE  habbonaam = '".$_SESSION['habbonaam']."' AND datum > DATE_SUB(NOW(), INTERVAL 10 SECOND) ");
		if(mysql_num_rows($sql_laatstaangemaakt) == 0)
		{
			if(!isset($_POST["categorie_verstuur"]))
			{
?>
<span id="spoiler_Categoriemaken" style="display:none;">
	<form id="formulier" method="post" action="" style="width:400px;">
		<table width="400px" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="150px" style="font-weight:bold;">Naam:</td>
				<td><input type="text" style="width:250px;" class="textbox" name="categorie_naam"></td>
         	</tr>
            <tr>
				<td width="150px" style="font-weight:bold;">Omschrijving:</td>
				<td >
					<textarea style="width:250px; height:50px;"  class="textbox" name="categorie_omschrijving"></textarea>
                </td>
          	</tr>  
			<tr>
				<td width="150px" style="font-weight:bold;">Kunnen zien vanaf:</td>
				<td >
            <select name="categorie_toegang" >
 <?php if ($departement['zichtbaarheid'] != 2 ) { ?>          
			<option value="0">Bezoeker</option>
<?php  
 }
			$sql_eigenlvlenlager = mysql_query("SELECT * FROM paneel_FLevel WHERE  level <= ".$level." ORDER BY level ASC ");
			while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
			{
					echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
			}
?>		</select>
                </td>
         	</tr>
			<tr>
				<td width="150px" style="font-weight:bold;">Topic aanmaken vanaf:</td>
				<td >
            <select name="categorie_topic" >
 <?php if ($departement['zichtbaarheid'] != 2 ) { ?>          
			<option value="0">Bezoeker</option>
<?php  
 }
			$sql_eigenlvlenlager = mysql_query("SELECT * FROM paneel_FLevel WHERE  level <= ".$level." ORDER BY level ASC ");
			while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
			{
					echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
			}
?>		</select>
                </td>
         	</tr>
         	<tr>   
                <td colspan="2" align="right">    
					<input type="submit" class="submit" name="categorie_verstuur" id="categorie_verstuur" value="Categorie aanmaken">
				</td>
			</tr>
		</table>
	</form>
</span>
		
<?php		
			}
			else if(isset($_POST["categorie_verstuur"]))
			{
				if($_POST['categorie_naam'] == '' or $_POST['categorie_omschrijving'] == '' ) 
				{
                 	echo '
						<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>Er is geen Categorie aangemaakt! Je hebt namelijk niet alle velden ingevuld.</td>
								<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
							</tr>
						</table>	
						</div>
					';
				}
				else  
				{
					
					$naam1 = str_replace("&", "en", $_POST["categorie_naam"]);
					$naam2 = mysql_real_escape_string(htmlspecialchars($naam1));
					$naam = substr($naam2, 0, 200);

					$sql_naamxxcheck = mysql_query("SELECT * FROM paneel_FCategorie WHERE naam = ".$naam."");
					$num_naamxxcheck = mysql_num_rows($sql_naamxxcheck);
					if($num_naamxxcheck != 0)
					{
                 	echo '
						<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>Er is geen Categorie aangemaakt! De naam is namelijk niet uniek.</td>
								<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
							</tr>
						</table>	
						</div>
					';
					}
					else
					{
					
					
						$omschrijving1 = mysql_real_escape_string(htmlspecialchars($_POST["categorie_omschrijving"]));
						$omschrijving = substr($omschrijving1, 0, 500);
		
						$toegangxx = mysql_real_escape_string(htmlspecialchars($_POST["categorie_toegang"]));
						$sql_toegangxxcheck = mysql_query("SELECT * FROM paneel_FLevel WHERE  level = ".$toegangxx."");
						$num_toegangxxcheck = mysql_num_rows($sql_toegangxxcheck);
						if($num_toegangxxcheck != 0)
						{
							if($toegangxx <= $level)
							{
								$toegang = $toegangxx;
							}
							else
							{
								if($departement['zichtbaarheid'] != 2)
								{
									$toegang = 0;
								}
								else
								{
									$toegang = 1;
								}
							}
						}
						else
						{
							if($departement['zichtbaarheid'] != 2)
							{
								$toegang = 0;
							}
							else
							{
								$toegang = 1;
							}
						}
						
						$topicxx = mysql_real_escape_string(htmlspecialchars($_POST["categorie_topic"]));
						
						$sql_topicxxcheck = mysql_query("SELECT * FROM paneel_FLevel WHERE  level = ".$topicxx."");
						$num_topicxxcheck = mysql_num_rows($sql_topicxxcheck);
						if($num_topicxxcheck != 0)
						{
							if($topicxx <= $level)
							{
								$topic = $topicxx;
							}
							else
							{
								if($departement['zichtbaarheid'] != 2)
								{
									$topic = 0;
								}
								else
								{
									$topic = 1;
								}
							}
						}
						else
						{
							if($departement['zichtbaarheid'] != 2)
							{
								$topic = 0;
							}
							else
							{
								$topic = 1;
							}
						}
	
						mysql_query("INSERT INTO `paneel_FCategorie` (`naam`, `omschrijving`, `toegang`, `topic_maken`, `habbonaam`, `departement`, `datum`) VALUES ('".$naam."', '".$omschrijving."', '".$toegang."', '".$topic."', '".$_SESSION['habbonaam']."', '".$departement['id']."', NOW())");
						
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een categorie aangemaakt', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					}
				}
				
				
			}
		}
			
			
		}
/////////////////// categorie aanmaken ////////////////////	
/////////////////// categorie aanmaken ////////////////////	
/////////////////// categorie aanmaken ////////////////////	
		if(!isset($_POST["departement_verstuur"]))
		{
?>

<span id="spoiler_departementedit" style="display:none;">
	<form id="formulier" method="post" action="">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="250px" style="font-weight:bold;">Naam:</td>
				<td><input type="text" style="width:600px;" class="textbox" name="departement_naam" value="<?= $departement['naam'] ?>"></td>
         	</tr>
			<tr>
				<td width="250px" style="font-weight:bold;">Zichtbaarheid:</td>
				<td >
            <select name="departement_zichtbaarheid" >
				<option value="0" selected="selected">Openbaar (ook uitgelogde bezoekers)</option>
				<option value="1">Besloten (alleen ingelogde bezoekers)</option>
				<option value="2">Exclusief (alleen toegewezen leden)</option>
			</select>
                </td>
         	</tr>
			<tr>
				<td width="250px" style="font-weight:bold;">Departement Barhon:</td>
				<td>
					<select name="departement_wissen" >
						<option value="0" selected="selected">Niet Barhon</option>
						<option value="1">Wel Barhon</option>
					</select>
                </td>
         	</tr>
			<tr>
				<td width="250px" style="font-weight:bold;">Badge link:<br />
                 <i style="color:#CCC; font-weight:normal;">Te vinden in een groeps home widget</i></td>
				<td><input type="text" style="width:600px;" class="textbox" name="departement_badge" value="<?= $departement['badge'] ?>"></td>
         	</tr>
         	<tr>   
                <td colspan="2">    
					<input type="submit" class="submit" name="departement_verstuur" id="departement_verstuur" value="Departement aanpassen">
				</td>
			</tr>
		</table>
	</form>
</span>
		
<?php		
			}
			else if(isset($_POST["departement_verstuur"]))
			{
				if($_POST['departement_naam'] == '' or $_POST['departement_badge'] == '' ) 
				{
                 	echo '
						<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>Er is geen Categorie aangemaakt! Je hebt namelijk niet alle velden ingevuld.</td>
								<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
							</tr>
						</table>	
						</div>
					';
				}
				else  
				{
					$naam1 = str_replace("&", "en", $_POST["departement_naam"]);
					$naam2 = mysql_real_escape_string(htmlspecialchars($naam1));
					$naam = substr($naam2, 0, 75);
					
					$sql_naamxxcheck = mysql_query("SELECT `id` FROM paneel_FDepartement WHERE naam = '".$naam."' AND id = '".$departement['id']."'");
					$num_naamxxcheck = mysql_num_rows($sql_naamxxcheck);
					if($num_naamxxcheck != 0 && $departement['naam'] != $_POST["departement_naam"])
					{
						echo '
							<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
							<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>Er is geen Departement aangemaakt! De naam is namelijk niet uniek.</td>
									<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
								</tr>
							</table>	
							</div>
						';
					}
						else
						{
						
						$badge = mysql_real_escape_string(htmlspecialchars($_POST["departement_badge"]));
						
						$zichtbaarheidxx = mysql_real_escape_string(htmlspecialchars($_POST["departement_zichtbaarheid"]));
						if($zichtbaarheidxx > 2)
						{
							$zichtbaarheid = 2;
						}
						else if ($zichtbaarheidxx < 0 )
						{
							$zichtbaarheid = 0;
						}
						else if ($zichtbaarheidxx == 1 || $zichtbaarheidxx == 2 || $zichtbaarheidxx == 0)
						{
							$zichtbaarheid = $zichtbaarheidxx;
						}
						else
						{
							$zichtbaarheid = 0;
						}
						
						
						if($_POST["departement_wissen"] == 0)
						{	
							mysql_query("UPDATE paneel_FDepartement SET badge='".$badge."', zichtbaarheid='".$zichtbaarheid."', naam='".$naam."', datum=NOW() WHERE id='".$departement['id']."'");
							
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een departement aangepast', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; url=/forum">';
						}
						else if($_POST["departement_wissen"] == 1)
						{	
							mysql_query("UPDATE paneel_FDepartement SET prullenbak='1', datum=NOW() WHERE id='".$departement['id']."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een departement gewist', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; url=/forum">';
						}
						
												
					}
				}
			}	
/////////////////// Departement leden ////////////////////	
/////////////////// Departement leden ////////////////////	
/////////////////// Departement leden ////////////////////	
		if($eigenlvl['Flidlevel_edit'] != 0 || $eigenlvl['Flidlevel_aanstelle'] != 0)
		{
			$sql_leden = mysql_query("SELECT * FROM paneel_FLeden WHERE departement = '".$departement['id']."' ORDER BY level DESC, habbonaam ASC ");
?>
<span id="spoiler_departementleden" style="display:none;">
<?php
while($leden = mysql_fetch_assoc($sql_leden))
{
	if($kleurrangchange == 0)
	{
		 $rowcolor = '#FFFFFF';
		 $kleurrangchange--;
	}
	else
	{
		 $rowcolor = '#EEEEEE';
		 $kleurrangchange++;
	} 
	$sql_rangbijlvl = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$leden['level']."' ORDER BY id DESC LIMIT 1");
	$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
?>	
<div style="height:auto; width:400px; margin-top:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #d6d6d6; " onmouseover="this.style.border = '1px solid #666666'" onmouseout="this.style.border = '1px solid #d6d6d6'">	
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td width="120px"><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $leden['habbonaam']); echo $mijn_dossier; ?>"><?= $leden['habbonaam'] ?></a></td>
         <td width="120px"><?= $rangbijlvl['naamlevel'] ?></td>
         <td align="center">
		 <?php 
		 if(($eigenlvl['Flidlevel_edit'] != 0 && $leden['level'] <= $eigenlvl['Flidlevel_edit']) || $userLevel > 1)
		 {
			 if(!isset($_POST["lid_wis"])){
				echo '<form id="formulier" method="post" action=""><input type="hidden" value="'.$leden['habbonaam'].'" name="habbonaamwis" /><input type="submit" class="submit" name="lid_wis" id="lid_wis" value="Barhon"></form> ';
			 }
			 else if(isset($_POST["lid_wis"]) && $_POST['habbonaamwis'] != ""){
				$wishabbonaam = mysql_real_escape_string(htmlspecialchars($_POST['habbonaamwis']));
				$sql_wishabbonaamcheck = mysql_query("SELECT level FROM paneel_FLeden WHERE departement = '".$departement['id']."' AND habbonaam = '".$wishabbonaam."' ORDER BY level DESC, habbonaam ASC ");
				$num_wishabbonaamcheck = mysql_num_rows($sql_wishabbonaamcheck);
				 if($num_wishabbonaamcheck != 0)
				 {
					 $lidcheck = mysql_fetch_assoc($sql_wishabbonaamcheck);
					  if(($eigenlvl['Flidlevel_edit'] != 0 && $lidcheck['level'] <= $eigenlvl['Flidlevel_edit']) || $userLevel > 1)
					  {
						mysql_query("DELETE FROM paneel_FLeden WHERE departement = '".$departement['id']."' AND habbonaam = '".$wishabbonaam."' ");
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een forum medewerker gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					  }
				 }
			 }
			 
			 ?>
			 </td>
			 
			 <td align="center">
			 
	<a style="text-decoration:none;" id="show_lid_aanpassen<?= $leden['habbonaam'] ?>" onclick="document.getElementById('spoiler_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display=''; document.getElementById('close_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display=''; 
	document.getElementById('show_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display='none'; "><input type="submit" class="submit" value="Edit"></a>
	<a style="text-decoration:none; display:none;" id="close_lid_aanpassen<?= $leden['habbonaam'] ?>" onclick="document.getElementById('spoiler_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display='none'; document.getElementById('close_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display='none'; 
	document.getElementById('show_lid_aanpassen<?= $leden['habbonaam'] ?>').style.display=''; "><input type="submit" class="submit" value="Sluit" ></a>
			 <?php
		 }
		 ?>
         </td>
         
	</tr>
</table>   
<?php 
		 if(($eigenlvl['Flidlevel_edit'] != 0 && $leden['level'] <= $eigenlvl['Flidlevel_edit']) || $userLevel > 1)
		 {
			 if(!isset($_POST["lid_edit"])){
?>			 
<span id="spoiler_lid_aanpassen<?= $leden['habbonaam'] ?>" style="display:none;">
<form id="formulier" method="post" action="">
<input type="hidden" value="<?= $leden['habbonaam'] ?>" name="habbonaamedit" />
 <select name="editlevel" >
<?php            
		$sql_eigenlvlenlager = mysql_query("SELECT * FROM paneel_FLevel WHERE  level < ".$level." ORDER BY level ASC ");
		while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
		{
			echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
		}	
		if($userLevel > 1)
		{
			echo '<option value="5">Beheerder</option>';
		}
?>		
</select>
<input type="submit" class="submit" name="lid_edit" id="lid_edit" value="Opslaan">
</form>
</span>
   
<?php
			}
			else if(isset($_POST["lid_edit"]) && $_POST['habbonaamedit'] != "" && $_POST['editlevel'] != ""){
				$edithabbonaam = mysql_real_escape_string(htmlspecialchars($_POST['habbonaamedit']));
				$sql_edithabbonaamcheck = mysql_query("SELECT level FROM paneel_FLeden WHERE departement = '".$departement['id']."' AND habbonaam = '".$edithabbonaam."' ORDER BY level DESC, habbonaam ASC ");
				$num_edithabbonaamcheck = mysql_num_rows($sql_edithabbonaamcheck);
				 if($num_edithabbonaamcheck != 0)
				 {
					 $lidcheck = mysql_fetch_assoc($sql_edithabbonaamcheck);
					  if(($eigenlvl['Flidlevel_edit'] != 0 && $lidcheck['level'] <= $eigenlvl['Flidlevel_edit']) || $userLevel > 1)
					  {
						  
						$editlevelxx = mysql_real_escape_string(htmlspecialchars($_POST['editlevel']));							
						$sql_opgegevenlvl = mysql_query("SELECT id FROM paneel_FLevel WHERE  level = '".$editlevelxx."' ");
						$num_opgegevenlvl = mysql_num_rows($sql_opgegevenlvl);
						if($num_opgegevenlvl != 0)
						{
							$editlevel = $editlevelxx;	
							if(($eigenlvl['Flidlevel_aanstelle'] != 0 && $editevel <= $eigenlvl['Flidlevel_aanstelle']) || $userLevel > 1)
							{						
								mysql_query("UPDATE paneel_FLeden SET level='".$editlevel."', datum=NOW() WHERE habbonaam='".$edithabbonaam."' AND departement = '".$departement['id']."'  ");

								mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een forum medewerker rang aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
								die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
							}
						}
					}
				}
			}
		 }
?>
</div>    
<?php  
}
if($eigenlvl['Flidlevel_aanstelle'] != 0)
{
	
	if(!isset($_POST["lid_toevoegen"]))
	{
?>
<form id="formulier" method="post" action="" style="width:410px;">
	<table width="410px" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="150px" style="font-weight:bold;">Habbonaam:</td>
			<td><input type="text" style="width:260px;" class="textbox" name="lidnaam" placeholder="Habbonaam"></td>
		</tr>
		<tr>
			<td width="150px" style="font-weight:bold;">Level:</td>
            <td>
                <select name="lidlevel" >
<?php            
		$sql_eigenlvlenlager = mysql_query("SELECT level, naamlevel FROM paneel_FLevel WHERE level < ".$level." ORDER BY level ASC ");
		while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
		{
			echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
		}
		
		if($userLevel > 1)
		{
			echo '<option value="5">Beheerder</option>';
		}
?>		
				</select>
			</td>
     	</tr>           
    	<tr>
        	<td colspan="2" align="right"><input type="submit" class="submit" name="lid_toevoegen" id="lid_toevoegen" value="Lid toevoegen"></td>
     	</tr>
	</table>        
</form>    
<?php
	}
	else if(isset($_POST["lid_toevoegen"]))
	{
		if($_POST['lidnaam'] == ''  || $_POST['lidlevel'] == "") 
		{
			echo '
				<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
				<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>Er is geen lid toegevoegd! Je hebt namelijk niet alle velden ingevuld.</td>
						<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
					</tr>
				</table>	
				</div>
			';
		}
		else  
		{
			$lidnaamxx = mysql_real_escape_string(htmlspecialchars($_POST['lidnaam']));	
			$sql_opgegevennaam = mysql_query("SELECT id FROM paneel_FLeden WHERE habbonaam = '".$lidnaamxx."' AND departement = '".$departement['id']."' ");
			$num_opgegevennaam = mysql_num_rows($sql_opgegevennaam);
			if($num_opgegevennaam == 0)
			{
				$sql_rangvanopgegeven = mysql_query("SELECT id FROM paneel_rangverandering WHERE habbonaam = '".$lidnaamxx."' AND rang_nieuw > 1 ORDER BY rang_op DESC LIMIT 1" );
				$num_rangvanopgegeven = mysql_num_rows($sql_rangvanopgegeven);
				if($num_rangvanopgegeven != 0)
				{
					$lidnaam = $lidnaamxx;
					$lidlevelxx = mysql_real_escape_string(htmlspecialchars($_POST['lidlevel']));							
					$sql_opgegevenlvl = mysql_query("SELECT id FROM paneel_FLevel WHERE level = '".$lidlevelxx."' ");
					$num_opgegevenlvl = mysql_num_rows($sql_opgegevenlvl);
					if($num_opgegevenlvl != 0)
					{
						$lidlevel = $lidlevelxx;	
						
						if (($eigenlvl['Flidlevel_aanstelle'] != 0 && $lidlevel <= $eigenlvl['Flidlevel_aanstelle']) || ($userLevel > 1))
						{						
							mysql_query("INSERT INTO `paneel_FLeden` (`habbonaam`, `departement`, `level`, `datum`) VALUES ('".$lidnaam."', '".$departement['id']."', '".$lidlevel."', NOW())");
							
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een lid toegevoegd aan een forum  departement', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
						}
					}
				}
			}
		}
	}
}
?>
</span>
<?php		
		}
/////////////////// Departement leden ////////////////////	
/////////////////// Departement leden ////////////////////	
/////////////////// Departement leden ////////////////////	
	}
}
 //////////////////////////
 ////////////////////////// Opties end
 //////////////////////////
					
?>