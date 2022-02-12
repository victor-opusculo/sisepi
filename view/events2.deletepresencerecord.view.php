<?php
if (!empty($_GET["messages"]))
{ ?>

<?php }
else if ($presenceObj !== null)
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/deletepresencerecord.post.php", "cont=events2&action=deletepresencerecord&id=$presenceObj->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta presença? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo hsc($presenceObj->name) . (isset($presenceObj->socialName) && $presenceObj->socialName ? " (" . hsc($presenceObj->socialName) . ")" : ""); ?><br/>
		<label>E-mail: </label><?php echo hsc($presenceObj->email); ?> <br/><br/>
		
		<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
		<label>Data da lista: </label><?php echo date_format(date_create($eventDateObj->date), "d/m/Y"); ?><br/>
		<label>Horário: </label><?php echo $eventDateObj->beginTime . " - " . $eventDateObj->endTime; ?><br/>
		<br/>
		<input type="hidden" name="presenceId" value="<?php echo $presenceObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeletePresence" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("events2", "viewpresencelist", null, [ 'eventDateId' => $eventDateObj->id]); ?>';"/>
	</div>
	
</form>

<?php
}
?>