<?php if (isset($partyObj)): ?>
<div class="viewDataFrame">
    <div class="centControl">
        <?php if (!is_null($partyObj->logoFileExtension)): ?>
            <img src="<?= URL\URLGenerator::generateBaseDirFileURL("uploads/vereadormirim/parties/{$partyObj->id}.{$partyObj->logoFileExtension}") ?>"
                    height="200"/>
        <?php else: ?>
            <span style="font-style: italic; font-size: xx-large;">Sem logo</span>
        <?php endif; ?>
    </div>
    <br/>

    <label>Nome: </label><?= hsc($partyObj->name) ?> <br/>
    <label>Sigla: </label><?= hsc($partyObj->acronym) ?><br/>
    <label>Número: </label><?= hsc($partyObj->number) ?><br/>
    <label>Mais informações: </label><br/>
    <?= nl2br(hsc($partyObj->moreInfos)) ?>
</div>
<?php endif; ?>