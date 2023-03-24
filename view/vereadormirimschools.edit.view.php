<?php function writeCheckedStatus($property, $expectedValue)
{
    return (string)$property === (string)$expectedValue ? ' checked ' : '';
}
?>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimschools.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ] ) ?>">

    <fieldset>
        <legend>Básico</legend>
        <span class="formField">
            <label>Nome da escola: <input type="text" name="vmschools:txtSchoolName" maxlength="255" size="60" required value="<?= hscq($schoolObj->name) ?>"/></label>
        </span>
        <span class="formField">
            <label>Número de estudante votantes: <input type="number" min="0" step="1" name="vmschools:numVotingStudents" value="<?= hscq($schoolObj->numberOfVotingStudents) ?>"/></label>
        </span>
        
    </fieldset>
    <fieldset>
        <legend>Endereço da escola</legend>
        <span class="formField">
            <label>Logradouro: <input type="text" name="vmschools:txtAddressStreet" maxlength="255" size="40" value="<?= hscq($schoolObj->schoolDataJson->address->street ?? '') ?>"/></label>
            <label>Nº: <input type="text" name="vmschools:txtAddressNumber" maxlength="50" size="10" value="<?= hscq($schoolObj->schoolDataJson->address->number ?? '') ?>"/></label>
        </span>
        <span class="formField">
            <label>Bairro: <input type="text" name="vmschools:txtAddressNeighborhood" maxlength="255" size="40" value="<?= hscq($schoolObj->schoolDataJson->address->neighborhood ?? '') ?>"/></label>
        </span>
        <span class="formField">
            <label>CEP: <input type="text" name="vmschools:txtAddressCep" maxlength="140" size="20" value="<?= hscq($schoolObj->schoolDataJson->address->cep ?? '') ?>"/></label> 
            <label>Cidade: <input type="text" name="vmschools:txtAddressCity" maxlength="255" size="30" value="<?= hscq($schoolObj->schoolDataJson->address->city ?? '') ?>"/></label> 
            <label>Estado (UF): <input type="text" name="vmschools:txtAddressStateUf" maxlength="2" size="3" value="<?= hscq($schoolObj->schoolDataJson->address->stateUf ?? '') ?>"/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Contato</legend>
        <span class="formField">
            <label>E-mail corporativo: <input type="email" name="vmschools:txtSchoolEmail" maxlength="255" size="60" required value="<?= hscq($schoolObj->email) ?>"/></label>
        </span>
        <span class="formField">
            <label>Telefone: <input type="text" name="vmschools:txtSchoolTelephone" maxlength="100" size="30" value="<?= hscq($schoolObj->schoolDataJson->telephone ?? '') ?>"/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Diretor(a)</legend>
        <span class="formField">
            <label>Nome: <input type="text" name="vmschools:txtDirectorName" maxlength="255" size="60" value="<?= hscq($schoolObj->directorName) ?>"/></label>
            <label>Celular: <input type="text" name="vmschools:txtDirectorCellphone" maxlength="100" size="30" value="<?= hscq($schoolObj->directorDataJson->cellphone ?? '') ?>"/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Feminino" <?= writeCheckedStatus($schoolObj->directorDataJson->sex ?? '', 'Feminino') ?>/>Feminino</label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Masculino" <?= writeCheckedStatus($schoolObj->directorDataJson->sex ?? '', 'Masculino') ?>/>Masculino</label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Indefinido" <?= writeCheckedStatus($schoolObj->directorDataJson->sex ?? '', 'Indefinido') ?>/>Outros/Indefinido</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Coordenador(a) do programa na escola</legend>
        <span class="formField"> 
            <label>Nome: <input type="text" name="vmschools:txtCoordinatorName" maxlength="255" size="60" value="<?= hscq($schoolObj->coordinatorName) ?>"/></label>
            <label>Celular: <input type="text" name="vmschools:txtCoordinatorCellphone" maxlength="100" size="30" value="<?= hscq($schoolObj->coordinatorDataJson->cellphone ?? '') ?>"/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Feminino" <?= writeCheckedStatus($schoolObj->coordinatorDataJson->sex ?? '', 'Feminino') ?>/>Feminino</label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Masculino" <?= writeCheckedStatus($schoolObj->coordinatorDataJson->sex ?? '', 'Masculino') ?>/>Masculino</label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Indefinido" <?= writeCheckedStatus($schoolObj->coordinatorDataJson->sex ?? '', 'Indefinido') ?>/>Outros/Indefinido</label>
        </span>
    </fieldset>

    <div class="centControl">
        <input type="hidden" name="vmschools:hidRegistrationDate" value="<?= $schoolObj->registrationDate ?>"/>
        <input type="hidden" name="vmschools:schoolId" value="<?= $schoolObj->id ?>"/>
        <input type="submit" name="btnsubmitSubmitSchool" value="Editar escola"/>
    </div>
</form>