
<form enctype="multipart/form-data" action="<?= URL\URLGenerator::generateFileURL('post/traits.create.post.php', [ 'title' => $this->subtitle ] ); ?>" method="post">

    <span class="formField">
        <label>Nome: <input type="text" maxlength="255" size="40" name="traits:txtName" required="required" /></label>
    </span>
    <span class="formField">
        <label>Descrição: <textarea rows="5" name="traits:txtDescription"></textarea></label>
    </span>
    <span class="formField">
        <label>Arquivo do ícone: <input type="file" name="fileTraitIconFile" required="required" /></label>
    </span>
    <br/>
    <input type="submit" name="btnsubmitSubmitTrait" value="Criar" />
</form>