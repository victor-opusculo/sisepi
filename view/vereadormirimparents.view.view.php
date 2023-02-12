<?php if (isset($parentObj)): ?>

    <div class="viewDataFrame">
        <fieldset>
            <legend>Básico</legend>
            
            <label>Nome completo: </label><?= hsc($parentObj->name) ?> <br/>
            <label>E-mail: </label><?= hsc($parentObj->email) ?> <br/>
            <label>Sexo: </label><?= hsc($parentObj->parentDataJson->sex) ?> <br/>
            <label>Data de nascimento: </label><?= hsc(date_create($parentObj->parentDataJson->birthDate)->format('d/m/Y')) ?> <br/>
            
        </fieldset>
        <fieldset>
            <legend>Documentos</legend>
            <label>RG: </label><?= hsc($parentObj->parentDataJson->rg) ?> <br/>
            <label>Órgão expedidor: </label><?= hsc($parentObj->parentDataJson->rgIssuingAgency) ?>
        </fieldset>
        <fieldset>
            <legend>Telefones</legend>
            <label>Fixo: </label><?= hsc($parentObj->parentDataJson->phones->landline) ?> <br/>
            <label>Celular: </label><?= hsc($parentObj->parentDataJson->phones->cellphone) ?>
        </fieldset>

        <label>Data de registro: </label><?= hsc(date_create($parentObj->registrationDate)->format('d/m/Y')) ?>
    </div>

    <div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimparents", "edit", $parentObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimparents", "delete", $parentObj->id); ?>">Excluir</a></li>
    </ul>
</div>
<?php endif; ?>