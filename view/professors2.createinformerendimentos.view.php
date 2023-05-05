
<?php if (isset($profObject)): ?>

<div class="viewDataFrame">
    <label>Docente: </label><?= hsc($profObject->name) . " (ID: {$profObject->id})"?>
</div>

<form enctype="multipart/form-data" action="<?= URL\URLGenerator::generateFileURL('post/professors2.createinformerendimentos.post.php', [ 'cont' => 'professors', 'action' => 'view', 'id' => $profObject->id ] ); ?>" method="post">
    <span class="formField">
        <label>Ano-calendário: <input type="number" step="1" name="professors_ir_attachs:numYear" required="required" value="<?= (int)date('Y') - 1 ?>"/></label>
    </span>
    <span class="formField">
        <label>Arquivo do Informe de Rendimentos: <input type="file" name="fileInformeRendimentosFile" required="required" accept="<?= implode(",", $allowedTypes) ?>" /></label>
    </span>
    <br/>
    <input type="hidden" name="professors_ir_attachs:hidProfId" value="<?= $profObject->id ?>" />
    <input type="submit" name="btnsubmitSubmitIr" value="Upload" />
</form>

<?php endif; ?>