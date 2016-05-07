<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/rooms.hc.class.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hqrequest.class.php";

$HQ = new HqRequest();

$HQALastRequest = $HQ->getLastRequest(1);
$HQBLastRequest = $HQ->getLastRequest(2);

if (isset($_POST["submit"]))
{
	$errors = array();
	
	$API = new HipChatAPIRooms();
	
	if ($HQ->checkTimeout($_POST["hq"]))
	{
		$API->notifyRechtenhouders($_POST["hq"], $_SESSION["habbonaam"], $_POST["reason"]);
		$HQ->setLastRequest($_POST["hq"]);
		
		$response = "Verzoek is succesvol verstuurd!";
	}
	else
	{
		$response = "Er is minder dan 5 minuten geleden nog een verzoek gedaan voor dit HQ! Je kan de tool nog niet gebruiken.";
	}
}
?>
<div class="panel w-100">
	<div class="panel-heading">
		<h2>Roep Rechtenhouders!</h2>
	</div>
	<div class="panel-body">
		<div style="position: absolute; bottom: 10px; right: 12px;"><img width="7px" src="/_paneel/assets/afbeelding/pasen/paasei2.png" /></div>
		<?php
		if (isset($response))
		{
			echo '<strong>' . $response . '</strong><br /><br />';
		}
		?>
		Is er in een HQ een rechtenhouder nodig? Gebruik dan het formulier op deze pagina om de mensen met rechten in het aangegeven HQ te roepen. Deze krijgen dan automatisch een melding op HipChat dat er een rechtenhouder naar het aangegeven HQ moet komen! <strong>Misbruik van deze tool word bestraft! Gebruik deze tool dus niet als je alleen de staff lounge in moet, en kijk eerst of er rechtenhouders aanwezig zijn!</strong><br />
		<br />
		Voordat je een verzoek verstuurt, kijk eerst even wanneer het laatste verzoek voor rechten is verstuurd! Zo voorkomen we spam.<br />
		<strong>Laatst verzonden verzoek HQ A:</strong> <?= $HQ->getLastRequest(1); ?><br />
		<strong>Laatst verzonden verzoek HQ B:</strong> <?= $HQ->getLastRequest(2); ?><br />
		<br />
		<strong>Selecteer HQ:</strong><br />
		<form method="post" action="">
			<select name="hq" class="message-title" style="width: auto !important; height: 30px;">
				<option value="1">HQ A</option>
				<option value="2">HQ B</option>
			</select><br />
			<strong>Welke reden:</strong><br />
			<select name="reason" class="message-title" style="width: auto !important; height: 30px;">
				<option>Geen rechtenhouder aanwezig</option>
				<option>Ik ben een rechtenhouder, en heb een vervanger nodig</option>
				<option>Verzoek openen HQ (nieuwe rechtenhouder nodig)</option>
				<option>Overig</option>
			</select><br />
			<input type="submit" name="submit" value="Stuur verzoek!" class="btn btn-primary btn-cons flex-bg message-submit" />
		</form>
	</div>
</div>