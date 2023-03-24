<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimschools.create.post.php', [ 'cont' => $_GET['cont'], 'action' => 'home' ] ) ?>">

    <fieldset>
        <legend>Básico</legend>
        <span class="formField">
            <label>Nome da escola: <input type="text" name="vmschools:txtSchoolName" maxlength="255" size="60" required/></label>
        </span>
        <span class="formField">
            <label>Número de estudante votantes: <input type="number" min="0" step="1" name="vmschools:numVotingStudents" /></label>
        </span>
        

        
    </fieldset>
    <fieldset>
        <legend>Endereço da escola</legend>
        <span class="formField">
            <label>Logradouro: <input type="text" name="vmschools:txtAddressStreet" maxlength="255" size="40"/></label>
            <label>Nº: <input type="text" name="vmschools:txtAddressNumber" maxlength="50" size="10"/></label>
        </span>
        <span class="formField">
            <label>Bairro: <input type="text" name="vmschools:txtAddressNeighborhood" maxlength="255" size="40"/></label>
        </span>
        <span class="formField">
            <label>CEP: <input type="text" name="vmschools:txtAddressCep" maxlength="140" size="20"/></label> 
            <label>Cidade: <input type="text" name="vmschools:txtAddressCity" maxlength="255" size="30" /></label> 
            <label>Estado (UF): <input type="text" name="vmschools:txtAddressStateUf" maxlength="2" size="3" /></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Contato</legend>
        <span class="formField">
            <label>E-mail corporativo: <input type="email" name="vmschools:txtSchoolEmail" maxlength="255" size="60" required/></label>
        </span>
        <span class="formField">
            <label>Telefone: <input type="text" name="vmschools:txtSchoolTelephone" maxlength="100" size="30"/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Diretor(a)</legend>
        <span class="formField">
            <label>Nome: <input type="text" name="vmschools:txtDirectorName" maxlength="255" size="60"/></label>
            <label>Celular: <input type="text" name="vmschools:txtDirectorCellphone" maxlength="100" size="30"/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Feminino"/>Feminino</label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Masculino"/>Masculino</label>
            <label><input type="radio" name="vmschools:radDirectorSex" value="Indefinido"/>Outros/Indefinido</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Coordenador(a) do programa na escola</legend>
        <span class="formField">
            <label>Nome: <input type="text" name="vmschools:txtCoordinatorName" maxlength="255" size="60"/></label>
            <label>Celular: <input type="text" name="vmschools:txtCoordinatorCellphone" maxlength="100" size="30"/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Feminino"/>Feminino</label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Masculino"/>Masculino</label>
            <label><input type="radio" name="vmschools:radCoordinatorSex" value="Indefinido"/>Outros/Indefinido</label>
        </span>
    </fieldset>

    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitSchool" value="Criar escola"/>
    </div>
</form>