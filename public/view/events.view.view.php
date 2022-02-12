<?php if ($eventObj !== null) { 

?>

<script>
	function presenceListButton_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, "eventDateId={eventdateid}"); ?>';
		var eventDateId = this.getAttribute("data-eventDateId");
		window.location.href = url.replace("{eventdateid}", eventDateId);
	}
	
	function showSubscriptionList_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "viewsubscriptionlist", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", eventId);
	}
	
	function btnSubscribeEvent_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "subscribe", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", eventId);
	}
	
	function btnGenerateCertificate_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "gencertificate", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", eventId);
	}
	
	window.onload = function()
	{
		var butts = document.querySelectorAll("table button.btnSignPresenceList");
		for (let butt of butts)
			butt.onclick = presenceListButton_onClick;
		
		var btnShowSubscriptionList = document.getElementById("showSubscriptionList");
		var btnSubscribeEvent = document.getElementById("btnSubscribeEvent");
		var btnGenerateCertificate = document.getElementById("btnGenerateCertificate");
		
		if (btnShowSubscriptionList) btnShowSubscriptionList.onclick = showSubscriptionList_onClick;
		if (btnSubscribeEvent) btnSubscribeEvent.onclick = btnSubscribeEvent_onClick;
		if (btnGenerateCertificate) btnGenerateCertificate.onclick = btnGenerateCertificate_onClick;
	};
</script>
<?php if ($eventObj->posterImageAttachmentFileName): ?>
<div class="centControl responsiveImageFrame">
	<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/$eventObj->posterImageAttachmentFileName"); ?>" style="border-radius: 10px;"/>
</div>
<?php endif; ?>

<div class="viewDataFrame">
	<label>Nome: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
	<label>Responsável: </label><?php echo hsc($eventObj->responsibleForTheEvent); ?> <br/>
	<label>Carga horária: </label> <?php echo round(timeStampToHours($eventObj->hours), 1); ?>h<br/>
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
			</tr>
		</thead>
		<tbody>
			<?php foreach ($eventObj->dates as $d)
			{
				$formatedDate = date_format(date_create($d->date), "d/m/Y");
				echo '<tr><td class="shrinkCell">' . $formatedDate . '</td><td class="centControl">' . $d->beginTime . " - " . $d->endTime . "</td><td>" . hsc($d->name) . "</td><td>" . hsc($d->professorName) . '</td><td class="shrinkCell">';
				if ($d->presenceListNeeded)
					echo '<button class="btnSignPresenceList" data-eventDateId="' . $d->id . '" ' . (($d->isPresenceListOpen) ? '' : 'disabled="disabled"') . '>Assinar lista de presença</button>';
				echo '</td></tr>';
			}
			?>
		</tbody>
	</table>
	<br/>
	
	<?php if (count($eventObj->attachments) > 0) { ?>
	<label>Anexos: </label>
	<div>
		<ul>
		<?php 
		$attachsPath = URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/");
		foreach ($eventObj->attachments as $a)
		{ 
			echo '<li><a href="' . $attachsPath . $a->fileName . '">' . $a->fileName . "</a></li>";
		}
		?>
		</ul>
	</div>
	<?php } ?>
	
	<br/>
	<?php if ($eventObj->subscriptionListNeeded) { ?>
	<label>Inscrições: </label><button id="showSubscriptionList" data-eventId="<?php echo $eventObj->id; ?>">Ver relação de inscritos</button> <label>Data de encerramento: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
	<div class="centControl" style="margin-top: 30px;">
		<?php if ($passedSubscriptionClosureDate) { ?>
		<label>Período de inscrição encerrado!</label>
		<?php } else if ($isSubscriptionListFull) { ?>
		<label>Todas as vagas foram preenchidas!</label>
		<?php } else { ?>
		<button id="btnSubscribeEvent" data-eventId="<?php echo $eventObj->id; ?>">Inscrever-se</button>
		<?php } ?>
	</div>
	<?php } else { ?>	
	<label>Este evento não requer inscrição.</label>	
	<?php } ?>
	<br/>
	<?php if ($eventObj->certificateText !== null) { ?>
	<div class="centControl">
		<button id="btnGenerateCertificate" data-eventId="<?php echo $eventObj->id; ?>">Gerar certificado</button>
	</div>
	<?php } ?>
	<br/>
	
</div>

<?php } else { 
echo "Registro não encontrado.";
}
?>
