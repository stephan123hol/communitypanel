<?php
if (isset($_POST['title']))
{
	$answer = $conversaties->newConversation($_SESSION['ID'], $_POST['title'], $_POST['participants'], $_POST['message'], $_POST['close_on_create']);
	
	if ($answer === true)
	{
		die(header("Location: http://" . $_SERVER['HTTP_HOST'] . "/conversaties/"));
	}
}
?>
<div class="conversation-container">
	<h1 class="p-l-none">Nieuwe conversatie</h1>
	
	<form action="<?= $_SERVER["REQUEST_URI"] ?>" method="post">
		<input type="text" class="message-title" name="title" id="title" placeholder="Titel van de conversatie..." />
		<input type="text" class="message-title" name="participants" id="participants" placeholder="Leden van conversatie (gescheiden met een plus: user1+user2+user3)" />
		<textarea name="message" class="message-textarea" id="message" placeholder="Vul hier je bericht in..."></textarea><br />
		<input type="checkbox" value="1" name="close_on_create" id="close_on_create"> Sluit conversatie na het openen
		<div class="f-right">
			<button type="submit" class="btn btn-primary btn-cons flex-bg message-submit">Conversatie starten</button>
		</div>
	</form>
	<?php
	if ($answer !== true)
	{
		echo $answer;
	}
	?>
</div>