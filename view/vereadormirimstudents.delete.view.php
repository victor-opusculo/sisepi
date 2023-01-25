<?php if (!empty($vmStudentObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimstudents.delete.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">

    <?php if (!empty($vmStudentObj->photoFileExtension)): ?>
	<div class="centControl">
		<img src="<?= URL\URLGenerator::generateFileURL("uploads/vereadormirim/students/{$vmStudentObj->id}.{$vmStudentObj->photoFileExtension}") ?>" height="200" />
	</div>
    <?php endif; ?>

	<p style="text-align: center;">Deseja realmente excluir este vereador mirim? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $vmStudentObj->id ?><br/>
		<label>Nome: </label><?php echo hsc($vmStudentObj->name); ?><br/>
		<label>E-mail: </label><?php echo hsc($vmStudentObj->email) ?><br/>
		<label>Partido: </label><?php echo hsc($vmStudentObj->getOtherProperties()->partyName) ?><br/>
		<label>Legislatura: </label><?php echo hsc($vmStudentObj->getOtherProperties()->legislatureName) ?><br/>
		<input type="hidden" name="vmstudents:studentId" value="<?php echo $vmStudentObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteVmStudent" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>