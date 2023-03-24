<?php if (isset($schoolObj)): ?>

<div class="viewDataFrame">
    <fieldset>
        <legend>Básico</legend>
        <label>ID: </label><?= $schoolObj->id ?> <br/>
        <label>Nome da escola: </label><?= hsc($schoolObj->name) ?> <br/>
        <label>Número de estudante votantes: </label><?= $schoolObj->numberOfVotingStudents ?> <br/>        
    </fieldset>
    <fieldset>
        <legend>Endereço da escola</legend>
        <label>Logradouro: </label><?= hsc($schoolObj->schoolDataJson->address->street ?? '') ?>
        <label>Nº: </label><?= hsc($schoolObj->schoolDataJson->address->number ?? '') ?> <br/>
        <label>Bairro: </label><?= hsc($schoolObj->schoolDataJson->address->neighborhood ?? '') ?> <br/>

        <label>CEP: </label><?= hsc($schoolObj->schoolDataJson->address->cep ?? '') ?>
        <label>Cidade: </label><?= hsc($schoolObj->schoolDataJson->address->city ?? '') ?>
        <label>Estado (UF): </label><?= hsc($schoolObj->schoolDataJson->address->stateUf ?? '') ?>
    </fieldset>
    <fieldset>
        <legend>Contato</legend>
        <label>E-mail corporativo: </label><?= hsc($schoolObj->email) ?> <br/>
        <label>Telefone: </label><?= hsc($schoolObj->schoolDataJson->telephone ?? '') ?>
    </fieldset>
    <fieldset>
        <legend>Diretor(a)</legend>
        <label>Nome: </label><?= hsc($schoolObj->directorName) ?> <br/>
        <label>Celular: </label><?= hsc($schoolObj->directorDataJson->cellphone ?? '') ?> <br/>
        <label>Sexo: </label><?= hsc($schoolObj->directorDataJson->sex ?? '') ?>
    </fieldset>
    <fieldset>
        <legend>Coordenador(a) do programa na escola</legend>
        <label>Nome: </label><?= hsc($schoolObj->coordinatorName) ?> <br/>
        <label>Celular: </label><?= hsc($schoolObj->coordinatorDataJson->cellphone ?? '') ?> <br/>
        <label>Sexo: </label><?= hsc($schoolObj->coordinatorDataJson->sex ?? '') ?>
    </fieldset>
    <label>Data de registro: </label><?= date_create($schoolObj->registrationDate)->format('d/m/Y H:i:s') ?>
</div>

<div class="editDeleteButtonsFrame">
    <ul>
        <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimschools", "edit", $schoolObj->id); ?>">Editar</a></li>
        <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimschools", "delete", $schoolObj->id); ?>">Excluir</a></li>
    </ul>
</div>

<?php endif; ?>