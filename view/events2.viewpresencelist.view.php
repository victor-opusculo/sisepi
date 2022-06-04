<?php if($eventDateObj !== null) { ?>

<div class="viewDataFrame" style="margin-bottom: 20px;">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $eventObj->id); ?>"><?php echo hsc($eventObj->name); ?></a> <br/>
	<label>Tipo: </label><?php echo $eventObj->typeName; ?> <br/>
	<label>Data da lista: </label><?php echo date_format(date_create($eventDateObj->date), "d/m/Y"); ?> <br/>
	<label>Horário: </label><?php echo $eventDateObj->beginTime . " - " . $eventDateObj->endTime; ?> <br/>
	<label>Nome/Conteúdo: </label><?php echo hsc($eventDateObj->name); ?> <br/>
	<label>Docentes: </label><?php echo hsc($eventDateObj->professorsNames); ?> <br/>
	<br/>
	<label>Status da lista: </label><?php echo $eventDateObj->isPresenceListOpen ? "Aberta" : "Fechada"; ?> <br/>
	<label>Senha de acesso: </label><?php echo hsc($eventDateObj->presenceListPassword); ?> <br/>
	<label>Link para assinatura: </label><a href="<?php echo URL\URLGenerator::generatePublicSystemURL("events", "signpresencelist", null, ['eventDateId' => $eventDateObj->id]); ?>">Acesse aqui</a> <br/>
	<label>Número de assinaturas: </label><?php echo $presenceCount; ?>
</div>

<?php if($dgComp) $dgComp->render(); ?>

<div class="editDeleteButtonsFrame">
	<ul>
		<li><a href="<?php echo URL\URLGenerator::generateSystemURL("events2", "markpresence", null, ['eventDateId' => $eventDateObj->id]); ?>">Marcar presença</a></li>
	</ul>
</div>

<?php } else { ?>
<p>Registro não localizado.</p>
<?php } ?>