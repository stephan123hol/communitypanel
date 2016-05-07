<?php
$promotions 	 = $profile->getPromotions($username);
$givenPromotions = $profile->getGivenPromotions($username);
$trainings 		 = $profile->getTrainings($username);
$givenTrainings  = $profile->getGivenTrainings($username);
$warnings  		 = $profile->getWarnings($username);
?>

<div class="panel w-20">
	<div class="panel-body t-a-center">
		<div class="profile-avatar">
			<img src="http://www.habbo.nl/habbo-imaging/avatarimage?user=<?= $username ?>&head_direction=3&direction=2&gesture=sml&img_format=gif" />
		</div>
		
		<div class="profile-name"><?= $username ?></div>
		<div class="profile-rank"><?= $user->getFunction($username) ?></div>
		
		<ul class="profile-details">
			<li title="Habbo missie"><?= $user->getDataFromHabboAPI($username, "motto") ?></li>
			<li title="HipChat Account"><?= $user->showHipChatTagname($username, true) ?></li>
			<?php
			if ($user->getUserVar($username, "donator") == 1)
			{
				?>
				<li title="Donateur">Deze gebruiker is een donateur</li>
				<?php
			}
			?>
		</ul>
		
		<hr class="panel-divider" />
		
		<div class="panel-actions">
			<?php
			$actions = $profile->generateActions($username, $_SESSION["habbonaam"]);

			foreach ($actions as $id => $name)
			{
				echo '<button class="btn btn-primary btn-cons btn-small panel-button" id="' . $id . '">' . $name . '</button>';
			}
			?>
		</div>
		
		<hr class="panel-divider" />
		
		<div class="panel-info">
			<strong>Promoties ontvangen/gegeven:</strong> <?= $profile->countPromotions($username) ?> / <?= $profile->countGivenPromotions($username) ?><br />
			<strong>Trainingen ontvangen/gegeven:</strong> <?= $profile->countTrainings($username) ?> / <?= $profile->countGivenTrainings($username) ?><br />
		</div>
	</div>
</div>

<div class="panel">
	<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/_paneel/modules/ads/profiel.html"; ?>
</div>

<div class="panel w-70">
	<div class="panel-heading">
		<h2>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#" data-target="promotions">Rangveranderingen</a></li>
				<li><a href="#" data-target="promotions-given">Rangveranderingen (Gegeven)</a></li>
				<li><a href="#" data-target="trainings">Trainingen</a></li>
				<li><a href="#" data-target="trainings-given">Trainingen (Gegeven)</a></li>
				<li><a href="#" data-target="warnings"><i class="fa fa-exclamation-triangle warnings-icon" title="Waarschuwingen"></i></a></li>
			</ul>
		</h2>
	</div>
	
	<div id="promotions" class="panel-body panel-target">
		<?php
		if ($promotions !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Nieuwe rang</th>
						<th>Oude rang</th>
						<th>Informatie</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($promotions as $key => $value)
				{
					?>
					<tr>
						<td width="23%" class="bold"><?= $value["rang_nieuw"] ?> [<?= $value["promotag"] ?>]</td>
						<td width="22%"><?= $value["rang_oud"] ?></td>
						<td width="50%">
							<?= $value["rang_op"] ?> - <strong>Door:</strong> <a href="/profiel/<?= $value["rang_door"] ?>" target="_blank"><?= $value["rang_door"] ?></a><br />
							<strong>Reden:</strong> <?= $value["reden"] ?>
						</td>
						<td width="5%"><i class="fa fa-<?= $value["icon"] ?>" title="<?= $value["rang_soort"] ?>"></i></td>
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
			echo "Deze gebruiker heeft nog geen promoties gekregen.";
		}
		?>
	</div>
	
	<div id="promotions-given" class="panel-body panel-target hidden">
		<?php
		if ($givenPromotions !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Nieuwe rang</th>
						<th>Oude rang</th>
						<th>Informatie</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($givenPromotions as $key => $value)
				{
					?>
					<tr>
						<td width="23%" class="bold"><?= $value["rang_nieuw"] ?> [<?= $value["promotag"] ?>]</td>
						<td width="22%"><?= $value["rang_oud"] ?></td>
						<td width="50%">
							<?= $value["rang_op"] ?> - <strong>Aan:</strong> <a href="/profiel/<?= $value["habbonaam"] ?>" target="_blank"><?= $value["habbonaam"] ?></a><br />
							<strong>Reden:</strong> <?= $value["reden"] ?>
						</td>
						<td width="5%"><i class="fa fa-<?= $value["icon"] ?>" title="<?= $value["rang_soort"] ?>"></i></td>
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
	
	<div id="trainings" class="panel-body panel-target hidden">
		<?php
		if ($trainings !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Training</th>
						<th>Door</th>
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
						<td width="59%"><?= $value["training"] ?></td>
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
			echo "Deze gebruiker heeft nog geen trainingen ontvangen.";
		}
		?>
	</div>
	
	<div id="trainings-given" class="panel-body panel-target hidden">
		<?php
		if ($givenTrainings !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Training</th>
						<th>Aan</th>
						<th>Op</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($givenTrainings as $key => $value)
				{
					?>
					<tr>
						<td width="59%"><?= $value["training"] ?></td>
						<td width="18%"><a href="/profiel/<?= $value["habbonaam"] ?>" target="_blank"><?= $value["habbonaam"] ?></a></td>
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
	
	<div id="warnings" class="panel-body panel-target hidden">
		<?php
		if ($warnings !== false)
		{
			?>
			<table class="w-full profile-table">
				<thead>
					<tr>
						<th>Waarschuwing</th>
						<th>Gegeven door</th>
						<th>Op</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($warnings as $key => $value)
				{
					?>
					<tr>
						<td width="60%"><?= $value["warn"] ?></td>
						<td width="21%"><a href="/profiel/<?= $value["warn_gever"] ?>" target="_blank"><?= $value["warn_gever"] ?></a></td>
						<td width="19%"><?= $value["warn_op"] ?></td>
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
			echo "Deze gebruiker heeft nog geen waarschuwingen ontvangen.";
		}
		?>
	</div>
</div>

<script type="text/javascript">
$("ul.nav-tabs a").click(function() {
	$("ul.nav-tabs li").removeClass("active");
	$(this).parent().addClass("active");
	
	var target = $(this).data("target");
	
	$(".panel-target:visible").fadeToggle("fast", function(){
	   $("#" + target).fadeToggle("fast");
	});
});

$("#promote").click(function() {
	location.href = "/promoveren/<?= $username ?>"
});

$("#demote").click(function() {
	location.href = "/degraderen/<?= $username ?>"
});
	
$("#create-hipchat-account").click(function() {
	$.post("/_paneel/modules/hipchat/createuser.php", {habbonaam: "<?= $username ?>"})
	.done(function(data) {
		alert(data);
		location.reload();
	});
});

$("#hipchat-change-pw").click(function() {
	$.post("/_paneel/modules/hipchat/changepw.php", {habbonaam: "<?= $username ?>"})
	.done(function(data) {
		alert("HipChat wachtwoord gereset.");
		location.reload();
	});
});

$("#allow-pw-change").click(function() {
	$.post("/_paneel/modules/profiel/grantpwchange.php", {habbonaam: "<?= $username ?>"})
	.done(function(data) {
		alert("Gebruiker kan nu zijn/haar wachtwoord resetten." + data);
		location.reload();
	});
});
</script>