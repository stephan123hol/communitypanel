<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/classes/hipchat/rooms.hc.class.php";



$API = new HipChatAPIRooms();

$allRooms = $API->getAllRooms();

if (isset($_POST["submit"]))

{

	if (!empty($_POST["authkeycustom"]))

	{

		$API->setAuthKey($authKey);

	}

	

	if (isset($_POST["message"]) && !empty($_POST["message"]))

	{

		if (isset($_POST["rooms"]) && !empty($_POST["rooms"]))

		{

			$API->sendMessageToSpecificRooms($_POST["rooms"], $_POST["message"]);

			

			echo "<strong>Bericht succesvol verstuurd.</strong><br /><br />";

		}

		else

		{

			echo "<strong>Geen rooms geselecteerd.</strong><br /><br />";

		}

	}

	else

	{

		echo "<strong>Geen bericht ingevuld.</strong><br /><br />";

	}

}

?>

<h1>HipChat Notificatie</h1>

<hr />

Notificaties worden standaard verstuurd door -, indien je je eigen account hiervoor wil gebruiken, vul hieronder dan je eigen auth key in die je hebt gegenereerd op je HipChat account.<br />

<br />

<form method="post" action="">

	<input name="authkeycustom" class="message-title" placeholder="Eigen key? Vul hier in." /><br />

	<br />

	<select name="rooms[]" class="message-title" style="height: 180px;" multiple="multiple" size="20">

		<?php

			foreach ($allRooms as $id => $name)

			{

			?>

				<option value="<?= $id ?>"><?= $name ?></option>

			<?php

			}

		?>

	</select><br />

	<br />

	<textarea name="message" class="message-title" placeholder="Vul bericht in..." style="height: 120px;"></textarea><br />

	<input type="submit" name="submit" value="Verzend bericht naar rooms" class="btn btn-primary btn-cons flex-bg message-submit" />

</form>