<?php if (isset($vmStudentObj, $vmDocumentObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimstudents.deletedocument.post.php", [ 'cont' => $_GET['cont'], 'action' => 'view', 'id' => $vmStudentObj->id ]); ?>" method="post">

	<p style="text-align: center;">Deseja realmente excluir este documento de vereador mirim? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $vmDocumentObj->id ?><br/>
		<label>Modelo: </label><?php echo hsc($vmDocumentObj->getOtherProperties()->templateName); ?><br/>
		<label>Vereador Mirim: </label><?php echo hsc($vmStudentObj->name) ?><br/>
		<input type="hidden" name="vmdocuments:docId" value="<?php echo $vmDocumentObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteVmDocument" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>