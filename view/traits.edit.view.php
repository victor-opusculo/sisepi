<?php if (isset($traitObj)): ?>

<div class="centControl">
    <img src="<?php echo URL\URLGenerator::generateFileURL("uploads/traits/{$traitObj->id}.{$traitObj->fileExtension}"); ?>" width="128" />
</div>

<form enctype="multipart/form-data" action="<?= URL\URLGenerator::generateFileURL('post/traits.edit.post.php', [ 'id' => $traitObj->id ] ); ?>" method="post">

    <span class="formField">
        <label>Nome: <input type="text" maxlength="255" size="40" name="traits:txtName" required="required" value="<?= hsc($traitObj->name) ?>"/></label>
    </span>
    <span class="formField">
        <label>Descrição: <textarea rows="5" name="traits:txtDescription"><?= hsc($traitObj->description) ?></textarea></label>
    </span>
    <span class="formField">
        <label>Novo ícone (opcional): <input type="file" name="fileTraitIconFile"/></label>
    </span>
    <br/>
    <input type="submit" name="btnsubmitSubmitTrait" value="Alterar dados" />
    <input type="hidden" name="traits:traitId" value="<?= $traitObj->id ?>"/>
    <input type="hidden" name="traits:hidFileExtension" value="<?= $traitObj->fileExtension ?>"/>
</form>

<?php endif; ?>