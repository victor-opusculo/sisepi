<?php if (isset($parentObj)): ?>
<?php 
function writeCheckedStatus($property, $expectedValue)
{
    return (string)$property === (string)$expectedValue ? ' checked ' : '';
}
?>
<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimparents.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ] ) ?>">

    <fieldset>
        <legend>Básico</legend>
        <span class="formField">
            <label>Nome completo: <input type="text" name="vmparents:txtName" maxlength="255" size="60" value="<?= hscq($parentObj->name) ?>" required/></label>
        </span>
        <span class="formField">
            <label>E-mail: <input type="email" name="vmparents:txtEmail" maxlength="255" size="60" value="<?= hscq($parentObj->email) ?>" required/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmparents:radSex" <?= writeCheckedStatus($parentObj->parentDataJson->sex, 'Feminino') ?> value="Feminino" required/>Feminino</label>
            <label><input type="radio" name="vmparents:radSex" <?= writeCheckedStatus($parentObj->parentDataJson->sex, 'Masculino') ?> value="Masculino" required/>Masculino</label>
            <label><input type="radio" name="vmparents:radSex" <?= writeCheckedStatus($parentObj->parentDataJson->sex, 'Indefinido') ?> value="Indefinido" required/>Outros/Indefinido</label>
        </span>
        <span class="formField">
            <label>Data de nascimento: <input type="date" name="vmparents:dateBirth" value="<?= hscq($parentObj->parentDataJson->birthDate) ?>" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Documentos</legend>
        <span class="formField">
            <label>RG: <input type="text" name="vmparents:txtRg" maxlength="100" size="30" value="<?= hscq($parentObj->parentDataJson->rg) ?>" required/></label> 
            <label>Órgão expedidor: <input type="text" name="vmparents:txtRgIssuingAgency" maxlength="100" size="5" value="<?= hscq($parentObj->parentDataJson->rgIssuingAgency) ?>" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Telefones</legend>
        <span class="formField">
            <label>Fixo: <input type="text" name="vmparents:txtLandline" maxlength="255" size="20" value="<?= hscq($parentObj->parentDataJson->phones->landline) ?>"/> (opcional)</label>
        </span>
        <span class="formField">
            <label>Celular: <input type="text" name="vmparents:txtCellphone" maxlength="255" size="20" value="<?= hscq($parentObj->parentDataJson->phones->cellphone) ?>" required/></label>
        </span>
    </fieldset>
    <div class="centControl">
        <input type="hidden" name="vmparents:parentId" value="<?= $parentObj->id ?>" />
        <input type="hidden" name="vmparents:hidRegistrationDate" value="<?= $parentObj->registrationDate ?>" />
        <input type="submit" name="btnsubmitSubmitParent" value="Editar responsável"/>
    </div>
</form>

<?php endif; ?>