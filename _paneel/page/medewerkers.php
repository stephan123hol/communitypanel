<div style="position: absolute; top: 40px; left: 42px; z-index: 100;"><img src="/_paneel/assets/afbeelding/pasen/paasei2.png" /></div>
        <h1>Medewerkers</h1>
        <hr />   
         <form method="post">
		<table width="350">
			<tr>
				<td width="300"><input type="text" class="textbox" name="habbonaam" id="habbonaam" value="" placeholder="Zoek op Habbonaam.." style="width:300px;"/><br /></td>
				<td width="50"><input class="submit" type="submit" name="submit" value="Zoek"></td>
			</tr>
		</table>
	</form>
    
    <?php
if(isset($_POST['submit']) && !empty($_POST['habbonaam'])) {
    $habbonaam = mysql_real_escape_string(htmlspecialchars($_POST['habbonaam']));
	$sql_zoek = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam LIKE '%".$habbonaam."%' AND rang_nieuw > 1 GROUP BY habbonaam ORDER BY rang_op DESC LIMIT 50" );
	
	$aantelregelszoek = mysql_num_rows($sql_zoek);
		if($aantelregelszoek == 0){
				echo "Geen gelijkenis gevonden op '".$habbonaam ."' .";
				echo'<table width="100%" cellpadding="2" cellspacing="0">';
		}else{
			
	$i = 0;  ?>
    <table width="100%" cellpadding="2" cellspacing="0" bgcolor="#424242">
                <tr>
                    <td width="25%" style="color:#FFFFFF;"><b>Habbonaam</b></td>
                    <td width="35%" style="color:#FFFFFF;"><b>Rang</b></td>
                    <td width="40%" style="color:#FFFFFF;"><b>Rang op</b></td>
                </tr>
            </table>
            
            <table width="100%" cellpadding="2" cellspacing="0">
<?php
while($zoek = mysql_fetch_assoc($sql_zoek)) {
	if($i == 1){
 $bgcolor1 = '#EEEEEE';
 $i--;
}else{
 $bgcolor1 = '#FFFFFF';
 $i++;
}
?>
<tr>
<td width="25%" bgcolor="<?= $bgcolor1 ?>"><a href="/profiel/<?php $mijn_dossier = str_replace("?", "%3F", $zoek['habbonaam']); echo $mijn_dossier; ?>" style="text-decoration:none;"><b><?= $zoek['habbonaam'] ?></b></a></td>
<td width="35%" bgcolor="<?= $bgcolor1 ?>">
<?php

$sql_rangenzeu = mysql_query("SELECT * FROM paneel_rangverandering WHERE habbonaam='".$zoek['habbonaam']."' ORDER BY rang_op DESC LIMIT 1" );
$rowenzeu = mysql_fetch_assoc($sql_rangenzeu);

$sql_rangbijlvl = mysql_query("SELECT rang_naam FROM paneel_rangniveau WHERE rang_level='".$rowenzeu['rang_nieuw']."' ORDER BY id DESC LIMIT 1");
$rangbijlvl = mysql_fetch_assoc($sql_rangbijlvl);

	$sql_promotag = mysql_query("SELECT * FROM paneel_promotag WHERE habbonaam='".$rowenzeu['rang_door']."' ORDER BY id DESC LIMIT 1");
	$promotag = mysql_fetch_assoc($sql_promotag);
	$num_promotag = mysql_num_rows($sql_promotag);
	
	if ($num_promotag == 0)
	{
		echo $rangbijlvl['rang_naam'].' <i>'.$rowenzeu['rang_door'].'</i>';
	}
	else
	{
		echo $rangbijlvl['rang_naam'].' ['.$promotag['promotag'].']'; 
	}
}

 ?>
</td>
<td width="40%" bgcolor="<?= $bgcolor1 ?>"><?= datumrewrite($zoek['rang_op']) ?>  om <?= timerewrite($zoek['rang_op']) ?> uur</td>
</tr>
<?php
		} 
	}
	echo'</table>';

	include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/medewerkers.html";
?>
