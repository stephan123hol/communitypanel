<center>
	<?php
	$filesBanners = glob($_SERVER["DOCUMENT_ROOT"] . '/_paneel/assets/afbeelding/donatie/banners/active/*.*');
	$bannerPath = array_rand($filesBanners);
	
	preg_match("/[^\/]+$/", $filesBanners[$bannerPath], $matches);
	$banner = $matches[0];
	
	echo '<img src="/_paneel/assets/afbeelding/donatie/banners/active/' . $banner . '" />';
	?>
</center><br />
<!--<div onclick="location.href='http://iop.imperialmaffia.nl/'" class="forum-topbar">
	<table width="100%" border="0" height="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td style="padding-left:25px;">Imperial Opinion Program</td>
				<td align="right"><img height="40px" width="auto" src="http://i.imgur.com/jDa7b1G.png" border="0"></td>
			</tr>
		</tbody>
	</table>
</div>
<div onclick="location.href='http://iop.imperialmaffia.nl/'" style="height: 60px; margin-top: 5px; cursor: pointer; border-radius: 5px; padding: 5px; border: 1px solid rgb(214, 214, 214); background-color: rgb(255, 255, 255); margin-bottom: 30px;" onmouseover="this.style.border = '1px solid #666666'" onmouseout="this.style.border = '1px solid #d6d6d6'">	
	<table width="100%" height="60px" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td width="50px" align="center"><img src="/_paneel/assets/afbeelding/forum_icon_7.gif"> </td>
				<td>
					<b>Neem nu deel aan het Imperial Opinion Program en laat je mening horen!</b><br>
					<i style="word-wrap: break-word;">Dit programma is openbaar voor iedereen, leden en niet-leden van Imperial Maffia. We verwachten dat jullie de enquetes zo duidelijk en uitgebreid mogelijk invullen. Je kan hier klikken of naar http://iop.imperialmaffia.nl gaan!</i> <br> 
				</td>
			</tr>
		</tbody>
	</table>
</div>-->
<?php
if ($_SESSION["habbonaam"] == "Praelu2s")
{
	$department = null;
	$category   = null;
	$topic 		= null;
	
	if (!isset($_GET['departement']) || (isset($_GET['departement']) && empty($_GET['departement'])))
	{
		
	}
}
else
{
	$userLevel = isset($_SESSION["habbonaam"]) ? $user->getUserLevel($_SESSION["habbonaam"]) : 0;
	
	if($_GET['departement'] == "")
	{
		$sql_departement = mysql_query("SELECT id, zichtbaarheid, naam, badge FROM paneel_FDepartement WHERE prullenbak = '0' ORDER BY ordening ASC, id ASC  " );
		while($departement = mysql_fetch_assoc($sql_departement))
		{
			if($departement['zichtbaarheid'] == 0) 
			//// Toegankelijk voor iedereen, ook uitgelogden
			{
				include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
			}
			else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] == 1) 
			//// Toegankelijk voor iedereen die ingelogd is
			{
				include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
			}
			if($departement['zichtbaarheid'] == 2 && $_SESSION['login'] == 1) 
			//// Toegankelijk voor bepaalde mensen
			{
				$sql_leden = mysql_query("SELECT id FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
				$num_leden = mysql_num_rows($sql_leden);
				if($num_leden != 0 || $userLevel > 0)
				{
					if (($departement["id"] != 21 && $departement["id"] != 221) || (($departement["id"] == 21 || $departement["id"] == 221) && ($userLevel > 2 || $num_leden != 0)))
					{
						include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
					}
				}
			}
		}
	}
	else if($_GET['departement'] != "" && $_GET['categorie'] == "")
	{
		$link_departement = mysql_real_escape_string(htmlspecialchars(rawurldecode($_GET['departement'])));
		echo ' <a href="/forum" style="padding-left:10px;"> Forum overzicht </a> > <a href="/forum/'.rawurlencode($link_departement).'"> '.$link_departement.' </a><hr /> ';
		
		$sql_link_departement = mysql_query("SELECT id, zichtbaarheid, naam, badge FROM paneel_FDepartement WHERE prullenbak = '0' AND naam = '".$link_departement."' LIMIT 1" );
		$num_link_departement = mysql_num_rows($sql_link_departement);
		
		if($num_link_departement == 0)
		{
			echo "Het opgegeven departement bestaat niet, helaas!";
		}
		else
		{
			$departement = mysql_fetch_assoc($sql_link_departement);
			if($departement['zichtbaarheid'] == 2  && $_SESSION['login'] == 1) 
			//// Toegankelijk voor bepaalde mensen
			{
				$sql_leden = mysql_query("SELECT id FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
				$num_leden = mysql_num_rows($sql_leden);
				if($num_leden != 0 || $userLevel > 0)
				{
					include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
				}
				else
				{
					echo '<META http-equiv="refresh" content="0; URL=/forum">';
				}
			}
			else if($departement['zichtbaarheid'] == 2 && $_SESSION['login'] != 1) 
			{
				echo '<META http-equiv="refresh" content="0; URL=/forum">';
			}
			else if($departement['zichtbaarheid'] == 0) 
			//// Toegankelijk voor iedereen, ook uitgelogden
			{
				include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
			}
			else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] == 1) 
			//// Toegankelijk voor iedereen die ingelogd is
			{
				include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_departement.php');
			}
			else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] != 1) 
			{
				echo '<META http-equiv="refresh" content="0; URL=/forum">';
			}
		}
	}
	
	 
//////////////////////////////	
//////////////////////////////	
//////////////////////////////	 Categorie niveau
//////////////////////////////	
//////////////////////////////	
	
	else if($_GET['departement'] != "" && $_GET['categorie'] != "" && $_GET['topic'] == "")
	{
		$link_departement = mysql_real_escape_string(htmlspecialchars(rawurldecode($_GET['departement'])));
		$link_categorie = mysql_real_escape_string(htmlspecialchars($_GET['categorie']));
		echo ' <a href="/forum" style="padding-left:10px;"> Forum overzicht </a> > <a href="/forum/'.rawurlencode($link_departement).'"> '.$link_departement.' </a> > <a href="/forum/'.rawurlencode($link_departement).'/'.rawurlencode($link_categorie).'"> '.$link_categorie.' </a><hr /> ';
		
		$sql_link_departement = mysql_query("SELECT id, zichtbaarheid, naam, badge FROM paneel_FDepartement WHERE prullenbak = '0' AND naam = '".$link_departement."' LIMIT 1" );
		$num_link_departement = mysql_num_rows($sql_link_departement);
		
		if($num_link_departement == 0)
		{
			echo "Het opgegeven departement bestaat niet, helaas!";
		}
		else
		{
			$departement = mysql_fetch_assoc($sql_link_departement);
			$sql_link_categorie = mysql_query("SELECT id, toegang, naam, omschrijving, topic_maken, `order` FROM paneel_FCategorie WHERE prullenbak = '0' AND naam = '".$link_categorie."' AND departement = '".$departement['id']."' LIMIT 1" );
			$num_link_categorie = mysql_num_rows($sql_link_categorie);
			if($num_link_categorie == 0)
			{
				echo "De opgegeven categorie bestaat niet, helaas! <br />";
				echo $_GET['categorie'] . ";";
			}
			else
			{
				$categorie = mysql_fetch_assoc($sql_link_categorie);
				
				if($departement['zichtbaarheid'] == 2  && $_SESSION['login'] == 1) 
				//// Toegankelijk voor bepaalde mensen
				{
					$sql_leden = mysql_query("SELECT id FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
					$num_leden = mysql_num_rows($sql_leden);
					if($num_leden != 0 || $userLevel > 0)
					{
						include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_categorie.php');
					}
					else
					{
						echo '<META http-equiv="refresh" content="0; URL=/forum">';
					}
				}
				else if($departement['zichtbaarheid'] == 2 && $_SESSION['login'] != 1) 
				{
					echo '<META http-equiv="refresh" content="0; URL=/forum">';
				}
				else if($departement['zichtbaarheid'] == 0) 
				//// Toegankelijk voor iedereen, ook uitgelogden
				{
					include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_categorie.php');
				}
				else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] == 1) 
				//// Toegankelijk voor iedereen die ingelogd is
				{
					include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_categorie.php');
				}
				else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] != 1) 
				{
					echo '<META http-equiv="refresh" content="0; URL=/forum">';
				}
			}
		}
	}
	
	
//////////////////////////////	
//////////////////////////////	
//////////////////////////////	 Topic niveau
//////////////////////////////	
//////////////////////////////	

	else if($_GET['departement'] != "" && $_GET['categorie'] != "" && $_GET['topic'] != "")
	{
		$link_departement = mysql_real_escape_string(htmlspecialchars(rawurldecode($_GET['departement'])));
		$link_categorie = mysql_real_escape_string(htmlspecialchars(rawurldecode($_GET['categorie'])));
		$link_topic_id = mysql_real_escape_string(htmlspecialchars($_GET['topic']));
		
		$sql_link_departement = mysql_query("SELECT id, naam, badge, zichtbaarheid FROM paneel_FDepartement WHERE prullenbak = '0' AND naam = '".$link_departement."' LIMIT 1" );
		$num_link_departement = mysql_num_rows($sql_link_departement);
		
		if($num_link_departement == 0)
		{
			echo "Het opgegeven departement bestaat niet, helaas!";
		}
		else
		{
			$departement = mysql_fetch_assoc($sql_link_departement);
			$sql_link_categorie = mysql_query("SELECT id, naam, omschrijving FROM paneel_FCategorie WHERE prullenbak = '0' AND naam = '".$link_categorie."' AND departement = '".$departement['id']."' LIMIT 1" );
			$num_link_categorie = mysql_num_rows($sql_link_categorie);
			if($num_link_categorie == 0)
			{
				echo "De opgegeven categorie bestaat niet, helaas!";
			}
			else
			{
				$categorie = mysql_fetch_assoc($sql_link_categorie);
				$sql_topic = mysql_query("SELECT * FROM paneel_FTopic WHERE id = '".$link_topic_id."' AND prullenbak = '0' AND categorie = '".$categorie['id']."' ORDER BY id DESC LIMIT 1 ");
				$num_topic = mysql_num_rows($sql_topic);
				
				if($num_topic == 0)
				{
					echo '<META http-equiv="refresh" content="0; URL=/forum">';
				}
				else
				{
					$topic = mysql_fetch_assoc($sql_topic);
					echo ' <a href="/forum" style="padding-left:10px;"> Forum overzicht </a> > <a href="/forum/'.rawurlencode($link_departement).'"> '.$link_departement.' </a> > <a href="/forum/'.rawurlencode($link_departement).'/'.rawurlencode($link_categorie).'"> '.$link_categorie.' </a> > <a href="/forum/'.rawurlencode($link_departement).'/'.rawurlencode($link_categorie).'/'.$link_topic_id.'"> '.$topic['titel'].' </a><hr /> ';
	
					if($departement['zichtbaarheid'] == 2  && $_SESSION['login'] == 1) 
					//// Toegankelijk voor bepaalde mensen
					{
						$sql_leden = mysql_query("SELECT id FROM paneel_FLeden WHERE habbonaam = '".$_SESSION['habbonaam']."' AND departement = '".$departement['id']."' " );
						$num_leden = mysql_num_rows($sql_leden);
						if($num_leden != 0 || $userLevel > 0)
						{
							include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_topic.php');
						}
						else
						{
							echo '<META http-equiv="refresh" content="0; URL=/forum">';
						}
					}
					else if($departement['zichtbaarheid'] == 2 && $_SESSION['login'] != 1) 
					{
						echo '<META http-equiv="refresh" content="0; URL=/forum">';
					}
					else if($departement['zichtbaarheid'] == 0) 
					//// Toegankelijk voor iedereen, ook uitgelogden
					{
						include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_topic.php');
					}
					else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] == 1) 
					//// Toegankelijk voor iedereen die ingelogd is
					{
						include($_SERVER["DOCUMENT_ROOT"] . '/_paneel/include/forum_topic.php');
					}
					else if($departement['zichtbaarheid'] == 1 && $_SESSION['login'] != 1) 
					{
						echo '<META http-equiv="refresh" content="0; URL=/forum">';
					}
				}
			}
		}
	}
	
	
//////////////////////////
////////////////////////// Departement aanmaken
//////////////////////////
if($_GET['departement'] == "")
{
if($_SESSION['login'] == 1 && $userLevel > 1)
{
	$sql_laatstaangemaakt = mysql_query("SELECT id FROM paneel_FDepartement WHERE habbonaam = '".$_SESSION['habbonaam']."' AND datum > DATE_SUB(NOW(), INTERVAL 10 SECOND) ");
	if(mysql_num_rows($sql_laatstaangemaakt) == 0)
	{

	if(!isset($_POST["departement_verstuur"]))
	{
?>
<a style="text-decoration:none;" id="show_departementmaken" onclick="document.getElementById('spoiler_departementmaken').style.display=''; document.getElementById('show_departementmaken').style.display='none';"><input type="submit" class="submit" value="Departement aanmaken" style="float: right;"></a>

<span id="spoiler_departementmaken" style="display:none;">
<form id="formulier" method="post" action="">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="250px" style="font-weight:bold;">Naam:</td>
			<td><input type="text" style="width:600px;" class="textbox" name="departement_naam"></td>
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
			<td width="250px" style="font-weight:bold;">Badge link:<br />
			 <i style="color:#CCC; font-weight:normal;">Te vinden in een groeps home widget</i></td>
			<td><input type="text" style="width:600px;" class="textbox" name="departement_badge"></td>
		</tr>
		<tr>   
			<td colspan="2">    
				<input type="submit" class="submit" name="departement_verstuur" id="departement_verstuur" value="Departement aanmaken">
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
				
				$sql_naamxxcheck = mysql_query("SELECT * FROM paneel_FDepartement WHERE naam = '".$naam."'") or die(mysql_error());
				$num_naamxxcheck = mysql_num_rows($sql_naamxxcheck);
				if($num_naamxxcheck != 0)
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
					
					mysql_query("INSERT INTO `paneel_FDepartement` (`naam`, `habbonaam`, `zichtbaarheid`, `badge`, `datum`) VALUES ('".$naam."', '".$_SESSION['habbonaam']."', '".$zichtbaarheid."', '".$badge."', NOW())");
					
					
					$sql_nieuwdepartement= mysql_query("SELECT id FROM paneel_FDepartement WHERE prullenbak = '0' ORDER BY id DESC LIMIT 1 ");
					$nieuwdepartement = mysql_fetch_assoc($sql_nieuwdepartement);

					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een departement aangemaakt', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0">';
											
				}
			}
		}	
	}
	}
}
}
 //////////////////////////
 ////////////////////////// Departement aanmaken end
 //////////////////////////
		
?>
<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/forum.html"; ?>