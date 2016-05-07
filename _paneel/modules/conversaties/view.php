<?php
$data = $conversaties->listConversations($_SESSION['ID']);
?>
<div class="panel w-20">
	<div class="panel-heading">
		<h2>Jouw conversaties</h2>
	</div>
	
	<div class="panel-body">
		Een conversatie is een privé bericht naar één of meerdere personen. In elke conversatie vind je alle berichten die daar in worden gezet. Conversaties kunnen worden gesloten, en je kan ze ook verlaten (verwijderen).
		
		<button type="submit" class="btn btn-primary btn-cons link-button" data-link="http://<?= $_SERVER['HTTP_HOST'] . "/conversaties/nieuw/" ?>">Nieuwe conversatie</button>
	</div>
</div>

<div class="panel w-70">
	<?php
	if ($data !== false)
	{
		?>
			<table class="profile-table">
				<thead>
					<tr>
						<th>Conversatie</th>
						<th>Informatie</th>
						<th>Laatste post</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($data as $key => $value)
				{
					$class 	  = $value['new_replies'] === true ? "bold" : "";
					$lastPost = $conversaties->getLastPost($value['id']);
					?>
					<tr>
						<td width="60%">
							<a href="/conversaties/bekijk/<?= $value['id'] ?>" class="<?= $class ?>"><?= $value['title'] ?></a><br />
							<span class="convo-subtitle"><?= date("m-d-Y", $value['created_on']) ?> - <?= $value['participants'] ?></span>
						</td>
						<td width="15%">
								Berichten: <?= $conversaties->countMessages($value['id']) ?><br />
								Deelnemers: <?= $conversaties->countParticipants($value['id']) ?>
						</td>
						<td width="25%">
							Geplaatst door <a href="/profiel/<?= $user->idToUsername($lastPost['from_user']) ?>"><?= $user->idToUsername($lastPost['from_user']) ?></a><br />
							<a href="/conversaties/bekijk/<?= $value['id'] ?>#<?= $lastPost['id'] ?>" class="convo_link">Op <?= date("d-m-Y", $lastPost['timestamp']) ?> om <?= date("H:i", $lastPost['timestamp']) ?></a>
						</td>
						<td width="5%">
							<?php
							if (isset($value["icon"]))
							{
								?>
								<i class="fa fa-<?= $value["icon"] ?>"></i>
								<?php
							}
							?>
						</td>
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
		echo "Je hebt nog geen conversaties!";
	}
	?>
</div>

<div class="panel hidden">
	<div class="panel-body panel-target">
		<div class="convo-container convo-heading flex-bg">
			<div class="convo-left"></div>
			<div class="convo-middle-left">
				Conversatie
			</div>
			<div class="convo-middle-right">
				Informatie
			</div>
			<div class="convo-right">
				Laatste post
			</div>
		</div>
		<?php
		if ($data != false)
		{
		?>
			<?php
			foreach ($data as $key => $value)
			{
				$convo_title = $value['new_replies'] === true ? 'convo-title' : 'convo-title f-w-normal';
				
				$last_post = $conversaties->getLastPost($value['id']);
				?>
				<div class="convo-container">
					<div class="convo-left">
						<?php
						if ($value['closed'] == 1)
						{
						?>
							<img src="/_paneel/assets/afbeelding/forum_icon_3.gif" alt="Gesloten" title="Gesloten conversatie" />
						<?php
						}
						?>
					</div>
					<div class="convo-middle-left">
						<a href="/conversaties/bekijk/<?= $value['id'] ?>" class="<?= $convo_title ?>"><?= $value['title'] ?></a><br />
						<span class="convo-subtitle"><?= date("m-d-Y", $value['created_on']) ?> - <?= $value['participants'] ?></span>
					</div>
					<div class="convo-middle-right">
						<div class="f-left">
							Berichten:<br />
							Deelnemers:
						</div>
						<div class="f-right">
							<?= $conversaties->countMessages($value['id']) ?><br />
							<?= $conversaties->countParticipants($value['id']) ?>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="convo-right">
						Geplaatst door <a href="/profiel/<?= $user->idToUsername($last_post['from_user']) ?>"><?= $user->idToUsername($last_post['from_user']) ?></a><br />
						<a href="/conversaties/bekijk/<?= $value['id'] ?>#<?= $last_post['id'] ?>" class="convo_link">Op <?= date("d-m-Y", $last_post['timestamp']) ?> om <?= date("H:i", $last_post['timestamp']) ?></a>
					</div>
				</div>
				<?php
			}
			?>
		<?php
		}
		else
		{
			echo "Geen conversaties gevonden!";
		}
		?>
	</div>
</div>