<?php
if (isset($workSheetObject))
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/professors2.deleteworksheet.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta ficha de trabalho de docente? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Atividade: </label><?php echo hsc($workSheetObject->participationEventDataJson->activityName); ?><br/>
		<label>Datas: </label><?php echo nl2br(hsc($workSheetObject->participationEventDataJson->dates)); ?><br/>
		<label>Horário: </label><?php echo hsc($workSheetObject->participationEventDataJson->times); ?><br/>
		<label>Carga horária: </label><?php echo hsc($workSheetObject->participationEventDataJson->workTime); ?><br/>
		<input type="hidden" name="workSheetId" value="<?php echo $workSheetObject->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteWorkSheet" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php
}
?>