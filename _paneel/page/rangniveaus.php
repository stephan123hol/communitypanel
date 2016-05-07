<?php
$toegangtotdezepaginaopties = array("jenaam"); //// zonder hoofdletters!!

	if ((in_array(strtolower($_SESSION['habbonaam']), $bedrijf_beheerder)) or  (in_array(strtolower($_SESSION['habbonaam']), $hfc_beheerder)))
	{ 
?>
        <img src="http://www.panelify.com/_paneel/assets/afbeelding/icon-aanpassen.png" align="left"  /><h1>Rangniveaus beheren</h1>
        <hr />

<?php 
		if($_GET['edit'] == '' && $_GET['del'] == '')
		{
			$sql_rangenlijst = mysql_query("SELECT * FROM paneel_rangniveau ORDER BY rang_level ASC");
?>
	<table width="100%" border="0" cellpadding="1" cellspacing="0" style="background-color:#6C6C6C; color:#FFFFFF; font-size:9px;">
	  	<tr>
			<td width="25%">Rang naam</td>
			<td width="15%">Promoveren tot</td>
			<td width="15%">Ontslaan tot</td>
			<td width="15%">Degraderen tot</td>
			<td width="10%">Warn</td>
			<td width="10%">Training</td>
			<td width="5%"></td>
			<td width="5%"></td>
	  	</tr>
		</table>
<?php		
			while($rangenlijst = mysql_fetch_assoc($sql_rangenlijst)) 
			{
?>		
		<table width="100%" border="0" cellpadding="1" cellspacing="0" onMouseover="this.style.backgroundColor='lightgray';" onMouseout="this.style.backgroundColor='#FFFFFF';">
            <tr>
                <td width="25%"><?= $rangenlijst['rang_naam'] ?></td>
                <td width="15%"><?php
				if ($rangenlijst['promoveren_tot'] == 0){
					echo '<i>nog niet</i>';
				}else{
				$sql_rangbijlvl = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijst['promoveren_tot'] ."' ");
				$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
				 echo $rangbijlvl['rang_naam'];
				}
				 ?></td>
                <td width="15%"><?php
				if ($rangenlijst['ontslaan_tot'] == 0){
					echo '<i>nog niet</i>';
				}else{
				$sql_rangbijlvl = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijst['ontslaan_tot'] ."' ");
				$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
				 echo $rangbijlvl['rang_naam'];
				}
				 ?></td>
                <td width="15%"><?php
				if ($rangenlijst['degraderen_tot'] == 0){
					echo '<i>nog niet</i>';
				}else{
				$sql_rangbijlvl = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijst['degraderen_tot'] ."' ");
				$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);
				 echo $rangbijlvl['rang_naam'];
				}
				 ?></td>

                <td width="10%"><?php
				if ($rangenlijst['warn'] == 0){
					echo '<i>Nog niet</i>';
				}else{
					 echo '<i>Ja</i>';
				}
				 ?></td>

                <td width="10%"><?php
				if ($rangenlijst['trainingen'] == 0){
					echo '<i>Nog niet</i>';
				}else{
					 echo '<i>Ja</i>';
				}
				 ?></td>
				
				<?php
				if ( (in_array(strtolower($_SESSION['habbonaam']), $toegangtotdezepaginaopties)) || (in_array(strtolower($_SESSION['habbonaam']), $hfc_beheerder))  || ($tag == 'DE') ) {
				?>
                <td width="5%"><a href="/beheer/rangniveau/edit/<?= $rangenlijst['id'] ?>"><img border="0" src="http://panelify.com/images/fancenter/icons/tools_edit.gif" /></a></td>
                <td width="5%"><a href="/beheer/rangniveau/wis/<?= $rangenlijst['id'] ?>"><img border="0" src="http://panelify.com/images/fancenter/icons/v22_3.gif" /></a></td>
				<?php
				}
				?>
          	</tr>
		</table>
		
<?php 		
			}
//////////////
		}
		if($_GET['del'] == '1')
		{
			$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
			$sql_deletegegevens = mysql_query("SELECT * FROM paneel_rangniveau WHERE id='".$id."' ");
			$num_ledenhfcid = mysql_num_rows($sql_deletegegevens);
			$deletegegevens = mysql_fetch_assoc($sql_deletegegevens);			
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven rang bestaat niet in het systeem en kun je dus ook niet wissen.';
			}
			elseif ($num_ledenhfcid == '1')
			{
				$balksql = mysql_query(" SELECT  * FROM paneel_rangniveau WHERE rang_level > ".$deletegegevens['rang_level']." ORDER BY id DESC");
				$numbalk = mysql_fetch_assoc($balksql);
				$idbalk=1;
				while($idbalk<=$numbalk['id'])
				{
					mysql_query(" UPDATE paneel_rangniveau SET rang_level=rang_level-1 WHERE id = '".$idbalk."' AND rang_level > ".$deletegegevens['rang_level']."");
					if ($numbalk['promoveren_tot'] != 0)
					{
						mysql_query(" UPDATE paneel_rangniveau SET promoveren_tot=promoveren_tot-1 WHERE id = '".$idbalk."' AND rang_level > ".$deletegegevens['rang_level']."");
					}
					if ($numbalk['degraderen_tot'] != 0)
					{
					mysql_query(" UPDATE paneel_rangniveau SET degraderen_tot=degraderen_tot-1 WHERE id = '".$idbalk."' AND rang_level > ".$deletegegevens['rang_level']."");
					}
					if ($numbalk['ontslaan_tot'] != 0)
					{
					mysql_query(" UPDATE paneel_rangniveau SET ontslaan_tot=ontslaan_tot-1 WHERE id = '".$idbalk."' AND rang_level > ".$deletegegevens['rang_level']."");
					}
					$idbalk++;
				}
				mysql_query("DELETE FROM paneel_rangniveau WHERE  id='".$id."' ");
				mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een rang gewist.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
				echo '<META http-equiv="refresh" content="0; URL=/beheer/rangniveaus">';
			}

///////////////
		}
		else if ($_GET['edit'] == '1')
		{
			$id = mysql_real_escape_string(htmlspecialchars($_GET['id']));
			$sgl_ledenhfcid = mysql_query("SELECT * FROM paneel_rangniveau WHERE id='".$id."' ");
			$num_ledenhfcid = mysql_num_rows($sgl_ledenhfcid);
			if ($num_ledenhfcid == '0')
			{
				echo 'De opgegeven rang bestaat niet in het systeem en kun je dus ook niet aanpassen.';
			}
			elseif ($num_ledenhfcid == '1')
			{
				if(!isset($_POST['opslaan']))
				{
					$sql_rangenlijst = mysql_query("SELECT * FROM paneel_rangniveau ORDER BY rang_level ASC");
					$sql_rangenlijstO = mysql_query("SELECT * FROM paneel_rangniveau ORDER BY rang_level ASC");
					$sql_rangenlijstD = mysql_query("SELECT * FROM paneel_rangniveau ORDER BY rang_level ASC");
					$num_rangenlijst = mysql_num_rows($sql_rangenlijst);
					$sql_ranggegevens = mysql_query("SELECT * FROM paneel_rangniveau WHERE id='".$id."'");
					$rangenlijstje = mysql_fetch_assoc($sql_ranggegevens);
?>
<form id="form1" method="post" action="">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td style="font-weight:bold;">Rang naam</td>
        <td><input type="text" class="textbox" name="rangnaam" id="rangnaam" value="<?= $rangenlijstje['rang_naam'] ?>" /></td>
    </tr>
	<tr>
    	<td style="font-weight:bold;">Rang level</td>
        <td><input type="text" class="textbox" name="level" id="level" value="<?= $rangenlijstje['rang_level'] ?>" /></td>
    </tr>
    <tr>
    	<td style="font-weight:bold;">Promoveren tot en met</td>
        <td><select name="promoveren_tot" >
        <?php if ($rangenlijstje['promoveren_tot'] != 0){ ?>
        	<option value="<?= $rangenlijstje['promoveren_tot'] ?>"><?php		
				$sql_levelnaam1 = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijstje['promoveren_tot']."' ");
				$levelnaam1 = mysql_fetch_assoc($sql_levelnaam1);
				echo $levelnaam1['rang_naam'];
?>
			</option>
<?php } ?>
        	<option value="0">Mag nog niet promoveren</option>
<?php       
			while ($rangenlijst_P = mysql_fetch_assoc($sql_rangenlijst))
			{
        		echo '<option value="'.$rangenlijst_P['rang_level'].'">'.$rangenlijst_P['rang_naam'].'</option>';
			}
 ?>       </select>
        </td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Ontslaan tot en met</td>
        <td><select name="ontslaan_tot">
        <?php if ($rangenlijstje['ontslaan_tot'] != 0){ ?>
        	<option value="<?= $rangenlijstje['ontslaan_tot'] ?>"><?php		
				$sql_levelnaam2 = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijstje['ontslaan_tot']."' ");
				$levelnaam2 = mysql_fetch_assoc($sql_levelnaam2);
				echo $levelnaam2['rang_naam'];
?></option>
<?php } ?>
<option value="0">Mag nog niet ontslaan</option>
<?php       
			while ($rangenlijst_O = mysql_fetch_assoc($sql_rangenlijstO))
			{
        		echo '<option value="'.$rangenlijst_O['rang_level'].'">'.$rangenlijst_O['rang_naam'].'</option>';
			}
 ?>       </select>
        </td>
	</tr>
    <tr>
    	<td style="font-weight:bold;">Degraderen tot en met</td>
        <td><select name="degraderen_tot" >
        <?php if ($rangenlijstje['degraderen_tot'] != 0){ ?>
        <option value="<?= $rangenlijstje['degraderen_tot'] ?>"><?php		
				$sql_levelnaam3 = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_level='".$rangenlijstje['degraderen_tot']."' ");
				$levelnaam3 = mysql_fetch_assoc($sql_levelnaam3);
				echo $levelnaam3['rang_naam'];
?></option>
<?php } ?>

        	<option value="0">Mag nog niet degraderen</option>
<?php       
			while ($rangenlijst_D = mysql_fetch_assoc($sql_rangenlijstD))
			{
        		echo '<option value="'.$rangenlijst_D['rang_level'].'">'.$rangenlijst_D['rang_naam'].'</option>';
			}
 ?>       </select>
        </td>
	</tr>
    <tr bgcolor="#F1F1F2">
    	<td style="font-weight:bold;">Waarschuwingen geven</td>
        <td><input type="radio" name="warn" value="0" CHECKED> Nee - <input type="radio" name="warn" value="1"> Ja</td>
    </tr>
	<tr>
    	<td style="font-weight:bold;">Trainingen geven</td>
        <td><input type="radio" name="training" value="0" CHECKED> Nee - <input type="radio" name="training" value="1"> Ja</td>
    </tr>
	<tr>
    	<td><input type="hidden" name="id" id="id" value="<?= $rangenlijstje['id'] ?>"></td>
        <td><input type="submit" class="submit" name="opslaan" id="opslaan" value="opslaan"></td>
    </tr>
</table>    
</form>

           <?php
				}
				else if(isset($_POST['opslaan']))
				{
					if(empty($_POST['rangnaam']) or ($_POST['degraderen_tot'] == "") or ($_POST['ontslaan_tot'] == "") or ($_POST['promoveren_tot'] == "") or empty($_POST['level']))
					{
						echo 'Niet alle velden zijn ingevuld. Probeer het opnieuw.';
					}
					else
					{
						$rang_id = mysql_real_escape_string(htmlspecialchars($_POST["id"]));
						$rang_naam = mysql_real_escape_string(htmlspecialchars($_POST["rangnaam"]));
						$degraderen_tot = mysql_real_escape_string(htmlspecialchars($_POST["degraderen_tot"]));
						$ontslaan_tot = mysql_real_escape_string(htmlspecialchars($_POST["ontslaan_tot"]));
						$promoveren_tot = mysql_real_escape_string(htmlspecialchars($_POST["promoveren_tot"]));
						$rang_level = mysql_real_escape_string(htmlspecialchars($_POST["level"]));
						$warn = mysql_real_escape_string(htmlspecialchars($_POST["warn"]));
						$training = mysql_real_escape_string(htmlspecialchars($_POST["training"]));
				
						$sql_editrang_oud = mysql_query("SELECT * FROM paneel_rangniveau WHERE id='".$rang_id."' ");
						$num_editrang_oud = mysql_num_rows($sql_editrang_oud);
						$editrang_oud = mysql_fetch_assoc($sql_editrang_oud);			
				
						if ($editrang_oud['rang_level'] > $rang_level)
						{
							$ranglvl_oud1= $editrang_oud['rang_level'] - 1;
							$verhogen_sql = mysql_query(" SELECT  * FROM paneel_rangniveau WHERE rang_level BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ORDER BY id DESC");
							$verhogen = mysql_fetch_assoc($verhogen_sql);
							$idbalk_verhogen=1;
							while($idbalk_verhogen<=$verhogen['id'])
							{
								mysql_query(" UPDATE paneel_rangniveau SET rang_level=rang_level+1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								if ($verhogen['promoveren_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET promoveren_tot=promoveren_tot+1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								}
								if ($verhogen['ontslaan_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET ontslaan_tot=ontslaan_tot+1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								}
								if ($verhogen['degraderen_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET promoveren_tot=degraderen_tot+1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								}
								$idbalk_verhogen++;
							}
							
							$functieverhogen_sql = mysql_query(" SELECT  * FROM paneel_rangverandering ORDER BY id DESC");
							$functieverhogen = mysql_fetch_assoc($functieverhogen_sql);
							$idbalk_functieverhogen=1;
							while($idbalk_functieverhogen<=$functieverhogen['id'])
							{
								if ($functieverhogen['rang_oud'] != 0)
								{
									//mysql_query(" UPDATE paneel_rangverandering SET rang_oud=rang_oud+1 WHERE id = '".$idbalk_functieverhogen."' AND paneel='".$tag."' AND rang_oud BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								}
								if ($functieverhogen['rang_nieuw'] != 0)
								{
									//mysql_query(" UPDATE paneel_rangverandering SET rang_nieuw=rang_nieuw+1 WHERE id = '".$idbalk_functieverhogen."' AND paneel='".$tag."' AND rang_nieuw BETWEEN ".$rang_level." AND ".$ranglvl_oud1." ");
								}
								$idbalk_functieverhogen++;
							}
							
							//mysql_query(" UPDATE paneel_rangverandering SET rang_oud='".$rang_level."' WHERE rang_oud= '".$editrang_oud['rang_level'] ."' AND  paneel='".$tag."' ");
							//mysql_query(" UPDATE paneel_rangverandering SET rang_nieuw='".$rang_level."' WHERE rang_nieuw= '".$editrang_oud['rang_level'] ."' AND  paneel='".$tag."' ");
							
							mysql_query(" UPDATE paneel_rangniveau SET ontslaan_tot='".$ontslaan_tot."', promoveren_tot='".$promoveren_tot."', degraderen_tot='".$degraderen_tot."', rang_level='".$rang_level."', rang_naam='".$rang_naam."' WHERE id = '".$rang_id."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een rang aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; URL=/beheer/rangniveaus">';
						}
						/////////////////////////////////////////////////////////////////
						else if ($editrang_oud['rang_level'] < $rang_level)
						{
							$ranglvl_oud2= $editrang_oud['rang_level'] + 1;
							$verhogen_sql = mysql_query(" SELECT  * FROM paneel_rangniveau WHERE rang_level BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ORDER BY id DESC");
							$verhogen = mysql_fetch_assoc($verhogen_sql);
							$idbalk_verhogen=1;
							while($idbalk_verhogen<= $verhogen['id'])
							{
								mysql_query(" UPDATE paneel_rangniveau SET rang_level=rang_level-1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								if ($verhogen['promoveren_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET promoveren_tot=promoveren_tot-1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								}
								if ($verhogen['ontslaan_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET ontslaan_tot=ontslaan_tot-1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								}
								if ($verhogen['degraderen_tot'] != 0)
								{
									mysql_query(" UPDATE paneel_rangniveau SET promoveren_tot=degraderen_tot-1 WHERE id = '".$idbalk_verhogen."' AND rang_level BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								}
								$idbalk_verhogen++;
							}
							
							$functieverhogen_sql = mysql_query(" SELECT  * FROM paneel_rangverandering ORDER BY id DESC");
							$functieverhogen = mysql_fetch_assoc($functieverhogen_sql);
							$idbalk_functieverhogen=1;
							while($idbalk_functieverhogen<= $functieverhogen['id'])
							{
								if ($functieverhogen['rang_oud'] != 0)
								{
									//mysql_query(" UPDATE paneel_rangverandering SET rang_oud=rang_oud-1 WHERE id = '".$idbalk_functieverhogen."' AND paneel='".$tag."' AND rang_oud BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								}
								if ($functieverhogen['rang_nieuw'] != 0)
								{
									//mysql_query(" UPDATE paneel_rangverandering SET rang_nieuw=rang_nieuw-1 WHERE id = '".$idbalk_functieverhogen."' AND paneel='".$tag."' AND rang_nieuw BETWEEN ".$ranglvl_oud2." AND ".$rang_level." ");
								}
								$idbalk_functieverhogen++;
							}

							//mysql_query(" UPDATE paneel_rangverandering SET rang_oud='".$rang_level."' WHERE rang_oud= '".$editrang_oud['rang_level'] ."' AND  paneel='".$tag."' ");
							//mysql_query(" UPDATE paneel_rangverandering SET rang_nieuw='".$rang_level."' WHERE rang_nieuw= '".$editrang_oud['rang_level'] ."' AND  paneel='".$tag."' ");

							mysql_query(" UPDATE paneel_rangniveau SET ontslaan_tot='".$ontslaan_tot."', promoveren_tot='".$promoveren_tot."', degraderen_tot='".$degraderen_tot."', rang_level='".$rang_level."', rang_naam='".$rang_naam."' WHERE id = '".$rang_id."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een rang aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; URL=/beheer/rangniveaus">';
						}
						/////////////////////////////////////////////////////////////////
						else if ($editrang_oud['rang_level'] == $rang_level)
						{
							mysql_query(" UPDATE paneel_rangniveau SET warn='".$warn."', trainingen='".$training."', ontslaan_tot='".$ontslaan_tot."', promoveren_tot='".$promoveren_tot."', degraderen_tot='".$degraderen_tot."', rang_level='".$rang_level."', rang_naam='".$rang_naam."' WHERE id = '".$rang_id."'");
							mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', 'heeft een rang aangepast.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
							echo '<META http-equiv="refresh" content="0; URL=/beheer/rangniveaus">';
						}	
						
					} /// of opgeslagen velden leeg zijn					
				} //// opslaan
			} /// of opgegeven ID bestaat
		} /// of er een actie is
		
	} /// rangniveau
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>