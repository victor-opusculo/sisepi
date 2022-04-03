<?php if ($eventObj !== null) { 

?>

<script>
	function presenceListButton_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, "eventDateId={eventdateid}"); ?>';
		var eventDateId = this.getAttribute("data-eventDateId");
		window.location.href = url.replace("{eventdateid}", eventDateId);
	}
		
	window.onload = function()
	{
		var butts = document.querySelectorAll("table button.btnSignPresenceList");
		for (let butt of butts)
			butt.onclick = presenceListButton_onClick;

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
	<label>Inscrições: </label><a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "viewsubscriptionlist", null, [ 'eventId' => $eventObj->id ] ); ?>">Ver relação de inscritos</a> <label>Data de encerramento: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
	<div class="centControl" style="margin-top: 30px;">
		<?php if ($passedSubscriptionClosureDate) { ?>
		<label>Período de inscrição encerrado!</label>
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
	<?php if ($eventObj->certificateText !== null) { ?>
	<div class="centControl">
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "gencertificate", null, [ 'eventId' => $eventObj->id ] ); ?>">Gerar certificado</a>
	</div>
	<?php } ?>
	<br/>
	
</div>

<?php } else { 
echo "Registro não encontrado.";
}
?>
