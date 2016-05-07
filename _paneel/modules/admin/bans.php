<?php
if (isset($_POST["submit"]))
{
	$bans->addBan($_POST["type"], $_POST["value"], $_POST["reason"], $_SESSION["habbonaam"]);
}

$data = $bans->getBans();
?>
<h1>Ban toevoegen</h1>
<hr />
<form method="post" action="">
	<select name="type" class="message-title" style="width: auto !important; height: 30px;">
		<option value="username">Gebruikersnaam</option>
		<option value="ip">IP</option>
	</select><br />
	<br />
	<input name="value" class="message-title" placeholder="Habbonaam / IP" style="width: 75% !important;" /><br />
	<br />
	<textarea name="reason" class="message-title" placeholder="Vul hier de reden voor de ban in (max 400 karakters)" style="height: 120px; width: 75% !important;"></textarea>
	<br />
	<input type="submit" name="submit" value="Ban toevoegen" class="btn btn-primary btn-cons flex-bg message-submit" />
</form>

<h1>Huidige bans</h1>
<hr />
<?php
if ($data != false)
{
	?>
	<table class="table-default">
		<thead class="table-head-default">
			<tr>
				<th>ID</th>
				<th>Type</th>
				<th>Waarde</th>
				<th>Door</th>
				<th>Datum</th>
				<th>Reden</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($data as $key => $value)
		{
			?>
			<tr>
				<td><?php echo $value["id"]; ?></td>
				<td><?php echo $value["type"]; ?></td>
				<td><?php echo $value["value"]; ?></td>
				<td><?php echo $value["by"]; ?></td>
				<td><?php echo $value["added_on"]; ?></td>
				<td class="ban-reason">&nbsp;<i class="fa fa-info-circle" title="<?php echo $value["reason"]; ?>"></i></td>
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
	echo "<br />Geen bans gevonden!";
}
?>