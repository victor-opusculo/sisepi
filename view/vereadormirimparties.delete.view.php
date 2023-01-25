<?php if (!empty($partyObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimparties.delete.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">

	<div class="centControl">
		<img src="<?= URL\URLGenerator::generateFileURL("uploads/vereadormirim/parties/{$partyObj->id}.{$partyObj->logoFileExtension}") ?>" height="256" />
	</div>

	<p style="text-align: center;">Deseja realmente excluir este partido? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $partyObj->id ?><br/>
		<label>Nome: </label><?php echo hsc($partyObj->name); ?><br/>
		<label>Sigla: </label><?php echo hsc($partyObj->acronym) ?><br/>
		<input type="hidden" name="vmparties:partyId" value="<?php echo $partyObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteParty" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>