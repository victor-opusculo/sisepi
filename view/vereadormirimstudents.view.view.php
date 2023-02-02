<?php if (isset($vmStudentObj)): ?>

    <div class="centControl">
        <?php if (!empty($vmStudentObj->photoFileExtension)): ?>
            <img src="<?= URL\URLGenerator::generateFileURL("uploads/vereadormirim/students/{$vmStudentObj->id}.{$vmStudentObj->photoFileExtension}") ?>" height="200" />
        <?php else: ?>
            <p style="font-size:xx-large; font-style: italic;">Sem foto</p>
        <?php endif; ?>
    </div>

    <div class="viewDataFrame">
        <fieldset>
            <legend>Básico</legend>
            
            <label>Status: </label><?= (bool)$vmStudentObj->isActive ? ' Ativo ' : 'Desativado' ?> <br/>
            <label>Nome completo: </label><?= hsc($vmStudentObj->name) ?> <br/>
            <label>E-mail: </label><?= hsc($vmStudentObj->email) ?> <br/>
            <label>Sexo: </label><?= hsc($vmStudentObj->studentDataJson->sex) ?> <br/>
            <label>Data de nascimento: </label><?= hsc(date_create($vmStudentObj->studentDataJson->birthDate)->format('d/m/Y')) ?> <br/>
            <label>Ano escolar: </label><?= hsc($vmStudentObj->studentDataJson->schoolYear) ?> <br/>
            <label>Período: </label><?= hsc($vmStudentObj->studentDataJson->schoolPeriod) ?>
            
        </fieldset>
        <fieldset>
            <legend>Documentos</legend>
            <label>RG: </label><?= hsc($vmStudentObj->studentDataJson->rg) ?> <br/>
            <label>Órgão expedidor: </label><?= hsc($vmStudentObj->studentDataJson->rgIssuingAgency) ?>
        </fieldset>
        <fieldset>
            <legend>Telefones</legend>
            <label>Fixo: </label><?= hsc($vmStudentObj->studentDataJson->phones->landline) ?> <br/>
            <label>Celular: </label><?= hsc($vmStudentObj->studentDataJson->phones->cellphone) ?> <br/>
            <label>WhatsApp: </label><?= hsc($vmStudentObj->studentDataJson->phones->whatsapp) ?>
        </fieldset>
        <fieldset>
            <legend>Endereço residencial</legend>
            <label>Logradouro: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->street) ?> 
            <label>Nº: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->number) ?> <br/>
            <label>Complemento: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->complement) ?> <br/>
            <label>Bairro: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->neighborhood) ?> <br/>
            <label>CEP: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->cep) ?> <br/>
            <label>Cidade: </label><?= hsc($vmStudentObj->studentDataJson->homeAddress->city) ?>
            <label>/</label><?= hsc($vmStudentObj->studentDataJson->homeAddress->stateUf) ?> <br/>
        </fieldset>
        <fieldset>
            <legend>Pai/Responsável</legend>
            <label>Nome: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirimparents', 'view', $vmStudentObj->vmParentId) ?>"><?= hsc($vmStudentObj->getOtherProperties()->parentName) ?></a><br/>
            <label>Parentesco: </label><?= hsc($vmStudentObj->vmParentRelationship) ?>
        </fieldset>
        <fieldset>
            <legend>Outros</legend>
            <label>Atendimento especial requerido: </label><?= hsc($vmStudentObj->studentDataJson->accessibilityRequired) ?>
        </fieldset>
        <fieldset>
            <legend>Vereança Mirim</legend>
            <label>Partido filiado: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirimparties', 'view', $vmStudentObj->partyId) ?>"><?= hsc($vmStudentObj->getOtherProperties()->partyName) ?></a> <br/>
            <label>Legislatura: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirimlegislatures', 'view', $vmStudentObj->vmLegislatureId) ?>"><?= hsc($vmStudentObj->getOtherProperties()->legislatureName) ?></a>
        </fieldset>
        <label>Data de registro: </label><?= hsc(date_create($vmStudentObj->registrationDate)->format('d/m/Y')) ?> <br/>
    </div>
    <div>
        <fieldset>
            <legend>Termos e Documentos</legend>
            <?php if (!empty($vmDocumentTemplates)): ?>
                <span class="dropdownMenuButtonArea">
                    <button type="button">Adicionar</button>
                    <ul class="dropdownMenu">
                        <?php foreach ($vmDocumentTemplates as $templ): ?>
                        <li>
                            <a href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimstudents", "adddocument", $templ->id, [ 'vmStudentId' => $vmStudentObj->id ]); ?>"><?= $templ->name ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </span>
            <?php endif; ?>
        </fieldset>
    </div>

    <div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimstudents", "edit", $vmStudentObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimstudents", "delete", $vmStudentObj->id); ?>">Excluir</a></li>
    </ul>
</div>
<?php endif; ?>