<?php
$showAmount = 100;
$trainings  = $stats->getTrainings($showAmount);
?>

<div class="panel w-100">
	<div class="panel-heading">
		<h2>Laatste <?= $showAmount ?> Trainingen</h2>
	</div>
	<div class="panel-body panel-target">
		<?php
		if ($trainings !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Habbonaam</th>
						<th>Training</th>
						<th>Trainer</th>
						<th>Op</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($trainings as $key => $value)
				{
					?>
					<tr>
						<td width="18%"><a href="/profiel/<?= $value["habbonaam"] ?>" target="_blank"><?= $value["habbonaam"] ?></a></td>
						<td width="41%"><?= $value["training"] ?></td>
						<td width="18%"><a href="/profiel/<?= $value["door"] ?>" target="_blank"><?= $value["door"] ?></a></td>
						<td width="18%"><?= $value["datum"] ?></td>
						<td width="5%"><i class="fa fa-<?= $value["icon"] ?>"></i></td>
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
			echo "Deze gebruiker heeft nog geen trainingen gegeven.";
		}
		?>
	</div>
</div>