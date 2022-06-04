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

<!-- Page elements templates -->
<div id="pageElementsTemplates" style="display: none;">
	<table>	
		<tr id="newEventDateTableRow">
			<td><input type="date" class="eventDateDate" required="required"></td>
			<td><input type="time" class="eventDateTimeBegin" required="required" step="1" ><input type="time" class="eventDateTimeEnd" required="required" step="1"></td>
			<td><input type="text" class="eventDateName" size="20" maxlength="120"/></td>
			<td>
				<span class="dropdownMenuButtonArea">
					<button type="button" style="min-width: 20px;">Definir <img src="<?php echo URL\URLGenerator::generateFileURL("pics/menu.svg"); ?>" width="24" height="24" title="Definir docentes" alt="Definir docentes"/></button>
					<ul class="dropdownMenu">
						<hr>
						<li>
							<a href="#" class="eventDateProfessor_addNew">Adicionar</a>
						</li>
					</ul>
				<span>
			</td>
			<td>
				<label><input type="checkbox" class="eventDatePresenceListEnabled" value="1" checked="checked"/>Habilitar</label>
			</td>
			<td>
				<span class="dropdownMenuButtonArea">
					<button type="button" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/menu.svg"); ?>" width="24" height="24" title="Mais opções" alt="Mais opções"/></button>
					<ul class="dropdownMenu">
						<li><label>Senha: <input type="text" style="flex: 0 0 100px;" maxlength="4" class="eventDatePresenceListPassword" required="required" value=""/></label></li>
						<hr/>
						<li>
							<label>Checklist: 
								<select class="eventDateChecklistActions">
									<option value="-1">(Não usar checklist)</option>
									<?php foreach ($checklistTemplatesAvailable as $ct): ?>
										<option value="<?php echo $ct['id']; ?>">Novo: <?php echo hsc($ct['name']); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</li>
						<hr/>
						<li>
							<label>Local: 
								<select class="eventDateLocationId">
									<option value="">(Indefinido)</option>
									<?php foreach ($eventLocations as $el): ?>
										<option value="<?php echo $el['id']; ?>"><?php echo hsc($el['name']); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</li>
						<li><label>URL: <input type="text" class="eventDateLocationURL" value=""/></label></li>
						<li><label>Infos local: <input type="text" class="eventDateLocationInfos" value=""/></label></li>
					</ul>
				</span>
			</td>
			<td><input type="button" class="eventDateDeleteButton" style="min-width: 20px;" value="&times;"/></td>
		</tr>
	</table>

	<span id="newEventCustomInfo" class="formField spanCustomInfo">
		<input type="text" class="txtCustomInfoLabel" placeholder="Nome" maxlength="80" size="20" />
		<input type="text" class="txtCustomInfoValue" placeholder="Informação" maxlength="280" size="60" />
		<button type="button" class="btnCustomInfoDelete" style="min-width: 20px;">&times;</button> 
	</span>

	<ul>
		<li id="newEventDateProfessor">
			<select class="eventDateProfessor" style="width: 250px;">
				<?php foreach ($professors as $prof)
				{
					echo '<option value="' . $prof["id"] . '">' . hsc($prof["name"]) . '</option>';
				} ?>
			</select>
			<a style="display: inline; font-weight: bold;" href="#" class="eventDateProfessor_remove"> &times; </a>
		<li>
	</ul>
</div>

<?php $tabsComp->render(); ?>
<form enctype="multipart/form-data" action="<?php echo $formAction; ?>" method="post">
<?php $tabsComp->beginTabsFrame(); ?>
<?php $tabsComp->beginTab("Principal", true); ?>
	<input type="hidden" name="events:eventId" value="<?php echo $eventObj->id; ?>"/>
	<span class="formField"><label>Nome: </label><input type="text" name="events:txtEventName" size="80" maxlength="120" required="required" value="<?php echo hscq($eventObj->name); ?>"/></span>
	<span class="formField"><label>Tipo: </label><select name="events:cmbEventType" required="required"/>
		<?php foreach ($eventTypes as $et)
		{
			$isEventOfCurrentType = $et["id"] == $eventObj->typeId;
			echo '<option value="' . $et["id"] . '" ' . ($isEventOfCurrentType ? 'selected="selected"' : '') . '>' . $et["name"] . '</option>';
		}
		?>
	</select></span>
	<span class="formField"><Label>Responsável: </label><input type="text" name="events:txtResponsibleForTheEvent" size="70" value="<?php echo hsc($eventObj->responsibleForTheEvent); ?>"/></span>
	<span class="formField"><label>Campos customizáveis:</label></span>
	<div id="divCustomInfos">
		<?php $customInfos = json_decode($eventObj->customInfosJson);
		if (isset($customInfos) && count($customInfos) > 0):
		foreach ($customInfos as $ci): ?>
			<span class="formField spanCustomInfo">
				<input type="text" class="txtCustomInfoLabel" placeholder="Nome" maxlength="80" size="20" value="<?php echo $ci->label; ?>" />
				<input type="text" class="txtCustomInfoValue" placeholder="Informação" maxlength="280" size="60" value="<?php echo $ci->value; ?>" />
				<button type="button" class="btnCustomInfoDelete" style="min-width: 20px;">&times;</button> 
			</span>
		<?php endforeach;
		endif; ?>
	</div>
	<button type="button" id="btnAddCustomInfo">Adicionar</button>
	<span class="formField"><label>Mais informações: <br/>
			<textarea name="events:txtMoreInfos" rows="5" style="width:100%;"><?php echo hsc($eventObj->moreInfos); ?></textarea>
		</label>
	</span>
	<span class="formField">
		<label>Datas: </label><button type="button" id="btnCreateNewDate">Criar nova</button>
		<table id="tableEventDates">
			<thead>
				<tr>
					<th>Dia</th><th>Horário</th><th>Nome/Conteúdo</th><th>Docentes</th><th>Lista de presença?</th><th></th><th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($eventObj->dates as $d): ?>
				<tr data-dateId="<?php echo $d->id; ?>">
					<td><input type="date" class="eventDateDate" required="required" value="<?php echo $d->date; ?>"></td>
					<td><input type="time" class="eventDateTimeBegin" required="required" step="1" value="<?php echo $d->beginTime; ?>"><input type="time" class="eventDateTimeEnd" required="required" step="1" value="<?php echo $d->endTime; ?>"></td>
					<td><input type="text" class="eventDateName" size="20" maxlength="120" value="<?php echo hscq($d->name); ?>"/></td>
					<td>
						<span class="dropdownMenuButtonArea">
							<button type="button" style="min-width: 20px;">Definir <img src="<?php echo URL\URLGenerator::generateFileURL("pics/menu.svg"); ?>" width="24" height="24" title="Definir docentes" alt="Definir docentes"/></button>
							<ul class="dropdownMenu">
								<?php foreach ($d->professors as $profObj): ?>
									<li>
										<select class="eventDateProfessor" style="width: 250px;">
											<?php foreach ($professors as $prof)
											{
												$isEventDateOfCurrentProfessor = $prof["id"] === $profObj->professorId;
												echo '<option value="' . $prof["id"] . '" ' . ($isEventDateOfCurrentProfessor ? 'selected="selected"' : '') . '>' . hsc($prof["name"]) . '</option>';
											} ?>
										</select><a style="display: inline; font-weight: bold;" href="#" class="eventDateProfessor_remove"> &times; </a>
									</li>
								<?php endforeach; ?>
								<hr>
								<li>
									<a href="#" class="eventDateProfessor_addNew">Adicionar</a>
								</li>
							</ul>
						<span>
					</td>
					<td>
						<label><input type="checkbox" class="eventDatePresenceListEnabled" value="1" <?php echo ($d->presenceListNeeded ? 'checked="checked"' : ''); ?>/>Habilitar</label>
					</td>
					<td class="shrinkCell">
						<span class="dropdownMenuButtonArea">
							<button type="button" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/menu.svg"); ?>" width="24" height="24" title="Mais opções" alt="Mais opções"/></button>
							<ul class="dropdownMenu">
								<li><label>Senha: <input type="text" style="flex: 0 0 100px;" maxlength="4" class="eventDatePresenceListPassword" required="required" value="<?php echo hscq($d->presenceListPassword); ?>"/></label></li>
								<hr/>
								<li>
									<label>Checklist: 
										<select class="eventDateChecklistActions">
											<option value="-1">(Não usar checklist)</option>
											<?php if (!empty($d->checklistId)): ?>
												<option value="0" selected="selected">(Manter checklist atual)</option>
											<?php endif; ?>
											<?php foreach ($checklistTemplatesAvailable as $ct): ?>
												<option value="<?php echo $ct['id']; ?>">Novo: <?php echo hsc($ct['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</label>
								</li>
								<?php if (!empty($d->checklistId)): ?>
								<li>
									<a href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklists", "edit", $d->checklistId); ?>">Editar checklist atual</a>
								</li>
								<?php endif; ?>
								<hr/>
								<li>
									<label>Local: 
										<select class="eventDateLocationId">
											<option value="">(Indefinido)</option>
											<?php foreach ($eventLocations as $el): ?>
												<option <?php echo $d->locationId == $el['id'] ? ' selected="selected" ' : ''; ?> value="<?php echo $el['id']; ?>"><?php echo hsc($el['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</label>
								</li>
								<?php $infosDecoded = json_decode($d->locationInfosJson);
									$url = $infosDecoded->url ?? '';
									$localInfos = $infosDecoded->infos ?? '';
									?>
								<li>
									<label>URL: <input type="text" class="eventDateLocationURL" value="<?php echo hscq($url); ?>"/></label>
								</li>
								<li>
									<label>Infos local: <input type="text" class="eventDateLocationInfos" value="<?php echo hscq($localInfos); ?>"/></label>
								</li>
							</ul>
						</span>
					</td>
					<td class="shrinkCell"><input type="button" class="eventDateDeleteButton" data-dateId="<?php echo $d->id; ?>" style="min-width: 20px;" value="&times;"/></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</span>
	<input type="hidden" id="eventDatesChangesReport" name="eventdates:eventDatesChangesReport" value=""/>
	<br/>
	<span class="formField">
		<label><input type="checkbox" id="chkSubscriptionListNeeded" name="events:chkSubscriptionListNeeded" value="1" <?php echo ($eventObj->subscriptionListNeeded ? 'checked="checked"' : ''); ?>/> Habilitar lista de inscrição</label>
	</span>
	<span class="formField" id="spanSubscriptionExtraParameters" style="display: <?php echo ($eventObj->subscriptionListNeeded ? 'block' : 'none') ; ?>">
		<label>Número de vagas: </label><input type="number" id="txtMaxSubscriptionNumber" name="events:txtMaxSubscriptionNumber" min="1" max="10000000000" value="<?php echo $eventObj->maxSubscriptionNumber; ?>"/>
		<label>Data de encerramento da lista: </label><input type="date" id="dateSubscriptionListClosureDate" name="events:dateSubscriptionListClosureDate" value="<?php echo $eventObj->subscriptionListClosureDate; ?>"/>
		<br/>
		<label><input type="checkbox" name="events:chkAllowLateSubscriptions" value="1" <?php echo ($eventObj->allowLateSubscriptions ? 'checked="checked"' : ''); ?>/>Permitir inscrições tardias nas listas de presença</label>
	</span>
	<br/>
	
	<span class="formField"><label><input type="checkbox" id="chkAutoCertificate" name="events:chkAutoCertificate" value="1" <?php echo $eventObj->certificateText !== null ? 'checked="checked"' : '' ?>/>Permitir geração automática de certificados</label></span>
	<span id="spanCertificateText" style="<?php echo $eventObj->certificateText !== null ? "display:block;" : "display:none;" ?>">
		<label>
			Texto para o certificado:
			<textarea id="txtCertificateText" name="events:txtCertificateText" rows="5" maxlength="500"><?php echo hsc($eventObj->certificateText); ?></textarea>
		</label>
		<br/>
		<label>Imagem de fundo do certificado: <input type="text" id="txtCertificateBgFile" name="events:txtCertificateBgFile" size="60" maxlength="255" value="<?php echo hscq($eventObj->certificateBgFile); ?>"/></label>
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
					<td><label><input type="radio" name="events:radAttachmentPosterImage" value="<?php echo $a->fileName; ?>" <?php echo ($isAttachmentPosterImage ? 'checked="checked"' : ''); ?>/>Cartaz</label></td>
					<td class="shrinkCell"><input type="button" class="btnDeleteAttachment" data-attachId="<?php echo $a->id; ?>" style="min-width: 20px;" value="&times;"/></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" id="eventAttachmentsChangesReport" name="eventattachments:eventAttachmentsChangesReport" value=""/>
	</span>
	<?php $tabsComp->endToBeginTab("Plano de trabalho"); ?>
		<?php $workplanPage->render(); ?>	
	<?php $tabsComp->endToBeginTab("Checklist"); ?>
		<div>
			<label>Ação: 
				<select name="selEventChecklistActions">
					<option value="-1">(Não usar checklist)</option>
					<?php if (!empty($eventObj->checklistId)): ?>
						<option value="0" selected="selected">(Manter checklist atual)</option>
					<?php endif; ?>
					<?php foreach ($checklistTemplatesAvailable as $ct): ?>
						<option value="<?php echo $ct['id']; ?>">Novo: <?php echo $ct['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
		<?php $eventchecklistEditPage->render(); ?>
		<input type="hidden" name="eventchecklists:checklistId" value="<?php echo $eventObj->checklistId; ?>" />
		<input type="hidden" id="eventchecklistsJson" name="eventchecklists:checklistJson" value=""/>
		<input type="hidden" id="eventCustomInfosJson" name="events:hidCustomInfos" value="" />
	<?php $tabsComp->endTab(); ?>
	<?php $tabsComp->endTabsFrame(); ?>
	<br/>
	<div class="centControl">
		<input type="submit" id="btnsubmitSubmit" name="btnsubmitSubmit" value="Enviar dados"/>
	</div>
</form>
<?php else: ?>
Registro não localizado.
<?php endif; ?>