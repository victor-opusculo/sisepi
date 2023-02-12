
<?php if (isset($legislatureObj)): ?>

<div class="viewDataFrame">
    <label>Legislatura: </label><?= hsc($legislatureObj->name) ?> <br/>
    <label>Data de início: </label><?= date_create($legislatureObj->begin)->format('d/m/Y') ?> <br/>
    <label>Data de fim: </label><?= date_create($legislatureObj->end)->format('d/m/Y') ?>
</div>

<?php $dgComp->render(); ?>

<?php endif; ?>