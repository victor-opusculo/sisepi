<?php if ($presenceObj !== null)
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/editpresencerecord.post.php", "cont=events2&action=editpresencerecord&id=$presenceObj->id"); ?>" method="post">

	<div class="viewDataFrame">
		<label>Nome: </label><input type="text" name="txtName" size="60" maxlength="110" value="<?php echo hscq($presenceObj->name); ?>"/><br/>
		<label>E-mail: </label><input type="text" name="txtEmail" size="40" maxlength="110" value="<?php echo hscq($presenceObj->email); ?>"/><br/><br/>
		
		<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
		<label>Data da lista: </label><?php echo date_format(date_create($eventDateObj->date), "d/m/Y"); ?><br/>
		<label>Horário: </label><?php echo $eventDateObj->beginTime . " - " . $eventDateObj->endTime; ?><br/>
		<br/>
		<input type="hidden" name="presenceId" value="<?php echo $presenceObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitEditPresence" value="Alterar dados"/>
	</div>
	
</form>

<?php
}
?>