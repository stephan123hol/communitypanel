<?php

$data = $stats->getDonators();

?>

<div class="panel w-100">

	<div class="panel-heading">

		<h2>Donateurs</h2>

	</div>

	<div class="panel-body">

		<p style="margin-top: -5px;">Indien je je gedoneerde bedrag niet in deze lijst wilt weergeven, of alleen de hoeveelheid verborgen wilt hebben, neem contact op met -.</p>

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

						<td><?= $value["username"] ?></td>

						<td>&euro; <?= $value["amount"] ?></td>

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



<div class="panel w-100">

	<div class="panel-heading">

		<h2>Actieve banners</h2>

	</div>

	<div class="panel-body">

		<?php

		$filesBanners = glob($_SERVER["DOCUMENT_ROOT"] . '/_paneel/assets/afbeelding/donatie/banners/active/*.*');

		

		if (!empty($filesBanners))

		{

			foreach ($filesBanners as $key => $value)

			{

				preg_match("/[^\/]+$/", $value, $matches);

				$banner = $matches[0];

				

				echo '<img src="/_paneel/assets/afbeelding/donatie/banners/active/' . $banner . '" /> &nbsp; &nbsp;';

			}

		}

		else

		{

			echo "Geen actieve banners!";

		}

		?>

	</div>

</div>



<div class="panel w-100">

	<div class="panel-heading">

		<h2>Verlopen banners</h2>

	</div>

	<div class="panel-body">

		<?php

		$filesBanners = glob($_SERVER["DOCUMENT_ROOT"] . '/_paneel/assets/afbeelding/donatie/banners/inactive/*.*');

		

		if (!empty($filesBanners))

		{

			foreach ($filesBanners as $key => $value)

			{

				preg_match("/[^\/]+$/", $value, $matches);

				$banner = $matches[0];

				

				echo '<img src="/_paneel/assets/afbeelding/donatie/banners/inactive/' . $banner . '" /> &nbsp; &nbsp;';

			}

		}

		else

		{

			echo "Er zijn nog geen verlopen banners!";

		}

		?>

	</div>

</div>