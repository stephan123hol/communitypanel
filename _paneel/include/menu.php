<?php
$sql_PaneelLevel = mysql_query("SELECT * FROM paneel_personeel WHERE habbonaam='".$_SESSION['habbonaam']."'");
$PaneelLevel = mysql_fetch_assoc($sql_PaneelLevel);
?>
<div class="menu-item extend-menu">
	<div class="content">
		<i class="fa fa-dashboard"></i>
	</div>
	
	<div class="menu-advanced">
		<div class="item"><a href="/">Dashboard</a></div>
		<div class="item"><a href="/conversaties">Mijn conversaties</a></div>
		<div class="item"><a href="/profiel/<?= $_SESSION["habbonaam"] ?>">Mijn profiel</a></div>
		<div class="item"><a href="/wachtwoord_veranderen">Verander wachtwoord</a></div>
		<div class="item"><a href="/uitloggen">Uitloggen</a></div>
	</div>
</div>

<div class="menu-item extend-menu">
	<div class="content link-button" data-link="/forum">
		<i class="fa fa-comment"></i>
	</div>
</div>

<div class="menu-item extend-menu">
	<div class="content">
		<i class="fa fa-group"></i>
	</div>
	
	<div class="menu-advanced">
		<div class="item"><a href="/medewerkers">Overzicht medewerkers</a></div>
		<div class="item"><a href="/notifyrechtenhouders">Roep rechtenhouders</a></div>
		<?php
		if ($PaneelLevel['toegang_promotie'] == 1)
		{
		?>
		<div class="item"><a href="/promoveren">Promotie geven</a></div>
		<?php
		}
		if ($PaneelLevel['toegang_warn'] == 1)
		{
		?>
		<div class="item"><a href="/waarschuwingen">Overzicht waarschuwingen</a></div>
		<div class="item"><a href="/waarschuwing_toevoegen">Waarschuwing geven</a></div>
		<?php
		}
		if ($PaneelLevel['toegang_degradatie'] == 1)
		{
		?>
		<div class="item"><a href="/degraderen">Degradatie geven</a></div>
		<?php
		}
		if ($PaneelLevel['toegang_ontslag'] == 1)
		{
		?>
		<div class="item"><a href="/ontslaan">Ontslaan</a></div>
		<?php
		}
		?>
	</div>
</div>

<div class="menu-item extend-menu">
	<div class="content">
		<i class="fa fa-bar-chart"></i>
	</div>
	
	<div class="menu-advanced">
		<div class="item"><a href="/stats/rangenoverzicht">Rangenoverzicht</a></div>
		<div class="item"><a href="/stats/promos">Maandelijks: Promoties</a></div>
		<div class="item"><a href="/stats/trainingen">Maandelijks: Trainingen</a></div>
		<div class="item"><a href="/stats/laatstepromoties">Laatste promoties</a></div>
		<div class="item"><a href="/stats/laatstetrainingen">Laatste trainingen</a></div>
		<div class="item"><a href="/stats/donateurs">Donateurs</a></div>
	</div>
</div>

<?php  
if ($PaneelLevel['toegang_trainingen'] == 1)
{
?>
	<div class="menu-item extend-menu">
		<div class="content">
			<i class="fa fa-puzzle-piece"></i>
		</div>
		
		<div class="menu-advanced">
			<div class="item"><a href="/training_toevoegen">Training noteren</a></div>
			<div class="item"><a href="/stats/laatstetrainingen">Laatste trainingen</a></div>
		</div>
	</div>
<?php
}
if (($PaneelLevel['toegang_beheer'] == 1) || ($PaneelLevel['toegang_rangniveau'] == 1))
{
?>   
	<div class="menu-item extend-menu">
		<div class="content">
			<i class="fa fa-gear"></i>
		</div>
		
		<div class="menu-advanced">
			<div class="item"><a href="/beheer/paneel_leden">Medewerkers</a></div>
			<div class="item"><a href="/beheer/paneel_lid_toevoegen">Medewerker toevoegen</a></div>
			<?php
			if ($PaneelLevel['toegang_promotag'] == 1)
			{
			?>
				<div class="item"><a href="/beheer/promotags">Overzicht promotags</a></div>
				<div class="item"><a href="/beheer/promotag_toevoegen">Promotag toevoegen</a></div>
			<?php
			}
			?>
			
		</div>
	</div>
<?php 
}
if ($user->getUserLevel($_SESSION["habbonaam"]) > 1)
{
	?>
	<div class="menu-item extend-menu">
		<div class="content">
			<i class="fa fa-lock"></i>
		</div>
		
		<div class="menu-advanced">
			<?php
			if ($user->getUserLevel($_SESSION["habbonaam"]) > 2)
			{
				?>
				<div class="item"><a href="/admin/logs">Systeem logs</a></div>
			<?php
			}
			?>
			<div class="item"><a href="/admin/bans">Paneel bans</a></div>
			<div class="item"><a href="/admin/hipchatnotificatie">HipChat Notificatie</a></div>
			<div class="item"><a href="/beheer/rangniveaus">Rangniveaus overzicht</a></div>
			<div class="item"><a href="/beheer/rangniveau_toevoegen">Rangniveau toevoegen</a></div>
		</div>
	</div>
	<?php
}
?>