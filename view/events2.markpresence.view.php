<?php if($eventDateObj !== null) { ?>

<div class="viewDataFrame" style="margin-bottom: 20px;">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $eventObj->id); ?>"><?php echo hsc($eventObj->name); ?></a> <br/>
	<label>Tipo: </label><?php echo $eventObj->typeName; ?> <br/>
	<label>Data da lista: </label><?php echo date_format(date_create($eventDateObj->date), "d/m/Y"); ?> <br/>
	<label>Horário: </label><?php echo $eventDateObj->beginTime . " - " . $eventDateObj->endTime; ?> <br/>
	<label>Nome/Conteúdo: </label><?php echo hsc($eventDateObj->name); ?> <br/>
	<label>Docentes: </label><?php echo hsc($eventDateObj->professorsNames); ?> <br/>
</div>

<?php if($eventObj->subscriptionListNeeded): ?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/markpresencesubs.post.php", "cont=events2&action=markpresence&eventDateId=$eventDateObj->id"); ?>" method="post">
	<span class="formField">
		<label>Inscrito: 
			<select name="selSubscriptionId" required="required">
				<?php foreach ($subscriptionList as $sub): ?>
				<option value="<?php echo $sub["id"]; ?>"><?php echo hsc($sub["name"]) . (isset($sub["socialName"]) && $sub["socialName"] ? (" (" . hsc($sub["socialName"]) . ")") : ""); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
	<input type="hidden" name="eventDateId" value="<?php echo $eventDateObj->id; ?>"/>
	<input type="submit" name="btnsubmitMarkPresence" value="Marcar"/>
</form>

<?php else: ?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/markpresencenosubs.post.php", "cont=events2&action=markpresence&eventDateId=$eventDateObj->id"); ?>" method="post">
	<span class="formField">
		<label>Nome: <input type="text" name="txtName" size="60" maxlength="110" required="required"/></label>
	</span>
	<span class="formField">
		<label>E-mail: <input type="email" name="txtEmail" size="40" maxlength="110" required="required"/></label>
	</span>
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
	<input type="hidden" name="eventDateId" value="<?php echo $eventDateObj->id; ?>"/>
	<input type="submit" name="btnsubmitMarkPresence" value="Marcar"/>
</form>

<?php endif; ?>

<?php } else { ?>
<p>Registro não localizado.</p>
<?php } ?>