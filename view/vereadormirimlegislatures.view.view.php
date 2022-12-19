<?php if (!empty($legislatureObj)): ?>

<div class="viewDataFrame">
    <label>ID: </label><?= $legislatureObj->id ?><br/>
    <label>Nome: </label><?= hsc($legislatureObj->name) ?><br/>
    <label>Início: </label><?= date_create($legislatureObj->begin)->format('d/m/Y') ?><br/>
    <label>Fim: </label><?= date_create($legislatureObj->end)->format('d/m/Y') ?><br/>
</div>

<div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimlegislatures", "edit", $legislatureObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimlegislatures", "delete", $legislatureObj->id); ?>">Excluir</a></li>
    </ul>
</div>

<?php endif; ?>