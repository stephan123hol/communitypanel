<script>
	$(document).ready(function() {
		$(".fa-info-circle").tooltipster({
			content: $(this).find(".tooltip").html(),
			contentAsHTML: true
		});
		
		$("#search-table").keyup(function() {
			var input = $(this).val();
			
			$(".conversations > tbody > tr").filter(function() {
				$(this).each(function() {
					var found = false;
					
					$(this).children(".table-title, table-starter").each(function() {
						if ($(this).html().match(input))
						{
							found = true;
						}
					});
					
					if (!found)
					{
						$(this).hide();
					}
					else
					{
						$(this).show();
					}
				});
			});
		});
		
		$(".table-link").click(function() {
			var conversationId = $(this).data("conversation-id");
			
			location.href = "conversaties/bekijk/" + conversationId;
		});
	});
</script>

<div class="page-title">
	Conversaties
</div>
<div class="conversations-search">
	<input type="text" id="search-table" placeholder="Zoeken..." />
</div>
<div class="clearfix">
<div class="panel-box">
	<div class="panel-box-body">
		<?php
		$data = $conversaties->listConversations($_SESSION['ID']);

		if ($data != false)
		{
			?>
			<table class="conversations">
				<thead>
					<tr>
						<th class="table-info">Info</th>
						<th>Starter</th>
						<th class="table-title">Titel</th>
						<th>&nbsp;</th>
						<th>Aangemaakt op</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($data as $key => $value)
					{
						$conversationTitle = $value['new_replies'] === true ? 'convo-title' : 'convo-title f-w-normal';
						$lastPost		   = $conversaties->getLastPost($value['id']);
						?>
						<tr class="table-link" data-conversation-id="<?= $value["id"] ?>">
							<td class="table-info">
								<i class="fa fa-info-circle">
									<div class="hidden tooltip">
										<strong>Deelnemers:</strong> <?= $value['participants'] ?><br />
										<strong>Aantal berichten:</strong> <?= $conversaties->countMessages($value['id']) ?><br />
										<strong>Laatste bericht door</strong> <?= $user->idToUsername($lastPost['from_user']) ?> <strong>op</strong> <?= date("d-m-Y", $lastPost['timestamp']) ?>
									</div>
								</i>
							</td>
							<td class="table-starter">
								<?= $value["starter"] ?>
							</td>
							<td class="table-title">
								<?= $value["title"] ?>
							</td>
							<td class="table-status">
								<?php
								if ($value['closed'] == 1)
								{
								?>
									<i class="fa fa-lock"></i>
								<?php
								}
								?>
							</td>
							<td class="table-date">
								<?= date("m-d-Y H:i", $value["created_on"]) ?>
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
			echo "Geen conversaties gevonden!";
		}
		?>
	</div>
</div>

<div class="ads-box-728">
	<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-6420019160139072" data-ad-slot="4165695831"></ins>
</div>