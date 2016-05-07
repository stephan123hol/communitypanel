<?php
$showAmount = 100;
$promotions = $stats->getPromotions($showAmount);
?>
<div style="position: absolute; top: 600px; right: 20px;"><img src="/_paneel/assets/afbeelding/pasen/paasei2.png" /></div>
<div class="panel w-100">
	<div class="panel-heading">
		<h2>Laatste <?= $showAmount ?> Promoties</h2>
	</div>
	<div class="panel-body panel-target">
		<?php
		if ($promotions !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Habbonaam</th>
						<th>Nieuwe rang</th>
						<th>Oude rang</th>
						<th>Promogever</th>
						<th>Rang op</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($promotions as $key => $value)
				{
					?>
					<tr>
						<td><a href="/profiel/<?= $value["habbonaam"] ?>" target="_blank"><?= $value["habbonaam"] ?></a></td>
						<td><?= $value["rang_nieuw"] ?> [<?= $value["promotag"] ?>]</td>
						<td><?= $value["rang_oud"] ?></td>
						<td><a href="/profiel/<?= $value["rang_door"] ?>" target="_blank"><?= $value["rang_door"] ?></a></td>
						<td><?= $value["rang_op"] ?></td>
						<td><i class="fa fa-<?= $value["icon"] ?>" title="<?= $value["rang_soort"] ?>"></i></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
		else
		{
			echo "Deze gebruiker heeft nog geen promoties gegeven.";
		}
		?>
	</div>
</div>