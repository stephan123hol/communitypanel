<?php
$data = $conversaties->getConversationData($_GET['convo_id']);
$berichten = $conversaties->getConversation($_GET['convo_id']);
$deelnemers = $conversaties->getParticipants($_GET['convo_id']);

$conversaties->updateLastViewed($_GET['convo_id'], $_SESSION['ID']);

$status = $data['closed'] == 1 ? "Gesloten" : "Open";

if (isset($_POST['message']))
{
	$answer = $conversaties->addMessage($_SESSION['ID'], $_GET['convo_id'], $_POST['message']);
	if ($answer === true)
	{
		die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
	}
	else
	{
		die("Een error is opgetreden bij het uitvoeren van deze actie. Neem contact op met het beheer.");
	}
}

if (isset($_POST['user']))
{
	$conversaties->addUserToConvo($_SESSION['ID'], $_POST['user'], $_GET['convo_id']);
	die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
}

if (isset($_POST['user_delete']))
{
	$conversaties->deleteUserFromConvo($_SESSION['ID'], $_POST['user_delete'], $_GET['convo_id']);
	die(header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
}

if (isset($_POST['leave_conversation']))
{
	$conversaties->leaveConversation($_SESSION['ID'], $_POST['leave_conversation'], $_GET['convo_id']);
	die(header("Location: http://" . $_SERVER['HTTP_HOST'] . "/conversaties"));
}
?>
<div class="conversation-container">
	<h1 class="p-l-none"><?= $data['title'] ?></h1>
	<?php
	foreach ($berichten as $value)
	{
	?>
	<div class="message-container">
		<div class="message-left">
			<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $user->idToUsername($value['from_user']) ?>&head_direction=3&direction=2&gesture=sml&img_format=gif" />
			<p class="m-t-none"><a href="/profiel/<?= $user->idToUsername($value['from_user']) ?>" class="convo-title"><?= $user->idToUsername($value['from_user']) ?></a><br />
			<span class="message-rank"><?= $user->getFunction($user->idToUsername($value['from_user']), $tag) ?></span></p>
		</div>
		<div class="message-right">
			<?= $value['message'] ?>
			<div class="message-lower-info">
				Op <?= date("d-m-Y", $value['timestamp']) ?> om <?= date("H:i", $value['timestamp']) ?>
			</div>
		</div>
	</div>
	<?php
	}
	
	include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/conversatie.html";
	
	if ($data['closed'] == 0 || $user->isAdmin($_SESSION["habbonaam"]) === true)
	{
	?>
	<div class="message-form">
		<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
			<textarea name="message" class="message-textarea" id="message" placeholder="Vul hier je bericht in..."></textarea>
			<div class="t-a-right">
				<button type="submit" class="btn btn-primary btn-cons flex-bg message-submit">Reactie toevoegen</button>
			</div>
		</form>
	</div>
	<?php
	}
	else
	{
		?>
		<span class="message-closed">(Deze conversatie is gesloten. Hierop reageren is niet mogelijk.)</span>
		<?php
	}
	?>
</div>

<div class="message-infobox">
	<h1 class="p-l-none">Deelnemers</h1>
	<p class="m-t-none">
		<?php
		foreach ($deelnemers as $deelnemer)
		{
			?>
			- <?= $user->idToUsername($deelnemer['user_id']) ?><br />
			<?php
		}
		?>
	</p>

	<h1 class="p-l-none">Conversatie gegevens</h1>
	<p class="m-t-none">
		<b>Gemaakt door:</b> <?= $user->idToUsername($data['starter']) ?><br />
		<b>Aangemaakt op:</b> Op <?= date("d-m-Y", $data['created_on']) ?> om <?= date("H:i", $data['created_on']) ?><br />
		<b>Status:</b> <?= $status ?>
		
		<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
			<input type="hidden" name="leave_conversation" id="leave_conversation" value="<?= $_SESSION['ID'] ?>" />
			<button type="submit" class="btn btn-primary btn-cons flex-bg message-submit">Verlaat gesprek</button>
		</form>
		Let op: als je een gesprek verlaat, kan je deze niet meer zien totdat de starter je weer toevoegd.
	</p>
	
	<?php
	if ($_SESSION['ID'] == $data['starter'] || in_array(strtolower($_SESSION['habbonaam']), $bedrijf_beheerder))
	{
	?>
		<h1 class="p-l-none">Gebruiker toevoegen</h1>
		<p class="m-t-none">
			<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
				<input type="text" class="message-title full-width" name="user" id="user" placeholder="Habbonaam van gebruiker" />
				<div class="t-a-right">
					<button type="submit" class="btn btn-primary btn-cons flex-bg message-submit">Toevoegen</button>
				</div>
			</form>
		</p>
		
		<h1 class="p-l-none">Gebruiker verwijderen</h1>
		<p class="m-t-none">
			Je kan op deze manier niet jezelf verwijderen.
			<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
				<input type="text" class="message-title full-width" name="user_delete" id="user_delete" placeholder="Habbonaam van gebruiker (exact hetzelfde)" />
				<div class="t-a-right">
					<button type="submit" class="btn btn-primary btn-cons flex-bg message-submit">Verwijderen</button>
				</div>
			</form>
		</p>
	<?php
	}
	?>
</div>
<div class="clearfix"></div>