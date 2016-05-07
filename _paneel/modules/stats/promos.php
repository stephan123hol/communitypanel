<?php
$topAmount 	    = 10;
$selectedDate   = date('Y-m');
$selectedMonth	= $stats->getMonth(date("n"));
$data	 	    = $stats->getTopPromos($topAmount, $selectedDate);
?>

<div class="panel w-100">
	<div class="panel-heading">
		<h2>Top <?= $topAmount ?> Promoties (<?= ucfirst($selectedMonth) ?>)</h2>
	</div>
	<div class="panel-body">
		<?php
		if ($data != false)
		{
			?>
			<table class="table-default">
				<thead class="table-head-default">
					<tr>
						<th>Positie</th>
						<th>Habbonaam</th>
						<th>Hoeveelheid</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($data as $key => $value)
				{
					$position = ($key + 1);
					?>
					<tr>
						<td><?= $position ?></td>
						<td><?= $value["by"] ?></td>
						<td><?= $value["amount"] ?></td>
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
			echo "<br />Geen top gevonden!";
		}
		?>
	</div>
</div>