
<form enctype="multipart/form-data" method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimparties.create.post.php', [ 'title' => $this->subtitle ] ) ?>">

    <span class="formField">
        <label>Nome deste partido: <input type="text" maxlength="255" size="40" name="vmparties:txtName" required/></label>
    </span>
    <span class="formField">
        <label>Sigla: <input type="text" name="vmparties:txtAcronym" maxlength="50" required/></label>
    </span>  
    <span class="formField">
        <label>Número: <input type="number" min="1" step="1" name="vmparties:numPartyNumber" required/></label>
    </span>  
    <span class="formField">
        <label>Mais informações: 
            <textarea style="width:100%;" name="vmparties:txtMoreInfos" rows="5"></textarea>
        </label>
    </span>  
    <span class="formField">
        <label>Logotipo:
            <input type="file" name="filePartyLogo" required/> (Máximo de 1MB)
        </label>
    </span>

    <input type="submit" name="btnsubmitCreateParty" value="Criar partido" />
</form>