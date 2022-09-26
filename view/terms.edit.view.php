<?php if (isset($termObj)): ?>

<form enctype="multipart/form-data" method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/terms.edit.post.php', 'cont=terms&action=edit&id=' . $_GET['id']); ?>">
    <span class="formField">
        <label>Nome: <input type="text" name="terms:txtName" required="required" size="40" maxlength="250" value="<?php echo hscq($termObj->name); ?>"/></label>
    </span>
    <span class="formField">
        <label>Versão: <input type="number" name="terms:numVersion" required="required" min="1" step="1" value="<?php echo hscq($termObj->version); ?>"/></label>
    </span>
    <span class="formField">
        <label>Arquivo do termo (PDF) (Opcional): <input type="file" name="fileTermPdf" accept="application/pdf" /></label>
    </span>
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitTerm" value="Alterar" />
    </div>

    <input type="hidden" name="terms:registrationDate" value="<?= $termObj->registrationDate ?>" />
    <input type="hidden" name="terms:termId" value="<?= $termObj->id ?>" />
</form>

<?php endif; ?>