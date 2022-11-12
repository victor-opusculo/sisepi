<?php if (isset($traitObj)): ?>

<div class="centControl">
    <img src="<?php echo URL\URLGenerator::generateFileURL("uploads/traits/{$traitObj->id}.{$traitObj->fileExtension}"); ?>" width="128" />
</div>

<div class="viewDataFrame">
    <label>ID: </label><?= $traitObj->id ?> <br/>
    <label>Nome: </label><?= hsc($traitObj->name) ?> <br/>
    <label>Descrição: </label><?= nl2br(hsc($traitObj->description)) ?> <br/>
</div>

<div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("traits", "edit", $traitObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("traits", "delete", $traitObj->id); ?>">Excluir</a></li>
    </ul>
</div>

<?php endif; ?>