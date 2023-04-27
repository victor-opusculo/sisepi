<?php if ($eventObj !== null) { 

?>

<script>
	const eventDateURLGeneratorPath = "<?php echo hscq(URL\URLGenerator::generateFileURL("generate/getEventDateURL.php")); ?>";

	function presenceListButton_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, "eventDateId={eventdateid}"); ?>';
		var eventDateId = this.getAttribute("data-eventDateId");
		window.location.href = url.replace("{eventdateid}", eventDateId);
	}

	function loadEventDateURL(responseJson, tdElement)
	{
		if (responseJson)
		{
			if (responseJson.error)
				showBottomScreenMessageBox(BottomScreenMessageBoxType.error, responseJson.message);
			else
			{
				let aElement = tdElement.querySelector(".linkEventDateURL");
				aElement.href = responseJson.eventDateURL;
				aElement.innerText = responseJson.eventDateURL.length > 40 ? responseJson.eventDateURL.substring(0, 40) + '...' : responseJson.eventDateURL;
				aElement.style.display = 'unset';
				aElement.focus();

				tdElement.querySelector(".spanEmailConfirmationToShowURL").style.display = 'none';
			}
		}
		else
			showBottomScreenMessageBox(BottomScreenMessageBoxType.error, 'Erro ao carregar URL');
	}

	function btnEmailConfirmationToShowURL_onClick(e)
	{
		e.preventDefault();

		var eventId = this.getAttribute("data-eventId");
		var eventDateId = this.getAttribute("data-eventDateId");
		var email = this.parentNode.querySelector("input[type='email']").value;

		fetch(eventDateURLGeneratorPath + 
		"?eventId=" + eventId + 
		"&eventDateId=" + eventDateId + 
		"&email=" + email).then( res => res.json() ).then( json => loadEventDateURL(json, this.parentNode.parentNode) );
	}
		
	window.onload = function()
	{
		this.document.querySelectorAll("table button.btnSignPresenceList").forEach( butt => butt.onclick = presenceListButton_onClick);
		this.document.querySelectorAll(".spanEmailConfirmationToShowURL").forEach( span => span.querySelector("button").onclick = btnEmailConfirmationToShowURL_onClick );
	};
</script>
<?php if ($eventObj->posterImageAttachmentFileName): ?>
<div class="centControl responsiveImageFrame">
	<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/$eventObj->posterImageAttachmentFileName"); ?>" style="border-radius: 10px;"/>
</div>
<?php endif; ?>

<?php $tabsComp->render();
$tabsComp->beginTabsFrame();
$tabsComp->beginTab("Principal", true); ?>

<div class="viewDataFrame">
	<label>Nome: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->getOtherProperties()->typeName); ?> <br/>
	<label>Modalidade: </label><?php echo hsc(Data\getEventMode($eventObj->getOtherProperties()->locTypes)); ?> <br/>
	<label>Carga horária: </label> <?php echo round(timeStampToHours($eventObj->getOtherProperties()->hours), 1); ?>h<br/>
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
			</tr>
		</thead>
		<tbody>
			<?php foreach ($eventObj->eventDates as $d)
			{
				$disabledState = (bool)$d->getOtherProperties()->isPresenceListOpen ? '' : ' disabled ';
				$formatedDate = date_format(date_create($d->date), "d/m/Y");
				echo '<tr class="expandableTableRow" tabindex="0"><td class="shrinkCell">' . $formatedDate . '</td><td class="centControl">' . date_create($d->beginTime)->format('H:i') . " - " . date_create($d->endTime)->format('H:i') . "</td><td>" . hsc($d->name) . '</td><td class="shrinkCell">';
				if ($d->presenceListNeeded)
					echo '<button class="btnSignPresenceList" data-eventDateId="' . $d->id . '" ' . $disabledState . ' >Assinar lista de presença</button>';
				echo '</td></tr>';
				?>
				<tr class="tableRowExpandInfosPanel" tabindex="1">
					<td colspan="5">
						<div>
						<?php
							$localInfos = json_decode($d->locationInfosJson);
							$url = $localInfos->url ?? '';
							$moreInfos = $localInfos->infos ?? '';
						?>
						<label>Docentes: </label><?php echo hsc($d->getProfessorsNames()); ?>
						<br/>
						<label>Local: </label><?php echo !empty($d->getOtherProperties()->locationName) ? hsc($d->getOtherProperties()->locationName) : 'Indefinido'; ?>
						<br/>
						<?php if (!empty($url) && (bool)$eventObj->subscriptionListNeeded === true): ?>
							<label>Link: </label><a class="linkEventDateURL" style="display:none;" href=""></a>
							<form class="spanEmailConfirmationToShowURL" style="display: inline;">
								<input type="email" size="30" placeholder="Insira aqui seu e-mail para ver o link" />
								<button type="submit" style="min-width: 20px; font-size: 0.8em;" data-eventId="<?php echo $eventObj->id; ?>" data-eventDateId="<?php echo $d->id; ?>">&#128275;</button> 
							</form>
							<br/>
						<?php elseif (!empty($url) && (bool)$eventObj->subscriptionListNeeded === false): ?>
							<label>Link: </label><a class="linkEventDateURL" href="<?php echo $url; ?>"><?php echo hsc(truncateText($url, 40)); ?></a>
							<br/>
						<?php endif; ?>
						<?php if (!empty($moreInfos)): ?>
							<label>Informações: </label><?php echo $moreInfos; ?>
							<br/>
						<?php endif; ?>
						<?php if (!empty($d->traits)): ?>
							<label>Traços: </label><br/>
							<?php foreach ($d->traits as $trait): ?>
								<img src="<?= URL\URLGenerator::generateBaseDirFileURL("uploads/traits/{$trait->id}.{$trait->fileExtension}") ?>" height="32" alt="<?= $trait->name ?>" title="<?= $trait->name . ': ' . $trait->description ?>" />
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
	
	<?php if (count($eventObj->eventAttachments) > 0) { ?>
	<label>Anexos: </label>
	<div>
		<ul>
		<?php 
		$attachsPath = URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/");
		foreach ($eventObj->eventAttachments as $a)
		{ 
			echo '<li><a href="' . $attachsPath . $a->fileName . '">' . $a->fileName . "</a></li>";
		}
		?>
		</ul>
	</div>
	<?php } ?>
	
	<br/>
	<?php if ($eventObj->subscriptionListNeeded) { ?>
	<label>Inscrições: </label><a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "viewsubscriptionlist", null, [ 'eventId' => $eventObj->id ] ); ?>">Ver relação de inscritos</a> <label>Data de encerramento: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
	<div class="centControl" style="margin-top: 30px;">
		<?php if ($passedSubscriptionClosureDate) { ?>
		<label>Período de inscrição encerrado!</label>
		<?php } else if ($isSubscriptionYetToOpen) { ?>
		<label>As inscrições ainda não abriram. Abertura prevista para <?php echo dateInFullString($eventObj->subscriptionListOpeningDate); ?>. </label> 
		<?php } else if ($isSubscriptionListFull) { ?>
		<label>Todas as vagas foram preenchidas!</label>
		<?php } else { ?>
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "subscribe", null, [ 'eventId' => $eventObj->id ]); ?>">Inscrever-se</a>
		<?php } ?>
	</div>
	<?php } else { ?>	
	<label>Este evento não requer inscrição.</label>	
	<?php } ?>
	<br/>
	
	<div class="centControl">
		<?php if (!empty($eventObj->surveyTemplateId)): ?>
			<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events2", "fillsurvey", null, [ 'eventId' => $eventObj->id ] ); ?>">Preencher pesquisa de satisfação</a>
		<?php endif; ?>
		<?php if ($eventObj->certificateText !== null) { ?>
			<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "gencertificate", null, [ 'eventId' => $eventObj->id ] ); ?>">Gerar certificado</a>
		<?php } ?>
	</div>
	
	<br/>
	
</div>

<?php $tabsComp->endToBeginTab("ODS"); ?>
	<?php include "view/fragment/events.odsrelation.view.php"; ?>
<?php $tabsComp->endTab();
$tabsComp->endTabsFrame();
?>

<?php } else { 
echo "Evento não encontrado.";
}
?>
