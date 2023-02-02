
<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimparents.create.post.php', [ 'title' => $this->subtitle ] ) ?>">

    <fieldset>
        <legend>Básico</legend>
        <span class="formField">
            <label>Nome completo: <input type="text" name="vmparents:txtName" maxlength="255" size="60" required/></label>
        </span>
        <span class="formField">
            <label>E-mail: <input type="email" name="vmparents:txtEmail" maxlength="255" size="60" required/></label>
        </span>
        <span class="formField">
            <label>Sexo: </label>
            <label><input type="radio" name="vmparents:radSex" value="Feminino" required/>Feminino</label>
            <label><input type="radio" name="vmparents:radSex" value="Masculino" required/>Masculino</label>
            <label><input type="radio" name="vmparents:radSex" value="Indefinido" required/>Outros/Indefinido</label>
        </span>
        <span class="formField">
            <label>Data de nascimento: <input type="date" name="vmparents:dateBirth" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Documentos</legend>
        <span class="formField">
            <label>RG: <input type="text" name="vmparents:txtRg" maxlength="100" size="30" required/></label> 
            <label>Órgão expedidor: <input type="text" name="vmparents:txtRgIssuingAgency" maxlength="100" size="5" required/></label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Telefones</legend>
        <span class="formField">
            <label>Fixo: <input type="text" name="vmparents:txtLandline" maxlength="255" size="20"/> (opcional)</label>
        </span>
        <span class="formField">
            <label>Celular: <input type="text" name="vmparents:txtCellphone" maxlength="255" size="20" required/></label>
        </span>
    </fieldset>
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitParent" value="Criar responsável"/>
    </div>
</form>