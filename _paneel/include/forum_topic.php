<?php
$wesselTitle = '<span class="wessel-title">The Godfather</span>';

$topDonator = 'Twinsun';
$topDonatorTitle = '<span class="wessel-title">#1 Donateur</span>';

$specialNames = array(
		'Streufall=perm' => '<img src="/_paneel/assets/afbeelding/favourite_group_icon.gif" /> <span style="color: #000000; text-shadow: 1px 1px 1px rgba(255, 0, 153, 0.77);">Streufall=perm</span>',
		'Snowcake' => '<span style="color: #ff4e6d">Snowcake</span>',
		'aDistraction' => '<span style="color: #ff0000;">aDistraction</span>',
		'Praelus' => '<img src="https://devbest.com/styles/sub_icons/admin.png" /> <span style="color: #000000; text-shadow: 1px 1px 1px rgba(255, 10, 10, 0.77);">[ADMIN] Praelus</span>'
);

if($_SESSION['login'] == 1)
{
	$sql_leden = mysql_query("SELECT level FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
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
	
	$forum->updateWatched($_SESSION["habbonaam"], $topic["id"]);
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

$sql_eigenlvl = mysql_query("SELECT * FROM paneel_FLevel WHERE  level = '".$level."' ");
$eigenlvl = mysql_fetch_assoc($sql_eigenlvl);

$page = isset($_GET['page']) && ctype_digit($_GET['page']) ? $_GET['page'] : 0;
$resultAmount = 10;
?>

<div class="forum-topbar">
	<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding-left:25px;">
				<?= $topic['titel'] ?><br />
				<span style="font-size: 10px;">Reacties: <?= $forum->countReactions($topic["id"]) ?> - Bekeken: <?= $forum->countWatched($topic["id"]) ?></span>
			</td>
			<td width="60" align="center" style="font-size:10px;"></td>
			<td width="60" align="center" style="font-size:10px;"></td>
			<td width="100px" align="right" style="padding-right:10px;">
				<?php  
				if($departement['zichtbaarheid'] == 2) 
				{
					echo '<img src="/_paneel/assets/afbeelding/forum_icon_3.gif"  /> ';
				}
				 ?>
			 </td>
			<td width="40px"><img src="<?= $departement['badge'] ?>" border="0" /></td>
		</tr>
	</table>
</div>
                  
      
 <?php
if($categorietoegang == "ja")
{
	if (array_key_exists($topic["habbonaam"], $specialNames))
	{
		$topicUsername = $specialNames[$topic["habbonaam"]];
	}
	else
	{
		$topicUsername = $topic["habbonaam"];
	}
 ?>     
      
<div style="margin-bottom:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; padding:5px; border:1px solid #d6d6d6; background-color:#FFF;">	      
   <table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="165px" valign="top" align="center" rowspan="2" style="width:165px !important;">
            	<a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $topic['habbonaam']); echo $mijn_dossier; ?>">
                <b><span data-user="<?= $topic['id'] ?>"><?= $topicUsername; ?></span></b></a><br />
                <?php
				$sql_bedrijfrang = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam = '".$topic['habbonaam']."' ORDER BY rang_op DESC LIMIT 1" );
				$num_bedrijfrang = mysql_num_rows($sql_bedrijfrang);
				if($num_bedrijfrang == 0)
				{
					$rang = 1;
				}
				else
				{
					$rang_nummer = mysql_fetch_assoc($sql_bedrijfrang);
					$rang = $rang_nummer['rang_nieuw'];
				}
				$sql_rangbijlvl = mysql_query("SELECT rang_naam FROM paneel_rangniveau WHERE rang_level='".$rang."' ORDER BY id DESC LIMIT 1");
				$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
				
				echo '<font style="color:#000000; font-weight: normal;">'.$rangbijlvl['rang_naam'].'</font><br/>';
				if ($topic["habbonaam"] == $topDonator)
				{
					echo '<font style="color:#000000; font-weight: normal;">'.$topDonatorTitle.'</font><br/>';
				}
				?>
				<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $topic['habbonaam'] ?>&head_direction=3&direction=2&gesture=sml&img_format=gif" border="0" />
				<br />
				<?php
				$sql_ledenz = mysql_query("SELECT * FROM paneel_FLeden WHERE habbonaam = '".$topic['habbonaam']."' AND departement = '".$departement['id']."' " );
				$ledenz = mysql_fetch_assoc($sql_ledenz);
				$num_ledenz = mysql_num_rows($sql_ledenz);
				if($num_ledenz == 0)
				{
					$levelz = 0;
				}
				else
				{
					$levelz = $ledenz['level'];
				}
				
				if ($topic["habbonaam"] == "Praelus")
				{
					echo '<font style="font-size:10px;">Administrator</font><br/>';
				}
				else if ($user->isForumAdmin($reactie["habbonaam"]))
				{
					echo '<font style="font-size:10px;">Forum Beheerder</font><br/>';
				}
				else if ($levelz == 0 )
				{
					echo '<font style="font-size:10px;">Bezoeker</font><br/>';
				}
				else if ($levelz != 0 )
				{
					$sql_functiebijlvl = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$levelz."'  LIMIT 1");
					$functiebijlvl = mysql_fetch_assoc($sql_functiebijlvl);			
					echo '<font style="font-size:10px;">Departement '.$functiebijlvl['naamlevel'].'</font><br/>';
				}
				
				$sql_reactiesaantal = mysql_query("SELECT * FROM paneel_FReactie WHERE habbonaam = '".$topic['habbonaam']."' AND prullenbak = '0'" );
				$num_reactiesaantal = mysql_num_rows($sql_reactiesaantal);
				$sql_topicaantal = mysql_query("SELECT * FROM paneel_FTopic WHERE habbonaam = '".$topic['habbonaam']."' AND prullenbak = '0'" );
				$num_topicaantal = mysql_num_rows($sql_topicaantal);
				
				$aantal_berichten = $num_topicaantal + $num_reactiesaantal ;
				
				echo '<i style="font-size:10px;">'.$aantal_berichten.' berichten</i>';
				?>
         	</td>
   			<td style="background-color:#d6d6d6;" width="1px"  rowspan="2"></td>
          	<td valign="top" style="padding-left:10px;">
            <font style="text-decoration: italic; font-size: 10px; color: gray;"><?= datumrewrite($topic['datum']) ?>  om <?= timerewrite($topic['datum']) ?> uur <?php if($topic['aangepast_datum'] != '0000-00-00 00:00:00'){ ?> - Laatst aangepast op: <?= datumrewrite($topic['aangepast_datum']) ?>  om <?= timerewrite($topic['aangepast_datum']) ?> uur.<?php } ?></font>
            <div style="width:100%; word-wrap: break-word; height:auto; padding:0px;">
   				<div class="forum-message" data-id="<?= $topic['id'] ?>">
					<?= $topic['bericht'] ?>
				</div>
				<?php
				if ($forum->signatureEnabled($topic["habbonaam"]) === true)
				{
					?>
					<div class="forum-signature">
						<hr />
						<?= $forum->getSignature($topic["habbonaam"]) ?>
					</div>
					<?php
				}
				?>
            </div>
			</td>
		</tr>
   		<tr>
      		<td align="right" valign="bottom">
				<!--<button class="quote submit" id="<?= $topic['id'] ?>">Quote</button> <button class="crossthread-quote submit" id="<?= $topic['id'] ?>">Crossthread</button>-->
				
<?php
	if(($eigenlvl ['topic_wis'] == 1 || $eigenlvl ['topic_edit'] == 1) && $topic['reageerlevel'] <= $level || $_SESSION['habbonaam'] == $topic['habbonaam'])
	{
	?>            
	<a style="text-decoration:none;" id="show_topicaanpassen" onclick="
	document.getElementById('spoiler_topicaanpassen').style.display=''; 
	document.getElementById('close_topicaanpassen').style.display=''; 
	document.getElementById('show_topicaanpassen').style.display='none';
	"><input type="submit" class="submit" value="Topic aanpassen"></a>
	
	<a style="text-decoration:none; display:none;" id="close_topicaanpassen" onclick="
	document.getElementById('spoiler_topicaanpassen').style.display='none'; 
	document.getElementById('close_topicaanpassen').style.display='none'; 
	document.getElementById('show_topicaanpassen').style.display='';
	"><input type="submit" class="submit" value="Sluiten" ></a>
	<?php
	}
?>                 
            </td>
 		</tr>
	</table>
</div>
<?php
/////
	if($eigenlvl['topic_edit'] == 1 && $topic['reageerlevel'] <= $level)
	{
		if(!isset($_POST["topic_verstuur"]))
		{
?>
<div id="spoiler_topicaanpassen" style="display:none;">
	<form id="formulier" method="post" action="">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100px" style="font-weight:bold;">Titel:</td>
				<td><input type="text" style="width:800px;" class="textbox" name="topic_titel" value="<?= $topic['titel'] ?>"></td>
         	</tr>
            <tr>
				<td width="100px" style="font-weight:bold;">Bericht:</td>
				<td >
					<textarea style="width:800px; height:350px;" id="cke-topicaanpassen" class="Editor" name="topic_bericht"><?= $topic['bericht'] ?></textarea>
                </td>
          	</tr>
			<?php
			if ($level > 4)
			{
			?>
			<tr>
				<td width="100px" style="font-weight:bold;">Auteur:</td>
				<td>
					<input type="text" name="topic_auteur" value="<?= $topic['habbonaam'] ?>" />
                </td>
          	</tr>
			<?php
			}
			if ($eigenlvl['topic_wis'] == 1)
			{
?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Topic Barhon:</td>
				<td>
                    <select name="topic_wissen" >
                        <option value="0">Nee</option>
                        <option value="1">Ja</option>
                  	</select>
                </td>
         	</tr>
 <?php
			}
			
			if ($eigenlvl['topic_clean_comments'] == 1)
			{
?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Topic opschonen:</td>
				<td>
                    <select name="topic_opschonen" >
                        <option value="0">Nee</option>
                        <option value="1">Ja</option>
                  	</select>
                </td>
         	</tr>
 <?php
			}
			
			if ($eigenlvl['topic_sticky_edit'] == 1)
			{
?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Sticky:</td>
				<td>
                    <select name="topic_sticky" >
                        <option value="0" <?php if($topic['sticky'] == 0){echo 'selected="selected"'; } ?>>Nee</option>
                        <option value="1" <?php if($topic['sticky'] == 1){echo 'selected="selected"'; } ?>>Ja</option>
                  	</select>
                </td>
         	</tr>
 <?php
			}
			
			if ($eigenlvl['topic_close'] == 1)
			{
?>           
			<tr>
				<td width="100px" style="font-weight:bold;">Open/gesloten:</td>
				<td>
                    <select name="topic_close" >
                        <option value="0" <?php if($topic['closed'] == 0){echo 'selected="selected"'; } ?>>Open</option>
                        <option value="1" <?php if($topic['closed'] == 1){echo 'selected="selected"'; } ?>>Gesloten</option>
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
            <option value="<?= $topic['reageerlevel'] ?>" selected="selected">
			<?php
			if($topic['reageerlevel'] == 0)
			{
				echo 'Bezoeker';
			}
			else
			{
				$sql_rangbijlvlv = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$topic['reageerlevel']."' ORDER BY id DESC LIMIT 1");
				$rangbijlvlv = mysql_fetch_assoc($sql_rangbijlvlv);
				echo $rangbijlvlv['naamlevel'];
			}
			?>
			</option>
			<?php
			if ($topic['reageerlevel'] != 0)
			{
				?>
				<option value="0">Bezoeker</option>
				<?php
			}
			
			$sql_eigenlvlenlager = mysql_query("SELECT * FROM paneel_FLevel WHERE  level <= ".$level." ORDER BY level ASC ");
			while ($eigenlvlenlager = mysql_fetch_assoc($sql_eigenlvlenlager))
			{
				if ($topic['reageerlevel'] != $eigenlvlenlager['level'])
				{
					echo '<option value="'.$eigenlvlenlager['level'].'">'.$eigenlvlenlager['naamlevel'].'</option>';
				}
			}
?>		</select>
                </td>
         	</tr>
 <?php 
 			} 
?>           
         	<tr>   
                <td colspan="2">    
					<input type="submit" class="submit" name="topic_verstuur" id="topic_verstuur" value="Topic aanpassen">
				</td>
			</tr>
		</table>
	</form>
</div>
		
<?php		
		}
		else if(isset($_POST["topic_verstuur"]))
		{
			if($_POST['topic_titel'] != '' or $_POST['topic_bericht'] != '') 
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
				
				if ($eigenlvl['topic_close'] != 1)
				{ 
					$closeTopic = 0;
				}
				else
				{
					if($_POST['topic_close'] != "1")
					{
						$closeTopic = 0;
					}
					else
					{
						$closeTopic = 1;
					}
				}
				
				if($_POST['topic_opschonen'] == 1 && $forum->checkDepartmentPermission($_SESSION["habbonaam"], $departement["id"], "topic_clean_comments") === true)
				{
					$forum->deleteTopicComments($topic["id"]);
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
						$sql_opgegevenlvl = mysql_query("SELECT * FROM paneel_FLevel WHERE  level = '".$reageerxx."' ");
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
				$bericht = $inputFilter->filterHTML($bericht);
				$bericht = mysql_real_escape_string($_POST["topic_bericht"]);
				////$bericht = substr($bericht, 0, 300000);

				if($_POST["topic_wissen"] == 0)
				{
					if (isset($_POST['topic_auteur']) && $level > 4)
					{
						$topicAuteur = mysql_real_escape_string($_POST['topic_auteur']);
						mysql_query("UPDATE paneel_FTopic SET titel = '".$titel."', habbonaam = '" . $topicAuteur . "', bericht = '".$bericht."',  sticky = '".$sticky."', closed = '".$closeTopic."', reageerlevel = '".$reageer."',  aangepast_datum = NOW() WHERE id='".$topic['id']."'");
					}
					else
					{
						mysql_query("UPDATE paneel_FTopic SET titel = '".$titel."', bericht = '".$bericht."',  sticky = '".$sticky."', closed = '".$closeTopic."', reageerlevel = '".$reageer."',  aangepast_datum = NOW() WHERE id='".$topic['id']."'");
					}
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een topic aangepast', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				}
				else if($_POST["topic_wissen"] == 1 && $eigenlvl ['topic_wis'] == 1)
				{
					mysql_query("UPDATE paneel_FTopic SET prullenbak='1', aangepast_datum=NOW() WHERE id='".$topic['id']."'");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een topic gewist (".$topic['id'].")', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0; url=/forum/'.rawurlencode($departement['naam']).'">';
				}
			} /// niet leeg
		} // gepost 
	} /// mogen editte
?>                 

<br />
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/forum.topic.html";

//////////////////////////////
////////////////////////////// Reacties
//////////////////////////////
//////////////////////////////
		
	$kleurrangchange = 0;
	$sql_text = "SELECT * FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum ASC LIMIT " . ($page * $resultAmount) . ", " . $resultAmount . ";";
	$sql_reactie = mysql_query($sql_text);
	
	while($reactie = mysql_fetch_assoc($sql_reactie))
	{
		if($kleurrangchange == 1)
		{
			 $rowcolor = '#FFFFFF';
			 $kleurrangchange--;
		}
		else
		{
			 $rowcolor = '#EFFFFE';
			 $kleurrangchange++;
		}

		if (array_key_exists($reactie["habbonaam"], $specialNames))
		{
			$reactieUsername = $specialNames[$reactie["habbonaam"]];
		}
		else
		{
			$reactieUsername = $reactie["habbonaam"];
		}
												
?>						
		<div style="margin-top:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; background-color:<?= $rowcolor ?>; padding:5px; border:1px solid #d6d6d6; ">	
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="165px" align="center" rowspan="2">
					<a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $reactie['habbonaam']); echo $mijn_dossier; ?>"><b><span data-user="<?= $reactie['id'] ?>"><?= $reactieUsername; ?></span></b><br />
					<?php
		$sql_bedrijfrang = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam = '".$reactie['habbonaam']."' ORDER BY rang_op DESC LIMIT 1" );
		$num_bedrijfrang = mysql_num_rows($sql_bedrijfrang);
		if($num_bedrijfrang == 0)
		{
			$rang = 1;
		}
		else
		{
			$rang_nummer = mysql_fetch_assoc($sql_bedrijfrang);
			$rang = $rang_nummer['rang_nieuw'];
		}
		$sql_rangbijlvl = mysql_query("SELECT rang_naam FROM paneel_rangniveau WHERE rang_level='".$rang."' ORDER BY id DESC LIMIT 1");
		$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
		
		if ($reactie["habbonaam"] == "Kwartiermeester")
		{
			$rangbijlvl['rang_naam'] = $wesselTitle;
		}
		
		echo '<font style="color:#000000; font-weight: normal;">'.$rangbijlvl['rang_naam'].'</font><br/>';

		if ($reactie["habbonaam"] == $topDonator)
		{
			echo '<font style="color:#000000; font-weight: normal;">'.$topDonatorTitle.'</font><br/>';
		}		
		?>
		<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $reactie['habbonaam'] ?>&head_direction=3&direction=2&size=s&img_format=gif" border="0" />
		</a><br />
		<?php
		$sql_ledenz = mysql_query("SELECT * FROM paneel_FLeden WHERE habbonaam = '".$reactie['habbonaam']."' AND departement = '".$departement['id']."' " );
		$ledenz = mysql_fetch_assoc($sql_ledenz);
		$num_ledenz = mysql_num_rows($sql_ledenz);
		if($num_ledenz == 0)
		{
			$levelz = 0;
		}
		else
		{
			$levelz = $ledenz['level'];
		}
		
		if ($reactie["habbonaam"] == "Praelus")
		{
			echo '<font style="font-size:10px;">Administrator</font><br/>';
		}
		else if ($user->isForumAdmin($reactie["habbonaam"]))
		{
			echo '<font style="font-size:10px;">Forum Beheerder</font><br/>';
		}
		else if ($levelz == 0 )
		{
			echo '<font style="font-size:10px;">Bezoeker</font><br/>';
		}
		else if ($levelz != 0 )
		{
			$sql_functiebijlvl = mysql_query("SELECT naamlevel FROM paneel_FLevel WHERE level='".$levelz."'  LIMIT 1");
			$functiebijlvl = mysql_fetch_assoc($sql_functiebijlvl);			
			echo '<font style="font-size:10px;">Departement '.$functiebijlvl['naamlevel'].'</font><br/>';
		}
		
		$sql_reactiesaantal = mysql_query("SELECT * FROM paneel_FReactie WHERE habbonaam = '".$reactie['habbonaam']."' AND prullenbak = '0'" );
		$num_reactiesaantal = mysql_num_rows($sql_reactiesaantal);
		$sql_topicaantal = mysql_query("SELECT * FROM paneel_FTopic WHERE habbonaam = '".$reactie['habbonaam']."' AND prullenbak = '0'" );
		$num_topicaantal = mysql_num_rows($sql_topicaantal);
		
		$aantal_berichten = $num_topicaantal + $num_reactiesaantal ;
		
		echo '<i style="font-size:10px;">'.$aantal_berichten.' berichten</i>';
				?>
                                    
                                     </td>
                                     <td style="background-color:#d6d6d6;" width="1px"  rowspan="2"></td>
                                    
									<td valign="top" style="padding-left:10px; position: relative;">
										<font style="text-decoration: italic; font-size: 10px; color: gray;"><?= datumrewrite($reactie['datum']) ?>  om <?= timerewrite($reactie['datum']) ?> uur <?php if($reactie['aangepast_datum'] != '0000-00-00 00:00:00'){ ?> - Laatst aangepast op: <?= datumrewrite($reactie['aangepast_datum']) ?>  om <?= timerewrite($reactie['aangepast_datum']) ?> uur.<?php } ?>
                                        </font>
										<div class="forum-message" data-id="<?= $reactie['id'] ?>">
											<?= $reactie['reactie'] ?>
										</div>
										<?php
										if ($forum->signatureEnabled($reactie["habbonaam"]) === true)
										{
											?>
											<div class="forum-signature">
												<hr />
												<?= $forum->getSignature($reactie["habbonaam"]) ?>
											</div>
											<?php
										}
										?>
									</td>
								</tr>
                                <tr>
                                	<td align="right" valign="bottom" colspan="2">
									<!--<button class="quote submit" id="<?= $reactie['id'] ?>">Quote</button> <button class="crossthread-quote submit" id="<?= $reactie['id'] ?>">Crossthread</button>-->
                                    
<?php 
		if($_SESSION['login'] == 1 && (($eigenlvl['reactie_wis'] == 1 && $leveltje < $level) || ($reactie['habbonaam'] == $_SESSION['habbonaam'])))
		{ 
			 
			if(!isset($_POST["reactie_wis"]))
			{
				echo '<div style="float:right;"><form id="formulier" method="post" action=""><input type="hidden" value="'.$reactie['id'].'" name="reactieid" /><input type="submit" class="submit" name="reactie_wis" id="reactie_wis" value="Barhon"></form></div> ';
			 }
			 else if(isset($_POST["reactie_wis"]))
			 {
				$reactieid = mysql_real_escape_string(htmlspecialchars($_POST['reactieid']));
				
				$sql_wisreactiecheck = mysql_query("SELECT * FROM paneel_FReactie WHERE id = '".$reactieid."' ");
				$num_wisreactiecheck = mysql_num_rows($sql_wisreactiecheck);
				 if($num_wisreactiecheck != 0)
				 {
					$lidcheckr = mysql_fetch_assoc($sql_wisreactiecheck);
					 
					$sql_lidx = mysql_query("SELECT * FROM paneel_FLeden WHERE habbonaam = '".$lidcheckr['habbonaam']."' AND departement = '".$departement['id']."' " );
					$lidx = mysql_fetch_assoc($sql_lidx);
					$num_lidx = mysql_num_rows($sql_lidx);
					if($num_lidx == 0)
					{
						$leveltjex = 0;
					}
					else
					{
						$leveltjex = $lidx['level'];
					}

					 if($lidcheckr['habbonaam'] == $_SESSION['habbonaam'])
					 {
						mysql_query("UPDATE paneel_FReactie SET prullenbak='1' WHERE id='".$reactieid."'");
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een eigen forum reactie gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					 }
					 else if($eigenlvl['reactie_wis'] == 1 && $leveltjex < $level)
					 {
						mysql_query("UPDATE paneel_FReactie SET prullenbak='1' WHERE id='".$reactieid."'");
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een forum reactie gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					}
				}
			}
		}
		if($reactie['habbonaam'] == $_SESSION['habbonaam'] || ($level > 3))
		{
                                    ?>
                                    <div style="float:right;">
                                        <a style="text-decoration:none;" id="show_reactie_aanpassen<?= $reactie['id'] ?>" onclick="document.getElementById('spoiler_reactie_aanpassen<?= $reactie['id'] ?>').style.display=''; document.getElementById('close_reactie_aanpassen<?= $reactie['id'] ?>').style.display=''; 
                                        document.getElementById('show_reactie_aanpassen<?= $reactie['id'] ?>').style.display='none'; "><input type="submit" class="submit" value="Edit"></a>
                                        <a style="text-decoration:none; display:none;" id="close_reactie_aanpassen<?= $reactie['id'] ?>>" onclick="document.getElementById('spoiler_reactie_aanpassen<?= $reactie['id'] ?>').style.display='none'; document.getElementById('close_reactie_aanpassen<?= $reactie['id'] ?>').style.display='none'; 
                                        document.getElementById('show_reactie_aanpassen<?= $reactie['id'] ?>').style.display=''; "><input type="submit" class="submit" value="Sluit" ></a>
                                    </div>
<?php
			if(!isset($_POST["reactie_edit"]))
			{
?>                                   
										<span id="spoiler_reactie_aanpassen<?= $reactie['id'] ?>" style="display:none;">
											<form id="formulier" method="post" action="">
												<input type="hidden" value="<?= $reactie['id'] ?>" name="reactieid" />
												<textarea id="edit_textarea_<?= $reactie['id'] ?>" style="width:820px; height:100px" class="Editor" name="reactie_boxx"><?= $reactie['reactie'] ?></textarea>
												<button class="submit crossthread-quote-insert hidden">Crossthread quotes invoegen</button>
												<input type="submit" class="submit" name="reactie_edit" id="reactie_edit" value="Opslaan">
											</form>
										</span>
<?php
			}
			else if(isset($_POST["reactie_edit"]))
			{
				if($_POST['reactie_boxx'] != '')
				{
					$reactie_id = mysql_real_escape_string(htmlspecialchars($_POST['reactieid']));
					$sql_reactiecheck = mysql_query("SELECT * FROM paneel_FReactie WHERE id = '".$reactie_id."' ");
					$num_reactiecheck = mysql_num_rows($sql_reactiecheck);
					if($num_reactiecheck != 0)
					{
						$reactie = $inputFilter->filterHTML($reactie);
						$reactie = mysql_real_escape_string($_POST["reactie_boxx"]);
						$reactie = substr($reactie, 0, 300000);
	
	
						mysql_query("UPDATE paneel_FReactie SET reactie = '".$reactie."', aangepast_datum = NOW() WHERE id='".$reactie_id."'");						
						mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft zij/haar reactie aangepast', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
						die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					}
				}											
			}
		}
?>
                                    <div style="clear:both;"></div>
                                    </td>
                                </tr>
							</table>
						</div>                        
<?php                 
	} //// reacties while
						
						
 //////////////////////////
 ////////////////////////// Reageren
 //////////////////////////
 	if($_SESSION['login'] == 1)
	{
		if($topic['reageerlevel'] <= $level)
		{
			if(!isset($_POST["reactie_verstuur"]))
			{
				if ($topic['closed'] == 1 && $level != 5)
				{
					echo "Dit topic is gesloten. Reageren is niet mogelijk";
				}
				else
				{	
					?>
					<a style="text-decoration:none;" id="show_reageren" onclick="document.getElementById('spoiler_Reageren').style.display=''; document.getElementById('show_reageren').style.display='none';"><input type="submit" class="submit" value="Reageren" style="float: right;"></a>

					<span id="spoiler_Reageren" style="display:none;">
					<?php if(mysql_num_rows($sql_reactie) != 0){ echo '<hr />'; } ?>
						<form id="reactie_form" method="post" action="">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td width="150px" align="center"><img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?php echo $_SESSION['habbonaam'] ?>&action=crr=6&direction=2&head_direction=2&gesture=sml&size=m"></td>
									<td align="center">
										<textarea id="reactie_textarea" style="width:820px; height:100px" class="Editor textarea-quote" name="reactie_box"></textarea><br />
										<button class="submit crossthread-quote-insert hidden">Crossthread quotes invoegen</button>
										<input type="submit" class="submit" name="reactie_verstuur" id="reactie_verstuur" value="Reactie plaatsen">
									</td>
								</tr>
							</table>
						</form>
					</span>
					<?php		
				}
			}
			else if(isset($_POST["reactie_verstuur"]))
			{
				if($_POST['reactie_box'] == '')
				{
                 	echo '
						<div style="margin-bottom:5px; cursor: pointer; -webkit-border-bottom-right-radius: 10px; -webkit-border-radius: 5px; -moz-border-radius: 5px;border-radius: 5px; margin-top:5px; padding:5px;  width:970px; height:auto; background-color:#900; color:#FFF; ">
						<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>Er is geen reactie geplaatst! Je hebt namelijk geen bericht ingevuld.</td>
								<td width="100px"><a href="##" onClick="history.go(-1); return false;"><input type="submit" class="submit" value="Opnieuw proberen"></a></td> 
							</tr>
						</table>	
						</div>
					';
				}
				else if($_POST['reactie_box'] != '')
				{
					$sql_laatstaangemaakt = mysql_query("SELECT * FROM paneel_FReactie WHERE habbonaam = '".$_SESSION['habbonaam']."' AND datum > DATE_SUB(NOW(), INTERVAL 10 SECOND) ");
					if(mysql_num_rows($sql_laatstaangemaakt) == 0)
					{
					$reactie = $inputFilter->filterHTML($reactie);
					$reactie = mysql_real_escape_string($_POST["reactie_box"]);
					$reactie = substr($reactie, 0, 300000);

					mysql_query("INSERT INTO `paneel_FReactie` (`topic`, `habbonaam`, `reactie`, `datum`) VALUES ('".$topic['id']."', '".$_SESSION['habbonaam']."', '".$reactie."', NOW())");
					
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft op een topic gereageerd', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					
					die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
					}
				}
			}
		} // mogen reageren
	} // ingelogd
 //////////////////////////
 ////////////////////////// Reageren end
 //////////////////////////
 
 
} ///// categorie toegang
else
{
	echo '<META http-equiv="refresh" content="0; URL=/forum">';
}

$sql_total_comments = mysql_query("SELECT * FROM paneel_FReactie WHERE prullenbak = '0' AND topic = '".$topic['id']."' ORDER BY datum ASC");
$total_comments 	= mysql_num_rows($sql_total_comments);
$pages 				= ceil($total_comments / $resultAmount);
/// $data 				= "<br /><div style='float:left;'>Pagina <b>" . ($page + 1) . "</b> van <b>" . $pages . "</b><br />";
$data 				= "<br /><div style='float:left;'>";
$parts 				= explode('/', $_SERVER['REQUEST_URI']);
$output 			= implode('/', array_slice($parts, 0, 5));

if ($page > 0)
{
	$data .= '<a class="pagination" href="http://' . $_SERVER['HTTP_HOST'] . '' . $output . '/' . ($page-1) . '"><input type="submit" class="submit" value="Vorige"></a>';
}
else
{
	$data .= '';/// vorige leeg
}

for ($i = 1; $i <= $pages; $i++)
{
	$page2 = $page + 1;
	
	if ($i != $page2)
	{
		$data .= '<a class="pagination" href="http://' . $_SERVER['HTTP_HOST'] . '' . $output . '/' . ($i - 1) . '"><input type="submit" class="submit" value="' . $i . '"></a> ';
	}
	else
	{
		$data .= '<input type="submit" class="submit" value="' . $i . '">'; /// getallen leeg
	}
}

if (($page + 1) <= ($total_comments / $resultAmount))
{
	$data .= '<a class="pagination" href="http://' . $_SERVER['HTTP_HOST'] . '' . $output . '/' . ($page + 1) . '"><input type="submit" class="submit" value="Volgende"></a>';
}
else
{
	$data .= ''; /// volgende leeg
}

$data .= "</div>";

echo $data;
?>