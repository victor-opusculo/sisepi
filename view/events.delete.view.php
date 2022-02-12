<?php
if ($eventDataRow !== null)
{
	$eventId = $eventDataRow['id'];
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/deleteevent.post.php", "cont=events&action=delete&id=$eventId"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este evento? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo $eventDataRow["name"] ?><br/>
		<label>Tipo: </label><?php echo $eventDataRow["typeName"] ?><br/><br/>
		<input type="hidden" name="eventId" value="<?php echo $eventDataRow["id"] ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteEvent" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("events"); ?>';"/>
	</div>
	
</form>

<?php
}
?>