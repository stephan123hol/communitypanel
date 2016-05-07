<?php
$toegangtotdezepagina = array("jenaam"); //// zonder hoofdletters!!

	if ((in_array(strtolower($_SESSION['habbonaam']), $toegangtotdezepagina)) || (in_array(strtolower($_SESSION['habbonaam']), $hfc_beheerder)) || ($tag == 'DE'))
	{ 
?>
        <h1>Rangniveau toevoegen</h1>
        <hr />

<?php 
		$sql_rangenlijst = mysql_query("SELECT * FROM paneel_rangniveau WHERE paneel='".$tag."' ORDER BY rang_level ASC");
		$sql_rangenlijstO = mysql_query("SELECT * FROM paneel_rangniveau WHERE paneel='".$tag."' ORDER BY rang_level ASC");
		$sql_rangenlijstD = mysql_query("SELECT * FROM paneel_rangniveau WHERE paneel='".$tag."' ORDER BY rang_level ASC");
		$num_rangenlijst = mysql_num_rows($sql_rangenlijst);
		if(!isset($_POST['opslaan']))
		{
?>
Let op! Het rang level bepaalt de hoogte van de rang en de restricties die daar bij horen. Zodra je een nieuwe rang toevoegt in het systeem, zal deze automatisch de hoogste rang krijgen. Aanbevolen is dus om het op volgorde van laag naar hoog in te voeren. Natuurlijk is het bij <a href="<?= $site ?>beheer/rangniveaus">Rangniveaus beheren</a> mogelijk om het level aan te passen. 

<form id="form1" method="post" action="">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td style="font-weight:bold;">Rang naam</td>
        <td><input type="text" class="textbox" name="rangnaam" id="rangnaam" value="" /></td>
    </tr>
    <tr>
    	<td style="font-weight:bold;">Promoveren tot en met</td>
        <td><select name="promoveren_tot" >
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
    	<td></td>
        <td><input type="submit" class="submit" name="opslaan" id="opslaan" value="opslaan"></td>
    </tr>
</table>    
</form>

<?php
		}
		else if(isset($_POST['opslaan']))
		{
			if(empty($_POST['rangnaam']) or ($_POST['degraderen_tot'] == "") or ($_POST['ontslaan_tot'] == "") or ($_POST['promoveren_tot'] == ""))
			{
				echo 'Niet alle velden zijn ingevuld. Probeer het opnieuw.';
			}
			else
			{
				$rang_naam = mysql_real_escape_string(htmlspecialchars($_POST["rangnaam"]));
				$degraderen_tot = mysql_real_escape_string(htmlspecialchars($_POST["degraderen_tot"]));
				$ontslaan_tot = mysql_real_escape_string(htmlspecialchars($_POST["ontslaan_tot"]));
				$promoveren_tot = mysql_real_escape_string(htmlspecialchars($_POST["promoveren_tot"]));
				$rang_level = $num_rangenlijst + 1;
				$warn = mysql_real_escape_string(htmlspecialchars($_POST["warn"]));
				$trainingen = mysql_real_escape_string(htmlspecialchars($_POST["training"]));


				$sql_ledenhfc = mysql_query("SELECT * FROM paneel_rangniveau WHERE rang_naam='".$rang_naam."' ");
				$num_ledenhfc = mysql_num_rows($sql_ledenhfc);
				if ($num_ledenhfc == '1')
				{
					echo 'De opgegeven rang naam bestaat al in het systeem.';
				}
				elseif ($num_ledenhfc == '0')
				{
			
					mysql_query("INSERT INTO `paneel_rangniveau` (`rang_naam`, `rang_level`, `promoveren_tot`, `degraderen_tot`, `ontslaan_tot`, `warn`, `trainingen`) VALUES ('".$rang_naam."', '".$rang_level."', '".$promoveren_tot."', '".$degraderen_tot."', '".$ontslaan_tot."', '".$warn."', '".$trainingen."')");
					mysql_query("INSERT INTO `paneel_logs` (`habbonaam`, `date`, `actie`, `ip`, `UA`, `datum`) VALUES ('".$_SESSION["habbonaam"]."', '".time()."', 'heeft een rang toegevoegd.', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', NOW() )");
					echo '<META http-equiv="refresh" content="0; URL=/beheer/rangniveaus">';
				}
			}
		}
	}
	else
	{
		echo '<META http-equiv="refresh" content="0; URL=/">';
	}
?>