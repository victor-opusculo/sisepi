<?php if($eventObj !== null) { ?>

<script>
	function presenceListButton_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events2", "viewpresencelist", null, "eventDateId={eventdateid}"); ?>';
		var eventDateId = this.getAttribute("data-eventDateId");
		window.location.href = url.replace("{eventdateid}", String(eventDateId));
	}
		
	function btnShowPresenceApps_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events2", "viewpresencesapp", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", String(eventId));
	}

	function btnShowCertificates_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events3", "viewcertificates", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", String(eventId));
	}
	
	window.onload = function()
	{
		var butts = document.querySelectorAll("table button.btnViewPresenceList");
		for (let butt of butts)
			butt.onclick = presenceListButton_onClick;
		
		document.getElementById("btnShowPresenceApps").onclick = btnShowPresenceApps_onClick;
		document.getElementById("btnShowCertificates").onclick = btnShowCertificates_onClick;
	};
</script>

<?php $tabsComp->render();
$tabsComp->beginTabsFrame();
$tabsComp->beginTab("Principal", true); ?>
<div class="viewDataFrame">
	<label>ID: </label><?php echo $eventObj->id; ?> <br/>
	<label>Nome: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo $eventObj->typeName; ?> <br/>
	<label>Modalidade: </label><?php echo hsc(Data\getEventMode($eventObj->locTypes)); ?><br/>
	<label>Responsável: </label><?php echo hsc($eventObj->responsibleForTheEvent); ?> <br/>
	<?php $customInfos = json_decode($eventObj->customInfosJson); 
	if (isset($customInfos) && count($customInfos) > 0): ?>
		<?php foreach ($customInfos as $ci): ?> 
			<label><?php echo $ci->label; ?>: </label><?php echo $ci->value; ?><br/>
		<?php endforeach; ?>
	<?php endif; ?>
	<label>Mais informações: </label><div><?php echo nl2br(hsc($eventObj->moreInfos)); ?></div> <br/>
	<label>Datas: </label>
	<table>
		<thead>
			<tr>
				<th>Dia</th>
				<th>Horário</th>
				<th>Nome/Conteúdo</th>
				<th>Docente</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($eventObj->dates as $d)
			{
				$formatedDate = date_format(date_create($d->date), "d/m/Y"); ?>
				<tr class="expandableTableRow" tabindex="0">
					<td class="shrinkCell"><?php echo $formatedDate; ?></td><td class="centControl"><?php echo $d->beginTime . " - " . $d->endTime; ?></td><td><?php echo hsc($d->name); ?></td><td><?php echo hsc($d->professorName); ?></td><td class="shrinkCell"><button class="btnViewPresenceList" data-eventDateId="<?php echo $d->id; ?>" <?php echo ((!$d->presenceListNeeded) ? 'disabled="disabled"' : ''); ?>>Lista de presença</button></td>
					<td class="shrinkCell">
						<span class="dropdownMenuButtonArea">
							<button type="button" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/menu.svg"); ?>" width="24" height="24" title="Opções" alt="Opções"/></button>
							<ul class="dropdownMenu">
								<?php if (!empty($d->checklistId)): ?>
								<li>
									<a href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklists", "view", $d->checklistId); ?>">Ver/preencher checklist</a>
								</li>
								<?php endif; ?>
							</ul>
						</span>
					</td>
				</tr>
				<tr class="tableRowExpandInfosPanel" tabindex="1">
					<td colspan="6">
						<div>
						<?php
							$localInfos = json_decode($d->locationInfosJson);
							$url = $localInfos->url ?? '';
							$moreInfos = $localInfos->infos ?? '';
							$location = array_filter($eventLocations, fn($dr) => (string)$dr['id'] === (string)$d->locationId);
						?>
						<label>Local: </label><?php echo !empty($d->locationId) ? array_pop($location)['name'] : 'Indefinido'; ?>
						<br/>
						<?php if (!empty($url)): ?>
							<label>Link: </label><a href="<?php echo $url; ?>"><?php echo truncateText($url, 30); ?></a>
							<br/>
						<?php endif; ?>
						<?php if (!empty($moreInfos)): ?>
							<label>Informações: </label><?php echo $moreInfos; ?>
						<?php endif; ?>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<br/>
	<label>Lista de inscrição: </label><?php echo ($eventObj->subscriptionListNeeded) ? "Habilitada" : "Desabilitada"; ?> 
	<?php if ($eventObj->subscriptionListNeeded) { ?>
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events2", "viewsubscriptionlist", null, [ 'eventId' => $eventObj->id ]); ?>">Ver lista</a>
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events3", "subscribe", null, [ 'eventId' => $eventObj->id ]); ?>">Criar inscrição</a>
		<label>Data de encerramento: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
	<label>Permitir inscrições tardias: </label><?php echo ($eventObj->allowLateSubscriptions ? "Sim" : "Não"); ?>
	<?php } ?>	<br/>
	<br/>
	
	<label>Geração automática de certificados: </label><?php echo ($eventObj->certificateText !== null) ? "Habilitada" : "Desabilitada"; ?><br/>
	
	<br/>
	<label>Anexos: </label>
	<div>
		<ul>
		<?php 
		$attachsPath = URL\URLGenerator::generateFileURL("uploads/events/" . $eventObj->id . "/");
		foreach ($eventObj->attachments as $a)
		{ 
			echo '<li><a href="' . $attachsPath . $a->fileName . '">' . $a->fileName . "</a></li>";
		}
		?>
		</ul>
	</div>
	
	<div class="centControl">
		<button id="btnShowPresenceApps" data-eventId="<?php echo $eventObj->id; ?>">Apontamento de presença</button>
		<button id="btnShowCertificates" data-eventId="<?php echo $eventObj->id; ?>" <?php echo ($eventObj->certificateText !== null) ? "" : 'disabled="disabled"' ?>>Certificados emitidos</button>
	</div>
</div>
<?php $tabsComp->endToBeginTab("Plano de trabalho"); ?>
	<?php $workplanPage->render(); ?>
<?php $tabsComp->endToBeginTab("Checklist"); ?>
	<?php $checklistPage->render(); ?>
<?php $tabsComp->endTab();
$tabsComp->endTabsFrame();
?>

<div class="editDeleteButtonsFrame">
	<ul>
		<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("events", "edit", $eventObj->id); ?>">Editar</a></li>
		<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events", "delete", $eventObj->id); ?>">Excluir</a></li>
	</ul>
</div>

<?php } else { ?>
<p>Registro não localizado.</p>
<?php } ?>