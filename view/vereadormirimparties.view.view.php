<?php if (!empty($partyObj)): ?>

<div class="centControl">
    <img src="<?= URL\URLGenerator::generateFileURL("uploads/vereadormirim/parties/{$partyObj->id}.{$partyObj->logoFileExtension}") ?>" height="256" />
</div>

<div class="viewDataFrame">
    <label>ID: </label><?= $partyObj->id ?><br/>
    <label>Nome: </label><?= hsc($partyObj->name) ?><br/>
    <label>Sigla: </label><?= hsc($partyObj->acronym) ?><br/>
    <label>Número: </label><?= $partyObj->number ?><br/>
    <label>Mais informações: </label><br/>
    <?= nl2br(hsc($partyObj->moreInfos)) ?><br/>
</div>

<div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimparties", "edit", $partyObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimparties", "delete", $partyObj->id); ?>">Excluir</a></li>
    </ul>
</div>

<?php endif; ?>