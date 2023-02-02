<?php
    function writeSelectedStatus($property, $expectedValue)
    {
        return (string)$property === (string)$expectedValue ? ' selected ' : ''; 
    }

    function writeCheckedStatus($property, $expectedValue)
    {
        return (string)$property === (string)$expectedValue ? ' checked ' : ''; 
    }
?>
<script>
    const getLegislatureInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getVmLegislatureInfos.php'); ?>';
    const getParentInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getVmParentInfos.php'); ?>';
    const popupURL = '<?php echo URL\URLGenerator::generatePopupURL("{popup}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/vereadormirimstudents.edit.view.js'); ?>"></script>

<form enctype="multipart/form-data" method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimstudents.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ] ) ?>">

    <fieldset>
        <legend>Básico</legend>
        <span class="formField">
            <label><input type="checkbox" name="vmstudents:chkIsActive" value="1" <?= writeCheckedStatus($vmStudentObj->isActive, 1) ?>/> Cadastro ativo</label>
        </span>
        <span class="formField">
            <label>Foto: <input type="file" name="fileVmStudentPhoto"/> </label> (Opcional. Máximo de 1MB)
            <label><input type="checkbox" name="chkRemovePhoto" value="1"/> Remover foto atual</label>
        </span>
        <span class="formField">
            <label>Nome completo: <input type="text" name="vmstudents:txtName" maxlength="255" size="60" value="<?= hscq($vmStudentObj->name) ?>" required/></label>
        </span>
        <span class="formField">
            <label>E-mail: <input type="email" name="vmstudents:txtEmail" maxlength="255" size="60" value="<?= hscq($vmStudentObj->email) ?>" required/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmstudents:radSex" value="Feminino" <?= writeCheckedStatus($vmStudentObj->studentDataJson->sex, 'Feminino') ?> required/>Feminino</label>
            <label><input type="radio" name="vmstudents:radSex" value="Masculino" <?= writeCheckedStatus($vmStudentObj->studentDataJson->sex, 'Masculino') ?> required/>Masculino</label>
            <label><input type="radio" name="vmstudents:radSex" value="Indefinido" <?= writeCheckedStatus($vmStudentObj->studentDataJson->sex, 'Indefinido') ?> required/>Outros/Indefinido</label>
        </span>
        <span class="formField">
            <label>Data de nascimento: <input type="date" name="vmstudents:dateBirth" value="<?= hscq($vmStudentObj->studentDataJson->birthDate) ?>" required/></label>
        </span>
        <span class="formField">
            <label>Ano escolar: <input type="text" name="vmstudents:txtSchoolYear" maxlength="255" size="40" value="<?= hscq($vmStudentObj->studentDataJson->schoolYear) ?>" required/></label>
        </span>
        <span class="formField">
            <label>Período: </label>
            <label><input type="radio" name="vmstudents:radSchoolPeriod" value="Manhã" <?= writeCheckedStatus($vmStudentObj->studentDataJson->schoolPeriod, 'Manhã') ?> required/>Manhã</label>
            <label><input type="radio" name="vmstudents:radSchoolPeriod" value="Tarde" <?= writeCheckedStatus($vmStudentObj->studentDataJson->schoolPeriod, 'Tarde') ?> required/>Tarde</label>
            <label><input type="radio" name="vmstudents:radSchoolPeriod" value="Noite" <?= writeCheckedStatus($vmStudentObj->studentDataJson->schoolPeriod, 'Noite') ?> required/>Noite</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Documentos</legend>
        <span class="formField">
            <label>RG: <input type="text" name="vmstudents:txtRg" maxlength="100" size="30" value="<?= hscq($vmStudentObj->studentDataJson->rg) ?>" required/></label> 
            <label>Órgão expedidor: <input type="text" name="vmstudents:txtRgIssuingAgency" maxlength="100" size="5" value="<?= hscq($vmStudentObj->studentDataJson->rgIssuingAgency) ?>" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Telefones</legend>
        <span class="formField">
            <label>Fixo: <input type="text" name="vmstudents:txtLandline" maxlength="255" size="20" value="<?= hscq($vmStudentObj->studentDataJson->phones->landline) ?>" /> (opcional)</label>
        </span>
        <span class="formField">
            <label>Celular: <input type="text" name="vmstudents:txtCellphone" maxlength="255" size="20" value="<?= hscq($vmStudentObj->studentDataJson->phones->cellphone) ?>" required/></label>
        </span>
        <span class="formField">
            <label>WhatsApp: <input type="text" name="vmstudents:txtWhatsapp" maxlength="255" size="20" value="<?= hscq($vmStudentObj->studentDataJson->phones->whatsapp) ?>" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Endereço residencial</legend>
        <span class="formField">
            <label>Logradouro: <input type="text" name="vmstudents:txtAddrStreet" maxlength="255" size="40" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->street) ?>" required/></label>
            <label>Nº: <input type="text" name="vmstudents:txtAddrNumber" maxlength="50" size="10" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->number) ?>"/></label>
        </span>
        <span class="formField">
            <label>Complemento: <input type="text" name="vmstudents:txtAddrComplement" maxlength="140" size="30" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->complement) ?>"/></label>
            <label>Bairro: <input type="text" name="vmstudents:txtAddrNeighborhood" maxlength="255" size="30" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->neighborhood) ?>" required/></label>
        </span>
        <span class="formField">
            <label>CEP: <input type="text" name="vmstudents:txtCep" maxlength="140" size="20" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->cep) ?>" required/></label> 
            <label>Cidade: <input type="text" name="vmstudents:txtCity" maxlength="255" size="30" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->city) ?>" required/></label> 
            <label>Estado (UF): <input type="text" name="vmstudents:txtStateUf" maxlength="2" size="3" value="<?= hscq($vmStudentObj->studentDataJson->homeAddress->stateUf) ?>" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Pai/Responsável</legend>
        <span class="searchFormField">
            <label>Responsável ID: <input type="number" id="inputParentId" name="vmstudents:numParentId" min="1" step="1" value="<?= $vmStudentObj->vmParentId ?>"/></label>
            <button type="button" id="btnLoadParent" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
		    <button type="button" id="btnSearchParent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <div class="viewDataFrame">
            <span id="spanParentName"></span>
        </div>
        <span class="searchFormField">
            <label>Parentesco: <input type="text" name="vmstudents:txtParentRelationship" list="parentRelations" size="50" maxlength="255" value="<?= hscq($vmStudentObj->vmParentRelationship) ?>" /></label>
            <datalist id="parentRelations">
                <option value="Pai">Pai</option>
                <option value="Mãe">Mãe</option>
                <option value="Avô">Avô</option>
                <option value="Avó">Avó</option>
                <option value="Tio">Tio</option>
                <option value="Tia">Tia</option>
                <option value="Madrasta">Madrasta</option>
                <option value="Padrasto">Padrasto</option>
            </datalist>
        </span>
    </fieldset>
    <fieldset>
        <legend>Outros</legend>
        <span class="formField">
            <label>Atendimento especial requerido: <input type="text" name="vmstudents:txtAccesibilityRequired" maxlength="255" size="40" value="<?= hscq($vmStudentObj->studentDataJson->accessibilityRequired) ?>"/> (opcional)</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Vereança Mirim</legend>
        <span class="formField">
            <label>Partido:
                <select name="vmstudents:selParty" required>
                    <?php foreach ($partiesList as $p): ?>
                        <option value="<?= $p->id ?>" <?= writeSelectedStatus($p->id, $vmStudentObj->partyId) ?> ><?= hsc($p->name) . ' (' . hsc($p->number) . ')' ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </span>
        <span class="searchFormField">
            <label>Legislatura ID: <input type="number" id="inputLegislatureId" name="vmstudents:numLegislatureId" min="1" step="1" value="<?= $vmStudentObj->vmLegislatureId ?>" required/></label>
            <button type="button" id="btnLoadLegislature" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
		    <button type="button" id="btnSearchLegislature"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <div class="viewDataFrame">
            <label>Nome: </label><span id="spanLegislatureName"></span>
        </div>
    </fieldset>
    <div class="centControl">
        <input type="hidden" name="vmstudents:hidPhotoFileExtension" value="<?= $vmStudentObj->photoFileExtension ?>"/>
        <input type="hidden" name="vmstudents:hidRegistrationDate" value="<?= $vmStudentObj->registrationDate ?>" />
        <input type="hidden" name="vmstudents:studentId" value="<?= $vmStudentObj->id ?>" />
        <input type="submit" name="btnsubmitSubmitStudent" value="Editar vereador mirim"/>
    </div>
</form>