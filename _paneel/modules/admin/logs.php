<?php

if ($user->getUserLevel($_SESSION["habbonaam"]) < 3)

{

	header("Location: /");

	exit;

}



$data = $user->getLogs();

$data = false;

?>

<h1>Paneel logs</h1>

<hr />

<div class="f-right m-r-20">

	<!--<button type="submit" class="btn btn-primary btn-cons flex-bg link-button" data-link="http://<?= $_SERVER['HTTP_HOST'] . "/conversaties/nieuw/" ?>">Nieuwe conversatie</button>-->

</div>

<p class="m-t-none width-p-75">Hieronder vind je de laatste 200 log entry's uit de database.</p>



<?php

if ($data != false)

{

	?>

	<table class="table-default">

		<thead class="table-head-default">

			<tr>

				<th>Habbonaam</th>

				<th>Actie</th>

				<th>Datum</th>

				<th>IP</th>

			</tr>

		</thead>

		<tbody>

		<?php

		foreach ($data as $key => $value)

		{

			?>

			<tr>

				<td><?php echo $value["habbonaam"]; ?></td>

				<td><?php echo $value["actie"]; ?></td>

				<td><?php echo $value["datum"]; ?></td>

				<td><?php echo $value["ip"]; ?></td>

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

	echo "Geen logs gevonden!";

}

?>