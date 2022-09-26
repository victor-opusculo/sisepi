
<form enctype="multipart/form-data" method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/terms.create.post.php', [ 'title' => $this->subtitle ]); ?>">
    <span class="formField">
        <label>Nome: <input type="text" name="terms:txtName" required="required" size="40" maxlength="250" /></label>
    </span>
    <span class="formField">
        <label>Versão: <input type="number" name="terms:numVersion" required="required" min="1" step="1" /></label>
    </span>
    <span class="formField">
        <label>Arquivo do termo (PDF): <input type="file" name="fileTermPdf" required="required" accept="application/pdf" /></label>
    </span>
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitTerm" value="Enviar" />
    </div>
</form>