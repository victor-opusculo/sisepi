<script>
	const professorsList = 
	[
		{id:"", name:"(Indefinido)"}
	];
	
	<?php foreach ($professors as $prof): ?> 
	professorsList.push(
	{
		id:<?php echo $prof["id"]; ?>,
		name:'<?php echo hscq($prof["name"]); ?>'
	});
	<?php endforeach; ?>
</script>

<?php if ($eventObj !== null): ?>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/events.edit.view.js"); ?>"></script>
<?php endif; ?>

<?php 

if ($eventObj !== null):

	$formAction = null;
	if ($operation === "edit")
	{
		$formAction = URL\URLGenerator::generateFileURL("post/editevent.post.php", "cont=events&action=edit&id=" . $eventObj->id);
	}
	else
	{
		$formAction = URL\URLGenerator::generateFileURL("post/createevent.post.php", "cont=events&action=create");
	}
?>

<form enctype="multipart/form-data" action="<?php echo $formAction; ?>" method="post">
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
	<span class="formField"><label>Nome: </label><input type="text" name="txtEventName" size="80" maxlength="120" required="required" value="<?php echo hscq($eventObj->name); ?>"/></span>
	<span class="formField"><label>Tipo: </label><select name="cmbEventType" required="required"/>
		<?php foreach ($eventTypes as $et)
		{
			$isEventOfCurrentType = $et["id"] == $eventObj->typeId;
			echo '<option value="' . $et["id"] . '" ' . ($isEventOfCurrentType ? 'selected="selected"' : '') . '>' . $et["name"] . '</option>';
		}
		?>
	</select></span>
	<span class="formField"><Label>Responsável: </label><input type="text" name="txtResponsibleForTheEvent" size="70" value="<?php echo hsc($eventObj->responsibleForTheEvent); ?>"/></span>
	<span class="formField"><label>Mais informações: <br/>
			<textarea name="txtMoreInfos" rows="5" style="width:100%;"><?php echo hsc($eventObj->moreInfos); ?></textarea>
		</label>
	</span>
	<span class="formField">
		<label>Datas: </label><button type="button" id="btnCreateNewDate">Criar nova</button>
		<table id="tableEventDates">
			<thead>
				<tr>
					<th>Dia</th><th>Horário</th><th>Nome/Conteúdo</th><th>Docente</th><th>Lista de presença?</th><th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($eventObj->dates as $d): ?>
				<tr data-dateId="<?php echo $d->id; ?>">
					<td><input type="date" class="eventDateDate" required="required" value="<?php echo $d->date; ?>"></td>
					<td><input type="time" class="eventDateTimeBegin" required="required" step="1" value="<?php echo $d->beginTime; ?>"><input type="time" class="eventDateTimeEnd" required="required" step="1" value="<?php echo $d->endTime; ?>"></td>
					<td><input type="text" class="eventDateName" size="20" maxlength="120" value="<?php echo hscq($d->name); ?>"/></td>
					<td>
						<select class="eventDateProfessor" style="width: 200px;">
							<option value="">(Indefinido)</option>
							<?php foreach ($professors as $prof)
							{
								$isEventDateOfCurrentProfessor = $prof["id"] === $d->professorId;
								echo '<option value="' . $prof["id"] . '" ' . ($isEventDateOfCurrentProfessor ? 'selected="selected"' : '') . '>' . hscq($prof["name"]) . '</option>';
							} ?>
						</select>
					</td>
					<td>
						<label><input type="checkbox" class="eventDatePresenceListEnabled" value="1" <?php echo ($d->presenceListNeeded ? 'checked="checked"' : ''); ?>/>Habilitar</label>
						(<a href="#" class="setPresenceListPassword">Senha</a>)
						<input type="hidden" class="eventDatePresenceListPassword" value="<?php echo $d->presenceListPassword; ?>"/>
					</td>
					<td><input type="button" class="eventDateDeleteButton" data-dateId="<?php echo $d->id; ?>" style="min-width: 20px;" value="X"/></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</span>
	<input type="hidden" id="eventDatesChangesReport" name="eventDatesChangesReport" value=""/>
	<br/>
	<span class="formField">
		<label><input type="checkbox" id="chkSubscriptionListNeeded" name="chkSubscriptionListNeeded" value="1" <?php echo ($eventObj->subscriptionListNeeded ? 'checked="checked"' : ''); ?>/> Habilitar lista de inscrição</label>
	</span>
	<span class="formField" id="spanSubscriptionExtraParameters" style="display: <?php echo ($eventObj->subscriptionListNeeded ? 'block' : 'none') ; ?>">
		<label>Número de vagas: </label><input type="number" id="txtMaxSubscriptionNumber" name="txtMaxSubscriptionNumber" min="1" max="10000000000" value="<?php echo $eventObj->maxSubscriptionNumber; ?>"/>
		<label>Data de encerramento da lista: </label><input type="date" id="dateSubscriptionListClosureDate" name="dateSubscriptionListClosureDate" value="<?php echo $eventObj->subscriptionListClosureDate; ?>"/>
		<br/>
		<label><input type="checkbox" name="chkAllowLateSubscriptions" value="1" <?php echo ($eventObj->allowLateSubscriptions ? 'checked="checked"' : ''); ?>/>Permitir inscrições tardias nas listas de presença</label>
	</span>
	<br/>
	
	<span class="formField"><label><input type="checkbox" id="chkAutoCertificate" name="chkAutoCertificate" value="1" <?php echo $eventObj->certificateText !== null ? 'checked="checked"' : '' ?>/>Permitir geração automática de certificados</label></span>
	<span id="spanCertificateText" style="<?php echo $eventObj->certificateText !== null ? "display:block;" : "display:none;" ?>">
		<label>
			Texto para o certificado:
			<textarea id="txtCertificateText" name="txtCertificateText" rows="5" style="width:100%;" maxlength="500"><?php echo hsc($eventObj->certificateText); ?></textarea>
		</label>
		<br/>
		<label>Imagem de fundo do certificado: <input type="text" id="txtCertificateBgFile" name="txtCertificateBgFile" size="60" maxlength="255" value="<?php echo hscq($eventObj->certificateBgFile); ?>"/></label>
	</span>
	
	<br/>
	<span class="formField">
		<label>Anexos:</label><button type="button" id="btnCreateNewAttachment">Criar novo</button>
		<table id="tableEventAttachments">
			<tbody>
			<?php foreach ($eventObj->attachments as $a): ?>
				<tr data-attachId="<?php echo $a->id; ?>">
					<td><span class="existentFileName"><?php echo $a->fileName; ?></span></td>
					<?php $isAttachmentPosterImage = $eventObj->posterImageAttachmentFileName === $a->fileName; ?>
					<td><label><input type="radio" name="radAttachmentPosterImage" value="<?php echo $a->fileName; ?>" <?php echo ($isAttachmentPosterImage ? 'checked="checked"' : ''); ?>/>Cartaz</label></td>
					<td class="shrinkCell"><input type="button" class="btnDeleteAttachment" data-attachId="<?php echo $a->id; ?>" style="min-width: 20px;" value="X"/></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" id="eventAttachmentsChangesReport" name="eventAttachmentsChangesReport" value=""/>
	</span>
	<br/>
	<div class="centControl">
		<input type="submit" id="btnsubmitSubmit" name="btnsubmitSubmit" value="Enviar dados"/>
	</div>
</form>
<?php else: ?>
Registro não localizado.
<?php endif; ?>