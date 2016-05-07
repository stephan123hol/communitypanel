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
	
if($categorie['toegang'] >= 2 || $categorie['toegang'] <= 1)
{ 
	if($categorie['toegang'] <= $level)
	{
		$categorietoegang = "ja";
	}
	else
	{
		$categorietoegang = "nee";
	}
}
else if($categorie['toegang'] > 1 && $categorie['toegang'] < 2)
{ 
	if($categorie['toegang'] == $level || $level >= 2)
	{
		$categorietoegang = "ja";
	}
	else
	{
		$categorietoegang = "nee";
	}
}
 
 
if($categorietoegang == "nee")
{
	echo '<META http-equiv="refresh" content="0; url=/forum">';
}	

?>

					<div class="forum-topbar">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="padding-left:25px;">
									<?= $categorie['naam'] ?><br />
									<span style="font-size: 10px;"><?= $categorie['omschrijving'] ?></span>
								</td>
								<td width="60" align="center" style="font-size:10px;"></td>
								<td width="60" align="center" style="font-size:10px;"></td>
                                <td width="100px" align="right" style="padding-right:10px;">
									<?php  
                                    if($departement['zichtbaarheid'] == 2) 
									{
										echo '
										<img src="/_paneel/assets/afbeelding/forum_icon_3.gif"  /> 
										';
									}
                                     ?>
                                 </td>
								<td width="40px"><img src="<?= $departement['badge'] ?>" border="0" /></td>
							</tr>
						</table>
					</div>
      
      
      
<?php				
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// Sticky 
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
	
					$kleurrangchange = 0;
					$sql_topic = mysql_query(" SELECT * FROM paneel_FTopic WHERE prullenbak = '0' AND categorie = '".$categorie['id']."' AND sticky = '1' ORDER BY datum DESC " );
					while($topic = mysql_fetch_assoc($sql_topic))
					{
						$sql_topicreacties = mysql_query(" SELECT datum FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum DESC" );
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
													
						if($categorietoegang == "ja")
						{
//////////////////////////////////
								$sql_bekeken = mysql_query(" SELECT * FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' " );							
								if($_SESSION['login'] == 1)
								{
									$sql_ikbekeken = mysql_query(" SELECT * FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' AND habbonaam = '".$_SESSION['habbonaam']."' ORDER BY datum DESC " );
									if(mysql_num_rows($sql_ikbekeken) == 0)
									{
											$gezien = 0;
									}
									else
									{
										$topicreacties = mysql_fetch_assoc($sql_topicreacties);
										$ikbekeken = mysql_fetch_assoc($sql_ikbekeken);
										
										$laatstbekeken = $ikbekeken['datum'];
										if($num_topicreacties == 0)
										{
											$laatstedatum = $topic['datum'];
										}
										else
										{
											$laatstedatum = $topicreacties['datum'];
										}
										
										if($laatstbekeken < $laatstedatum)
										{
											$gezien = 0;
										}
										else { $gezien = 1; }
									}
								}
								else { $gezien = 1; }
	////////////////////////////////							
								
	?>						
						
							<div onclick="location.href='<?= '/forum/'.rawurlencode($departement['naam']).'/'.rawurlencode($categorie['naam']).'/'.$topic['id'] ?>'" style="height:60px; margin-bottom:5px; cursor: pointer; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #990000; <?php if($gezien == 1){ echo 'color:#999;'; } else { echo 'color:#990000;'; } ?> " onmouseover="this.style.border = '1px solid #FF3300'" onmouseout="this.style.border = '1px solid #990000'">	
												
								<table width="100%" height="60px" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="50px" align="center" style="padding-left:25px;">
											<?php
											if ($topic["closed"] == 1)
											{
												?>
												<img src="/_paneel/assets/afbeelding/forum_icon_3.gif" title="Topic is gesloten" />
												<?php
											}
											else
											{
												?>
												<img src="/_paneel/assets/afbeelding/forum_icon_2.gif" />
												<?php
											}
											?>
										</td>
										<td>
											<b><?= $topic['titel'] ?></b><br />
											<i><?= datumrewrite($topic['datum']) ?>  om <?= timerewrite($topic['datum']) ?> uur</i>
										</td>
										<td width="70px"  align="center" style="font-style:italic;"> <a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $topic['habbonaam']); echo $mijn_dossier; ?>"><?= $topic['habbonaam'] ?></a></td>
										<td width="70px"  align="center" style="font-style:italic;">
										<?php
										$sql_reacties = mysql_query(" SELECT habbonaam, datum FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum DESC " );
										$reacties = mysql_fetch_assoc($sql_reacties);
										echo mysql_num_rows($sql_reacties);
										?>
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
										
										<?php
										$sql_bekeken = mysql_query(" SELECT id FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' " );
										echo mysql_num_rows($sql_bekeken);
										?>
										
										</td>
										<td width="150px" align="center">
										<?php
										if (mysql_num_rows($sql_reacties) != 0)
										{
										?>
										<a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $reacties['habbonaam']); echo $mijn_dossier; ?>"><?= $reacties['habbonaam'] ?></a><br />
										<i style="font-size:10px;"><?= datumrewrite($reacties['datum']) ?> om <?= timerewrite($reacties['datum']) ?> uur</i>
										</td>
										<td width="40px" align="right" style="padding-right:10px;">
										<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $reacties['habbonaam'] ?>&head_direction=3&direction=4&action=sit&size=s&img_format=gif" />
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
					if(mysql_num_rows($sql_topic) != 0) { echo '<br/><br />'; }
/////////////////////////////////////////////////////////			
					$kleurrangchange = 0;
					$sql_topic = mysql_query(" SELECT * FROM paneel_FTopic WHERE prullenbak = '0' AND categorie = '".$categorie['id']."' AND sticky = '0' ORDER BY datum DESC " );
					while($topic = mysql_fetch_assoc($sql_topic))
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
												
						if($categorietoegang == "ja")
						{
//////////////////////////////////
								$sql_bekeken = mysql_query(" SELECT * FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' " );
								if($_SESSION['login'] == 1)
								{
									$sql_ikbekeken = mysql_query(" SELECT * FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' AND habbonaam = '".$_SESSION['habbonaam']."' ORDER BY datum DESC " );
									if(mysql_num_rows($sql_ikbekeken) == 0)
									{
											$gezien = 0;
									}
									else
									{
										$sql_topicreacties = mysql_query(" SELECT datum FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum DESC" );
										$topicreacties = mysql_fetch_assoc($sql_topicreacties);
										$ikbekeken = mysql_fetch_assoc($sql_ikbekeken);
										
										$laatstbekeken = $ikbekeken['datum'];
										if($num_topicreacties == 0)
										{
											$laatstedatum = $topic['datum'];
										}
										else
										{
											$laatstedatum = $topicreacties['datum'];
										}
										if($laatstbekeken < $laatstedatum)
										{
											$gezien = 0;
										}
										else { $gezien = 1; }
									}
								}
								else { $gezien = 1; }
	////////////////////////////////																			
	?>						
						
							<div onclick="location.href='<?= '/forum/'.rawurlencode($departement['naam']).'/'.rawurlencode($categorie['naam']).'/'.$topic['id'] ?>'" style="height:60px; margin-bottom:5px; cursor: pointer; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #d6d6d6; <?php if($gezien == 1){ echo 'color:#999;'; } ?> " onmouseover="this.style.border = '1px solid #666666'" onmouseout="this.style.border = '1px solid #d6d6d6'">	
												
								<table width="100%" height="60px" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td width="50px" align="center" style="padding-left:25px;">
											<?php
											if ($topic["closed"] == 1)
											{
												?>
												<img src="/_paneel/assets/afbeelding/forum_icon_3.gif" title="Topic is gesloten" />
												<?php
											}
											else
											{
												?>
												<img src="/_paneel/assets/afbeelding/forum_icon_2.gif" />
												<?php
											}
											?>
										</td>
										<td>
											<b><?= $topic['titel'] ?></b><br />
											<i><?= datumrewrite($topic['datum']) ?>  om <?= timerewrite($topic['datum']) ?> uur</i>
										</td>
										<td width="70px"  align="center" style="font-style:italic;"> <a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $topic['habbonaam']); echo $mijn_dossier; ?>"><?= $topic['habbonaam'] ?></a></td>
										<td width="70px"  align="center" style="font-style:italic;">
										<?php
										$sql_reacties = mysql_query(" SELECT habbonaam, datum FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum DESC " );
										$reacties = mysql_fetch_assoc($sql_reacties);
										echo mysql_num_rows($sql_reacties);
										?>
										</td>
										<td width="70px"  align="center" style="font-style:italic;">
										<?php
										$sql_bekeken = mysql_query(" SELECT id FROM paneel_FGelezen WHERE prullenbak = '0' AND topic = '".$topic['id']."' " );
										echo mysql_num_rows($sql_bekeken);
										?>

										 </td>
										<td width="150px" align="center">
										<?php
										if (mysql_num_rows($sql_reacties) != 0)
										{
										?>
										<a href="<?= '/profiel/'.$reacties['habbonaam'] ?>"><?= $reacties['habbonaam'] ?></a><br />
										<i style="font-size:10px;"><?= datumrewrite($reacties['datum']) ?> om <?= timerewrite($reacties['datum']) ?> uur</i>
										</td>
										<td width="40px" align="right" style="padding-right:10px;">
										<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $reacties['habbonaam'] ?>&head_direction=3&direction=4&action=sit&size=s&img_format=gif" />
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
					} //// topics while
					
 //////////////////////////
 ////////////////////////// Opties / knopjes begin
 //////////////////////////
 	if($_SESSION['login'] == 1)
	{
			$sql_eigenlvl = mysql_query("SELECT * FROM paneel_FLevel WHERE level = '".$level."' ");
			$eigenlvl = mysql_fetch_assoc($sql_eigenlvl);
			
			if($categorie['topic_maken'] <= $level){
?>	
<a style="text-decoration:none;" id="show_topicmaken" onclick="
document.getElementById('spoiler_Topicmaken').style.display=''; 
document.getElementById('close_topicmaken').style.display=''; 
document.getElementById('show_topicmaken').style.display='none';
document.getElementById('spoiler_cataedit').style.display='none'; 
document.getElementById('close_cataedit').style.display='none'; 
document.getElementById('show_cataedit').style.display='';
"><input type="submit" class="submit" value="Topic aanmaken" style="float: right;"></a>

<a style="text-decoration:none; display:none;" id="close_topicmaken" onclick="
document.getElementById('spoiler_Topicmaken').style.display='none'; 
document.getElementById('close_topicmaken').style.display='none'; 
document.getElementById('show_topicmaken').style.display='';
"><input type="submit" class="submit" value="Sluiten" style="float: right;"></a>


<?php
			}
		if($eigenlvl['categorie_edit'] == 1){
?>
<a style="text-decoration:none;" id="show_topicmaken" onclick="
document.getElementById('spoiler_Topicmaken').style.display='none'; 
document.getElementById('close_topicmaken').style.display='none'; 
document.getElementById('show_topicmaken').style.display='';
document.getElementById('spoiler_cataedit').style.display=''; 
document.getElementById('close_cataedit').style.display=''; 
document.getElementById('show_cataedit').style.display='none';
"><input type="submit" class="submit" value="Categorie aanpassen" style="float: right;"></a>

<a style="text-decoration:none; display:none;" id="close_topicmaken" onclick="
document.getElementById('spoiler_cataedit').style.display='none'; 
document.getElementById('close_cataedit').style.display='none'; 
document.getElementById('show_cataedit').style.display='';
"><input type="submit" class="submit" value="Sluiten" style="float: right;"></a>

<?	
}
echo '<div style="clear:both;"></div>';	
 //////////////////////////
 ////////////////////////// Topic aanmaken
 //////////////////////////
		if($categorie['topic_maken'] <= $level)
		{
			
		$sql_laatstaangemaakt = mysql_query("SELECT * FROM paneel_FTopic WHERE habbonaam = '".$_SESSION['habbonaam']."' AND datum > DATE_SUB(NOW(), INTERVAL 10 SECOND) ");
		if(mysql_num_rows($sql_laatstaangemaakt) == 0)
		{
			if(!isset($_POST["topic_verstuur"]))
			{
?>
<span id="spoiler_Topicmaken" style="display:none;">
	<form id="formulier" method="post" action="">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100px" style="font-weight:bold;">Titel:</td>
				<td><input type="text" style="width:800px;" class="textbox" name="topic_titel" placeholder="Titel"></td>
         	</tr>
            <tr>
				<td width="100px" style="font-weight:bold;">Bericht:</td>
				<td >
					<textarea style="width:800px; height:350px;" class="Editor" id="newTopicEditor" name="topic_bericht"></textarea>
                </td>
          	</tr>  
<?php
if ($eigenlvl['topic_sticky_edit'] == 1)
{ 
?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Sticky:</td>
				<td>
                    <select name="topic_sticky" >
                        <option value="0" CHECKED>Nee</option>
                        <option value="1">Ja</option>
                  	</select>
                </td>
         	</tr>
 <?php
 }
if ($eigenlvl['topic_reageerlevel'] == 1)
{ 
 ?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Reageren vanaf:</td>
				<td >
            <select name="topic_reactielvl" >
            <option value="0">Bezoeker</option>
<?php            
			$sql_eigenlvlenlager = mysql_query("SELECT * FROM paneel_FLevel WHERE  level <= ".$level." ORDER BY level ASC ");
			while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
			{
					echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
			}
?>		</select>
                </td>
         	</tr>
 <?php } ?>           
         	<tr>   
                <td colspan="2">    
					<input type="submit" class="submit" name="topic_verstuur" id="topic_verstuur" value="Topic plaatsen">
				</td>
			</tr>
		</table>
	</form>
</span>
		
<?php		
			}
			else if(isset($_POST["topic_verstuur"]))
			{
				if($_POST['topic_titel'] == '' or $_POST['topic_bericht'] == '') 
				{
                 	echo '
						<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>Er is geen Topic geplaatst! Je hebt namelijk niet alle velden ingevuld.</td>
								<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
							</tr>
						</table>	
						</div>
					';
				}
				else  
				{
					if ($eigenlvl['topic_sticky_edit'] != 1)
					{ 
						$sticky = 0;
					}
					else
					{
						if($_POST['topic_sticky'] != "1")
						{
							$sticky = 0;
						}
						else
						{
							$sticky = 1;
						}
					}
					if ($eigenlvl['topic_reageerlevel'] != 1)
					{ 
						$reageer = 0;
					}
					else
					{
						if($_POST['topic_reactielvl'] == "")
						{
							$reageer = 0;
						}
						else
						{
							$reageerxx = mysql_real_escape_string(htmlspecialchars($_POST['topic_reactielvl']));							
							$sql_opgegevenlvl = mysql_query("SELECT * FROM paneel_FLevel WHERE level = '".$reageerxx."' ");
							$num_opgegevenlvl = mysql_num_rows($sql_opgegevenlvl);
							if($num_opgegevenlvl != "0")
							{
								$reageer = mysql_real_escape_string(htmlspecialchars($_POST['topic_reactielvl']));		
							}
							else
							{
								$reageer = 0;
							}
						}
					}
					
					$titel1 = mysql_real_escape_string(htmlspecialchars($_POST["topic_titel"]));
					$titel = substr($titel1, 0, 200);

					$bericht = $inputFilter->filterHTML($_POST["topic_bericht"]);
					$bericht = mysql_real_escape_string($bericht);
					////$bericht = substr($bericht, 0, 300000);

					mysql_query("INSERT INTO `paneel_FTopic` (`titel`, `bericht`, `habbonaam`, `sticky`, `reageerlevel`, `categorie`, `datum`) VALUES ('".$titel."', '".$bericht."', '".$_SESSION['habbonaam']."', '".$sticky."', '".$reageer."', '".$categorie['id']."', NOW())") or die(mysql_error());
					
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een topic aangemaakt', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
				}
				
				
			}
		}
			
			
		}
 //////////////////////////
 ////////////////////////// Topic aanmaken end
 //////////////////////////
		if($eigenlvl['categorie_edit'] == 1)
		{
			if(!isset($_POST["categorie_verstuur"]))
			{
?>
 <span id="spoiler_cataedit" style="display:none;">
 	<form id="formulier" method="post" action="" style="width:400px;">
		<table width="400px" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="150px" style="font-weight:bold;">Naam:</td>
				<td><input type="text" style="width:250px;" class="textbox" name="categorie_naam" value="<?= $categorie['naam'] ?>"></td>
         	</tr>
            <tr>
				<td width="150px" style="font-weight:bold;">Omschrijving:</td>
				<td >
					<textarea style="width:250px; height:50px;"  class="textbox" name="categorie_omschrijving"><?= $categorie['omschrijving'] ?></textarea>
                </td>
          	</tr>  
			<tr>
				<td width="150px" style="font-weight:bold;">Kunnen zien vanaf:</td>
				<td >
            <select name="categorie_toegang" >
            <option value="<?= $categorie['toegang'] ?>" selected="selected">
			<?php
			if($categorie['toegang'] == 0)
			{
				echo 'Bezoeker';
			}
			else
			{
			$sql_rangbijlvlv = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$categorie['toegang']."' ORDER BY id DESC LIMIT 1");
			$rangbijlvlv = mysql_fetch_assoc($sql_rangbijlvlv);
			echo $rangbijlvlv['naamlevel'];
			}
			?>

			</option>
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
            <option value="<?= $categorie['topic_maken'] ?>" selected="selected">
			<?php
			if($categorie['topic_maken'] == 0)
			{
				echo 'Bezoeker';
			}
			else
			{
			$sql_rangbijlvlv = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$categorie['topic_maken']."' ORDER BY id DESC LIMIT 1");
			$rangbijlvlv = mysql_fetch_assoc($sql_rangbijlvlv);
			echo $rangbijlvlv['naamlevel'];
			}
			?>
			</option>
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
				<td width="150px" style="font-weight:bold;">Categorie Barhon:</td>
				<td>
					<select name="categorie_wissen" >
						<option value="0" selected="selected">Niet Barhon</option>
						<option value="1">Wel Barhon</option>
					</select>
                </td>
         	</tr>
			<tr>
				<td width="130px" style="font-weight:bold;">Categorie order:</td>
				<td>
					<td><input type="number" style="width:250px;" class="textbox" name="categorie_order" value="<?= $categorie['order'] ?>"></td>
                </td>
         	</tr>
         	<tr>   
                <td colspan="2" align="right">    
					<input type="submit" class="submit" name="categorie_verstuur" id="categorie_verstuur" value="Categorie aanpassen">
				</td>
			</tr>
		</table>
	</form>
</span>
		
<?php		
			}
			else if(isset($_POST["categorie_verstuur"]))
			{
				//die(print_r($_POST));
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
					$order = intval($_POST["categorie_order"]);

					$sql_naamxxcheck = mysql_query("SELECT id FROM paneel_FCategorie WHERE naam = '".$naam."' AND id = '".$departement['id']."'");
					$num_naamxxcheck = mysql_num_rows($sql_naamxxcheck);
					if($num_naamxxcheck > 0 && $categorie['naam'] != $_POST["categorie_naam"])
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
						
						$sql_topicxxcheck = mysql_query("SELECT * FROM paneel_FLevel WHERE level = ".$topicxx."");
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
	
						
						if($_POST["categorie_wissen"] == 0)
						{
							mysql_query("UPDATE paneel_FCategorie SET toegang='".$toegang."', topic_maken='".$topic."', omschrijving='".$omschrijving."', `order` = '".$order."', naam='".$naam."', datum=NOW() WHERE id='".$categorie['id']."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een categorie aangepast', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; url=/forum/'.rawurlencode($departement['naam']).'">';
						}
						else if($_POST["categorie_wissen"] == 1)
						{	
							mysql_query("UPDATE paneel_FCategorie SET prullenbak='1', datum=NOW() WHERE id='".$categorie['id']."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een categorie aangepast', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; url=/forum/'.rawurlencode($departement['naam']).'">';
						}


					}
				}
			}		
		}


		
	} /// login check
 //////////////////////////
 ////////////////////////// 
 //////////////////////////
?>