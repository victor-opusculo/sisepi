<?php if (isset($traitObj)): ?>

<div class="centControl">
    <img src="<?php echo URL\URLGenerator::generateFileURL("uploads/traits/{$traitObj->id}.{$traitObj->fileExtension}"); ?>" width="128" />
</div>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/traits.delete.post.php', [ 'id' => $traitObj->id, 'title' => $this->subtitle ] ) ?>">
    <p style="text-align: center;">Deseja realmente excluir este traço? Esta operação é irreversível!</p>
    <div class="viewDataFrame">
        <label>ID: </label><?= $traitObj->id ?> <br/>
        <label>Nome: </label><?= hsc($traitObj->name) ?>
    </div>
    <div class="centControl">
        <input type="submit" name="btnsubmitDeleteTrait" value="Sim, excluir"/>
        <input type="button" value="Não excluir" onclick="history.back();"/>
    </div>
    <input type="hidden" name="traits:traitId" value="<?= $traitObj->id ?>" />
</form>

<?php endif; ?>