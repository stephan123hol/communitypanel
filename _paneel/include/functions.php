<?php
include_once('config.php');
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
function insertlog($mid,$actie,$ip,$UA,$link){
	$mid = htmlspecialchars($mid);
	$actie = htmlspecialchars($actie);
	$ip = htmlspecialchars($ip);
	$UA = htmlspecialchars($UA);
	$link = $link;
	mysql_query("INSERT INTO logs (mid, actie, ip, UA, extra, datum) VALUES ('".$mid."', '".$actie."', '".$ip."', '".$UA."', '".$link."', NOW())");  
}

function datumrewrite($dbdatum){
	$dbdatum = htmlspecialchars($dbdatum);
	$jaar = substr($dbdatum, 0, 4);
	$dag = substr($dbdatum, 8, 2);
	$maandnummer = substr($dbdatum, 5, 2);
	if($maandnummer == "01"){ echo $dag.' januari '.$jaar; }
	elseif($maandnummer == "02"){ echo $dag.' februari '.$jaar; }
	elseif($maandnummer == "03"){ echo $dag.' maart '.$jaar; }
	elseif($maandnummer == "04"){ echo $dag.' april '.$jaar; }
	elseif($maandnummer == "05"){ echo $dag.' mei '.$jaar; }
	elseif($maandnummer == "06"){ echo $dag.' juni '.$jaar; }
	elseif($maandnummer == "07"){ echo $dag.' juli '.$jaar; }
	elseif($maandnummer == "08"){ echo $dag.' augustus '.$jaar; }
	elseif($maandnummer == "09"){ echo $dag.' september '.$jaar; }
	elseif($maandnummer == "10"){ echo $dag.' oktober '.$jaar; }
	elseif($maandnummer == "11"){ echo $dag.' november '.$jaar; }
	elseif($maandnummer == "12"){ echo $dag.' december '.$jaar; }
}

function datumrrewrite($dbdatum){
	$dbdatum = htmlspecialchars($dbdatum);
	$jaar = substr($dbdatum, 0, 4);
	$dag = substr($dbdatum, 8, 2);
	$maandnummer = substr($dbdatum, 5, 2);
	if($maandnummer == "01"){ echo $dag.' januari '; }
	elseif($maandnummer == "02"){ echo $dag.' februari '; }
	elseif($maandnummer == "03"){ echo $dag.' maart '; }
	elseif($maandnummer == "04"){ echo $dag.' april '; }
	elseif($maandnummer == "05"){ echo $dag.' mei '; }
	elseif($maandnummer == "06"){ echo $dag.' juni '; }
	elseif($maandnummer == "07"){ echo $dag.' juli '; }
	elseif($maandnummer == "08"){ echo $dag.' augustus '; }
	elseif($maandnummer == "09"){ echo $dag.' september '; }
	elseif($maandnummer == "10"){ echo $dag.' oktober '; }
	elseif($maandnummer == "11"){ echo $dag.' november '; }
	elseif($maandnummer == "12"){ echo $dag.' december '; }
}
	
function timerewrite($dbtime){
	$dbtime = htmlspecialchars($dbtime);
	$uur = substr($dbtime, 11, 2);
	$minuut = substr($dbtime, 14, 2);
	{ echo ' '.$uur.':'.$minuut.' '; }
}

///////////////////////////////////////////////
?>