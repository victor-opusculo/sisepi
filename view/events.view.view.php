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
	<label>Tipo: </label><?php echo $eventObj->getOtherProperties()->typeName; ?> <br/>
	<label>Modalidade: </label><?php echo hsc(Data\getEventMode($eventObj->getOtherProperties()->locTypes)); ?><br/>
	<label>Carga horária: </label><?php echo hsc(round(timeStampToHours($eventObj->getOtherProperties()->hours), 1) . 'h' . 
	(isset($eventObj->getOtherProperties()->testHours) ? '+' . $eventObj->getOtherProperties()->testHours . 'h' : '')) ?><br/>
	<label>Responsável: </label><?php echo hsc($eventObj->responsibleForTheEvent); ?> <br/>
	<?php $customInfos = json_decode($eventObj->customInfosJson); 
	if (isset($customInfos) && count($customInfos) > 0): ?>
		<?php foreach ($customInfos as $ci): ?> 
			<label><?php echo hsc($ci->label); ?>: </label><?php echo hsc($ci->value); ?><br/>
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
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($eventObj->eventDates as $d)
			{
				$formatedDate = date_format(date_create($d->date), "d/m/Y"); ?>
				<tr class="expandableTableRow" tabindex="0">
					<td class="shrinkCell"><?php echo $formatedDate; ?></td><td class="centControl"><?php echo $d->beginTime . " - " . $d->endTime; ?></td><td><?php echo hsc($d->name); ?></td><td class="shrinkCell"><button class="btnViewPresenceList" data-eventDateId="<?php echo $d->id; ?>" <?php echo ((!$d->presenceListNeeded) ? 'disabled="disabled"' : ''); ?>>Lista de presença</button></td>
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
					<td colspan="5">
						<div>
						<?php
							$localInfos = json_decode($d->locationInfosJson);
							$url = $localInfos->url ?? '';
							$moreInfos = $localInfos->infos ?? '';
							$location = array_filter($eventLocations, fn($dr) => (string)$dr['id'] === (string)$d->locationId);
						?>
						<label>Docentes: </label><?php echo hsc(implode(', ', array_map( fn($profObj) => $profObj->name, $d->professors))); ?> 
						<br/>
						<label>Local: </label><?php echo hsc(!empty($d->locationId) ? array_pop($location)['name'] : 'Indefinido'); ?>
						<br/>
						<?php if (!empty($url)): ?>
							<label>Link: </label><a href="<?php echo hscq($url); ?>"><?php echo hsc(truncateText($url, 30)); ?></a>
							<br/>
						<?php endif; ?>
						<?php if (!empty($moreInfos)): ?>
							<label>Informações: </label><?php echo hsc($moreInfos); ?>
							<br/>
						<?php endif; ?>
						<?php if (!empty($d->traits)): ?>
							<label>Traços: </label><br/>
							<?php foreach ($d->traits as $trait): ?>
								<img src="<?= URL\URLGenerator::generateFileURL("uploads/traits/{$trait->id}.{$trait->fileExtension}") ?>" height="32" alt="<?= $trait->name ?>" title="<?= $trait->name . ': ' . $trait->description ?>" />
							<?php endforeach; ?>
							<br/>
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
		<br/>
		<label>Modelo de lista de inscrição: </label><?= hsc($eventObj->getOtherProperties()->subscriptionTemplateName) ?>
		<br/>
		<label>Abertura da lista: </label><?php echo !empty($eventObj->subscriptionListOpeningDate) ? date_format(date_create($eventObj->subscriptionListOpeningDate),"d/m/Y") : "Aberta desde a criação. ";?>
		<label>Encerramento da lista: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
	<label>Permitir inscrições tardias: </label><?php echo ($eventObj->allowLateSubscriptions ? "Sim" : "Não"); ?>
	<?php } ?>	<br/>
	<br/>
	
	<label>Geração automática de certificados: </label><?php echo ($eventObj->certificateText !== null) ? "Habilitada" : "Desabilitada"; ?><br/>
	<br/>

	<label>Pesquisa de satisfação: </label><?php echo !empty($eventObj->surveyTemplateId) ? "Habilitada" . hsc(" ({$eventObj->getOtherProperties()->surveyTemplateName})") : "Desabilitada"; ?> <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('events3', 'viewsurveylist', null, [ 'eventId' => $eventObj->id ] ); ?>">Ver pesquisas respondidas</a> <br/>
	<br/>

	<label>Avaliação: </label><?php echo !empty($eventObj->testTemplateId) ? "Habilitada" . hsc(" ({$eventObj->getOtherProperties()->testTemplateName})") : "Desabilitada"; ?>
	<br/>
	<br/>
	<label>Anexos: </label>
	<div>
		<ul>
		<?php 
		$attachsPath = URL\URLGenerator::generateFileURL("uploads/events/" . $eventObj->id . "/");
		foreach ($eventObj->eventAttachments as $a)
		{ 
			echo '<li><a href="' . $attachsPath . $a->fileName . '">' . $a->fileName . "</a></li>";
		}
		?>
		</ul>
	</div>
	
	<div class="centControl">
		<button id="btnShowPresenceApps" data-eventId="<?php echo $eventObj->id; ?>">Desempenhos</button>
		<button id="btnShowCertificates" data-eventId="<?php echo $eventObj->id; ?>" <?php echo ($eventObj->certificateText !== null) ? "" : 'disabled="disabled"' ?>>Certificados emitidos</button>
	</div>
</div>
<?php $tabsComp->endToBeginTab("Plano de trabalho"); ?>
	<?php $workplanPage->render(); ?>
<?php $tabsComp->endToBeginTab("Checklist"); ?>
	<?php $checklistPage->render(); ?>
<?php $tabsComp->endToBeginTab("Orçamento"); ?>
	<?php include "view/fragment/events.budgetentries.view.php"; ?>
<?php $tabsComp->endToBeginTab("ODS"); ?>
	<?php include "view/fragment/events.odsrelation.view.php"; ?>
<?php $tabsComp->endTab();
$tabsComp->endTabsFrame();
?>

<div class="editDeleteButtonsFrame">
	<ul>
		<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("events", "edit", $eventObj->id); ?>">Editar evento</a></li>
		<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events", "delete", $eventObj->id); ?>">Excluir evento</a></li>
	</ul>
</div>

<?php } else { ?>
<p>Registro não localizado.</p>
<?php } ?>