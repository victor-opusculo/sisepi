<?php if (isset($studentObj)): ?>
<div class="viewDataFrame">
    <div class="centControl">
        <?php if (!is_null($studentObj->photoFileExtension)): ?>
            <img src="<?= URL\URLGenerator::generateBaseDirFileURL("uploads/vereadormirim/students/{$studentObj->id}.{$studentObj->photoFileExtension}") ?>"
                    height="200"/>
        <?php else: ?>
            <span style="font-style: italic; font-size: xx-large;">Sem foto</span>
        <?php endif; ?>
    </div>
    <br/>

    <label>Ativo? </label><?= (bool)$studentObj->isActive ? 'Sim' : 'Não' ?><br/>
    <label>Nome: </label><?= hsc($studentObj->name) ?> <br/>
    <label>Partido: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirim', 'viewparty', $studentObj->partyId) ?>"><?= hsc($studentObj->getOtherProperties()->partyName) ?></a><br/>
    <label>Legislatura: </label><?= hsc($studentObj->getOtherProperties()->legislatureName) ?><br/>
    <label>Eleito? </label><?= (bool)$studentObj->isElected ? 'Sim' : 'Não' ?>
</div>
<?php endif; ?>