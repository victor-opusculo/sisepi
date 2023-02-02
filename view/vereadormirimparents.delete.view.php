<?php if (!empty($parentObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimparents.delete.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">

	<p style="text-align: center;">Deseja realmente excluir este pai/responsável de vereador mirim? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $parentObj->id ?><br/>
		<label>Nome: </label><?php echo hsc($parentObj->name); ?><br/>
		<label>E-mail: </label><?php echo hsc($parentObj->email) ?><br/>
		<label>RG: </label><?php echo hsc($parentObj->parentDataJson->rg) ?>
		<input type="hidden" name="vmparents:parentId" value="<?php echo $parentObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteVmParent" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>