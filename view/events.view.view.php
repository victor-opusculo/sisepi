<?php if($eventObj !== null) { ?>

<script>
	function presenceListButton_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events2", "viewpresencelist", null, "eventDateId={eventdateid}"); ?>';
		var eventDateId = this.getAttribute("data-eventDateId");
		window.location.href = url.replace("{eventdateid}", String(eventDateId));
	}
	
	function showSubscriptionList_onClick(e)
	{
		var url = '<?php echo URL\URLGenerator::generateSystemURL("events2", "viewsubscriptionlist", null, "eventId={eventid}"); ?>';
		var eventId = this.getAttribute("data-eventId");
		window.location.href = url.replace("{eventid}", String(eventId));
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
		
		let btnShowSubs = document.getElementById("showSubscriptionList");
		if (btnShowSubs) btnShowSubs.onclick = showSubscriptionList_onClick;
		
		document.getElementById("btnShowPresenceApps").onclick = btnShowPresenceApps_onClick;
		document.getElementById("btnShowCertificates").onclick = btnShowCertificates_onClick;
	};
</script>

<div class="viewDataFrame">
	<label>ID: </label><?php echo $eventObj->id; ?> <br/>
	<label>Nome: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo $eventObj->typeName; ?> <br/>
	<label>Responsável: </label><?php echo hsc($eventObj->responsibleForTheEvent); ?> <br/>
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
				echo '<tr><td class="shrinkCell">' . $formatedDate . '</td><td class="centControl">' . $d->beginTime . " - " . $d->endTime . "</td><td>" . hsc($d->name) . "</td><td>" . hsc($d->professorName) . '</td><td class="shrinkCell"><button class="btnViewPresenceList" data-eventDateId="' . $d->id . '" ' . ((!$d->presenceListNeeded) ? 'disabled="disabled"' : '') . '>Lista de presença</button></td></tr>';
			}
			?>
		</tbody>
	</table>
	<br/>
	<label>Lista de inscrição: </label><?php echo ($eventObj->subscriptionListNeeded) ? "Habilitada" : "Desabilitada"; ?> <?php if ($eventObj->subscriptionListNeeded) { ?><button id="showSubscriptionList" data-eventId="<?php echo $eventObj->id; ?>">Ver lista de inscrição</button> <label>Data de encerramento: </label><?php echo date_format(date_create($eventObj->subscriptionListClosureDate),"d/m/Y");?> <br/>
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
	<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("events", "edit", $eventObj->id); ?>">Editar</a></li>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events", "delete", $eventObj->id); ?>">Excluir</a></li>
		</ul>
	</div>
	<div class="centControl">
		<button id="btnShowPresenceApps" data-eventId="<?php echo $eventObj->id; ?>">Apontamento de presença</button>
		<button id="btnShowCertificates" data-eventId="<?php echo $eventObj->id; ?>" <?php echo ($eventObj->certificateText !== null) ? "" : 'disabled="disabled"' ?>>Certificados emitidos</button>
	</div>
</div>

<?php } else { ?>
<p>Registro não localizado.</p>
<?php } ?>