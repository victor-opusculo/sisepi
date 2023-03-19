<?php if($profObject !== null) 
{
?>
<?php
function writeSelectedStatus($property, $valueToSearchFor)
{
    return (string)$property === (string)$valueToSearchFor ? ' checked="checked" ' : '';
}?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/editprofessor.post.php", "cont=professors&action=edit&id=$profObject->id"); ?>" method="post">
	<span class="formField"><label>ID: </label><?php echo $profObject->id; ?> <input type="hidden" name="profId" value="<?php echo $profObject->id; ?>" /></span>
	<span class="formField"><label>Data de registro: </label><?php echo date_format(date_create($profObject->registrationDate), "d/m/Y H:i:s"); ?></span>
	
	<fieldset>
        <legend>Informações básicas</legend>
        <span class="formField"><label>Nome completo: </label><input type="text" name="professors:txtName" size="60" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($profObject->name); ?>"/></span>
        <span class="formField"><label>E-mail: </label><input type="email" name="professors:txtEmail" size="60" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($profObject->email); ?>"/></span>
        <span class="formField"><label>Telefone (com prefixo): </label><input type="text" name="professors:txtTelephone" size="20" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($profObject->telephone); ?>"/></span>
        <span class="formField"><label>Escolaridade: </label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($profObject->schoolingLevel, "Sem titulação"); ?> value="Sem titulação" required="required"/>Sem titulação</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($profObject->schoolingLevel, "Superior"); ?>  value="Superior"/>Superior</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($profObject->schoolingLevel, "Especialização"); ?>  value="Especialização"/>Especialização</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($profObject->schoolingLevel, "Mestrado"); ?>  value="Mestrado"/>Mestrado</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($profObject->schoolingLevel, "Doutorado"); ?>  value="Doutorado"/>Doutorado</label>
            </span>
        <span class="formField"><label>Temas de interessse: </label><input type="text" name="professors:txtTopicsOfInterest" size="80" maxlength="300" value="<?php echo hscq($profObject->topicsOfInterest); ?>"/></span>
        <span class="formField"><label>Plataforma Lattes: </label><input type="text" name="professors:txtLattesLink" size="80" maxlength="120" value="<?php echo hscq($profObject->lattesLink); ?>"/></span>
        <span class="formField"><label>Recolhe INSS? </label>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, 1); ?> value="1" required="required" oninput="document.getElementById('inputPIS_PASEP').required = true;"/>Sim</label>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, 0); ?> value="0" required="required" oninput="document.getElementById('inputPIS_PASEP').required = false;"/>Não</label>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, null); ?> value="" required="required" oninput="document.getElementById('inputPIS_PASEP').required = false;"/>Indefinido</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Documentos pessoais</legend>
        <span class="formField"><label>RG: </label><input type="text" name="professors:txtRGNumber" size="30"  maxlength="50" value="<?php echo hscq($profObject->personalDocsJson->rg ?? ""); ?>"/>
        <label>Órgão emissor: </label><input type="text" name="professors:txtRGIssuingAgency" size="15" maxlength="30" value="<?php echo hscq($profObject->personalDocsJson->rgIssuingAgency ?? ""); ?>"/></span>
        <span class="formField"><label>CPF: </label><input type="text" name="professors:txtCPFNumber" size="30" maxlength="50" value="<?php echo hscq($profObject->personalDocsJson->cpf ?? ""); ?>"/></span>
        <span class="formField"><label>PIS/PASEP: </label><input id="inputPIS_PASEP" type="text" name="professors:txtPIS_PASEP" size="30" maxlength="50" <?php echo !empty($profObject->collectInss) ? ' required ' : ''; ?> value="<?php echo hscq($profObject->personalDocsJson->pis_pasep ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Endereço residencial</legend>
        <span class="formField"><label>Logradouro: </label><input type="text" name="professors:txtHomeAddressStreet" size="40" maxlength="150" value="<?php echo hscq($profObject->homeAddressJson->street ?? ""); ?>"/>
        <label>Nº: </label><input type="text" name="professors:txtHomeAddressNumber" size="10" maxlength="30" value="<?php echo hscq($profObject->homeAddressJson->number ?? ""); ?>"/>
        <label>Complemento: </label><input type="text" name="professors:txtHomeAddressComplement" size="15" maxlength="100" value="<?php echo hscq($profObject->homeAddressJson->complement ?? ""); ?>"/></span>
        <span class="formField"><label>Bairro: </label><input type="text" name="professors:txtHomeAddressNeighborhood" size="30" maxlength="150" value="<?php echo hscq($profObject->homeAddressJson->neighborhood ?? ""); ?>"/></span>
        <span class="formField"><label>Cidade: </label><input type="text" name="professors:txtHomeAddressCity" size="30" maxlength="150" value="<?php echo hscq($profObject->homeAddressJson->city ?? ""); ?>"/>
        <label>Estado (UF): </label><input type="text" name="professors:txtHomeAddressState" size="7" maxlength="2" value="<?php echo hscq($profObject->homeAddressJson->state ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Currículo resumido</legend>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Formação Educacional/Acadêmica</h4>
            <textarea name="professors:txtResumeEducation" maxlength="600" rows="5"><?php echo hsc($profObject->miniResumeJson->education ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Experiência Profissional</h4>
            <textarea name="professors:txtResumeExperience" maxlength="600" rows="5"><?php echo hsc($profObject->miniResumeJson->experience ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Informações Complementares</h4>
            <textarea name="professors:txtResumeAdditionalInformation" maxlength="600" rows="5"><?php echo hsc($profObject->miniResumeJson->additionalInformation ?? ""); ?></textarea>
        </span>
    </fieldset>
    <fieldset>
        <legend>Dados bancários</legend>
        <span class="formField"><label>Nome do banco: </label><input type="text" name="professors:txtBankDataBankName" size="40" maxlength="150" value="<?php echo hscq($profObject->bankDataJson->bankName ?? ""); ?>"/></span>
        <span class="formField"><label>Agência: </label><input type="text" name="professors:txtBankDataAgency" size="10" maxlength="50" value="<?php echo hscq($profObject->bankDataJson->agency ?? ""); ?>"/>
        <label>Conta: </label><input type="text" name="professors:txtBankDataAccount" size="30" maxlength="100" value="<?php echo hscq($profObject->bankDataJson->account ?? ""); ?>"/></span>
        <span class="formField"><label>Chave PIX: </label><input type="text" name="professors:txtBankDataPix" size="30" maxlength="150" value="<?php echo hscq($profObject->bankDataJson->pix ?? ""); ?>"/></span>
    </fieldset>

	<fieldset>
		<legend>LGPD</legend>
    	<span class="formField"><label>Termo de consentimento para tratamento de dados pessoais: </label><?php echo 'ID ' . hsc($profObject->consentForm); ?> </span>
		<span class="formField"><label>Concorda com o termo? </label><?php echo ($profObject->agreesWithConsentForm) ? "Concorda" : "Não concorda"; ?></span>
	</fieldset>

    <input type="hidden" name="professors:chkAgreesWithConsentForm" value="<?= $profObject->agreesWithConsentForm ?>" />
	<input type="hidden" name="professors:profId" value="<?php echo $profObject->id; ?>" />
	<input type="hidden" name="professors:hidConsentFormVersion" value="<?php echo $profObject->consentForm; ?>"/>
    <input type="hidden" name="professors:hidRegistrationDate" value="<?= hscq($profObject->registrationDate) ?>" />
	<br/>
    <div class="centControl"> 
	    <input type="submit" id="btnsubmitProfessorEditPersonalInfos" name="btnsubmitProfessorEditPersonalInfos" value="Alterar dados"/>
    </div>
</form>

<?php }
?>