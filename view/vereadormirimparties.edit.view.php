<?php if (isset($partyObj)): ?>

<form enctype="multipart/form-data" method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimparties.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ] ) ?>">

    <span class="formField">
        <label>Nome deste partido: <input type="text" maxlength="255" size="40" name="vmparties:txtName" value="<?= hscq($partyObj->name) ?>" required/></label>
    </span>
    <span class="formField">
        <label>Sigla: <input type="text" name="vmparties:txtAcronym" maxlength="50" value="<?= hscq($partyObj->acronym) ?>" required/></label>
    </span>  
    <span class="formField">
        <label>Número: <input type="number" min="1" step="1" name="vmparties:numPartyNumber" value="<?= hscq($partyObj->number) ?>" required/></label>
    </span>  
    <span class="formField">
        <label>Mais informações: 
            <textarea style="width:100%;" name="vmparties:txtMoreInfos" rows="5"><?= $partyObj->moreInfos ?></textarea>
        </label>
    </span>  
    <span class="formField">
        <label>Alterar logotipo:
            <input type="file" name="filePartyLogo"/> (Máximo de 1MB)
        </label>
    </span>

    <input type="hidden" name="vmparties:hidLogoFileExtension" value="<?= hscq($partyObj->logoFileExtension) ?>" />
    <input type="hidden" name="vmparties:partyId" value="<?= $partyObj->id ?>" />
    <input type="submit" name="btnsubmitEditParty" value="Editar partido" />
</form>

<?php endif; ?>