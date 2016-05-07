<div class="panel w-20">
	<div class="panel-heading">
		<h2>Informatie</h2>
	</div>
	<div class="panel-body">
		Het automatische rangenoverzicht laat alle gebruikers zien onder hun huidige rank. Dit is op basis van leden die zijn toegevoegd als personeel door een Raadslid in het systeem.
	</div>
</div>

<?php
$data = $stats->listLatestRanks();

foreach ($data as $rankName => $rankData)
{
	?>
	<div class="panel w-20">
		<div class="panel-heading">
			<h2><?= $rankName ?></h2>
		</div>
		<div class="panel-body">
			<?php
			foreach ($rankData["leden"] as $username)
			{
				echo $username . "<br />";
			}
			?>
		</div>
	</div>
	<?php
}
?>