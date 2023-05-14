<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<?php if (isset($professorObj)): ?>

<style>
    h4 { margin: 0.3rem; }
    textarea { width: 100%; }
    #LGPDConsentFormQuestion
    {
        margin: 2rem auto 0 auto;
        max-width: 600px;
    }
</style>

<?php
function writeSelectedStatus($property, $valueToSearchFor)
{
    return (string)$property === (string)$valueToSearchFor ? ' checked="checked" ' : '';
}?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/professorpanelfunctions.editpersonalinfos.post.php", "cont=professorpanelfunctions&action=editpersonalinfos"); ?>" method="post">
	
    <fieldset>
        <legend>Informações básicas</legend>
        <span class="formField"><label>Nome completo: </label><input type="text" name="professors:txtName" size="60" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($professorObj->name); ?>"/></span>
        <span class="formField"><label>E-mail: </label><input type="email" name="professors:txtEmail" size="60" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($professorObj->email); ?>"/></span>
        <span class="formField"><label>Telefone (com prefixo): </label><input type="text" name="professors:txtTelephone" size="20" placeholder="Obrigatório" maxlength="120" required="required" value="<?php echo hscq($professorObj->telephone); ?>"/></span>
        <span class="formField"><label>Escolaridade: </label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($professorObj->schoolingLevel, "Sem titulação"); ?> value="Sem titulação" required="required"/>Sem titulação</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($professorObj->schoolingLevel, "Superior"); ?>  value="Superior"/>Superior</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($professorObj->schoolingLevel, "Especialização"); ?>  value="Especialização"/>Especialização</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($professorObj->schoolingLevel, "Mestrado"); ?>  value="Mestrado"/>Mestrado</label>
            <label><input type="radio" name="professors:radSchoolingLevel" <?php echo writeSelectedStatus($professorObj->schoolingLevel, "Doutorado"); ?>  value="Doutorado"/>Doutorado</label>
        </span>
        <span class="formField"><label>Etnia: </label>
            <?php if (isset($races)): foreach ($races as $race): ?>
                <label><input type="radio" name="professors:radRace" <?php echo writeSelectedStatus($professorObj->race, $race->value); ?>  value="<?= hscq($race->value) ?>" required /><?= hsc($race->value) ?></label>
            <?php endforeach; endif; ?>
        </span>
        <span class="formField"><label>Temas de interessse: </label><input type="text" name="professors:txtTopicsOfInterest" size="80" maxlength="300" placeholder="Opcional" value="<?php echo hscq($professorObj->topicsOfInterest); ?>"/></span>
        <span class="formField"><label>Plataforma Lattes: </label><input type="text" name="professors:txtLattesLink" size="80" maxlength="120" placeholder="Opcional" value="<?php echo hscq($professorObj->lattesLink); ?>"/></span>
        <span class="formField"><label>Você contribui com o INSS? </label><br/>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($professorObj->collectInss, 1); ?> value="1" required="required" 
                oninput="document.getElementById('inputPIS_PASEP').required = true;
                document.getElementById('inputPIS_PASEP').placeholder = 'Obrigatório';"/>Sim</label><br/>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($professorObj->collectInss, 0); ?> value="0" required="required" 
            oninput="document.getElementById('inputPIS_PASEP').required = false;
            document.getElementById('inputPIS_PASEP').placeholder = 'Opcional';"/>Não</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Documentos pessoais</legend>
        <span class="formField"><label>RG: </label><input type="text" name="professors:txtRGNumber" size="30" placeholder="Obrigatório" maxlength="50" required="required" value="<?php echo hscq($professorObj->personalDocsJson->rg ?? ""); ?>"/>
        <label>Órgão emissor: </label><input type="text" name="professors:txtRGIssuingAgency" size="15" placeholder="Obrigatório" maxlength="30" required="required" value="<?php echo hscq($professorObj->personalDocsJson->rgIssuingAgency ?? ""); ?>"/></span>
        <span class="formField"><label>CPF: </label><input type="text" name="professors:txtCPFNumber" size="30" placeholder="Obrigatório" maxlength="50" required="required" value="<?php echo hscq($professorObj->personalDocsJson->cpf ?? ""); ?>"/></span>
        <span class="formField"><label>PIS/PASEP: </label><input type="text" id="inputPIS_PASEP" name="professors:txtPIS_PASEP" size="30" placeholder="<?php echo !empty($professorObj->collectInss) ? 'Obrigatório' : 'Opcional'; ?>" maxlength="50" <?php echo !empty($professorObj->collectInss) ? ' required ' : ''; ?> value="<?php echo hscq($professorObj->personalDocsJson->pis_pasep ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Endereço residencial</legend>
        <span class="formField"><label>Logradouro: </label><input type="text" name="professors:txtHomeAddressStreet" size="40" placeholder="Obrigatório" maxlength="150" required="required" value="<?php echo hscq($professorObj->homeAddressJson->street ?? ""); ?>"/>
        <label>Nº: </label><input type="text" name="professors:txtHomeAddressNumber" size="10" placeholder="Obrigatório" maxlength="30" required="required" value="<?php echo hscq($professorObj->homeAddressJson->number ?? ""); ?>"/>
        <label>Complemento: </label><input type="text" name="professors:txtHomeAddressComplement" size="15" placeholder="Opcional" maxlength="100" value="<?php echo hscq($professorObj->homeAddressJson->complement ?? ""); ?>"/></span>
        <span class="formField"><label>Bairro: </label><input type="text" name="professors:txtHomeAddressNeighborhood" size="30" placeholder="Opcional" maxlength="150" value="<?php echo hscq($professorObj->homeAddressJson->neighborhood ?? ""); ?>"/></span>
        <span class="formField"><label>Cidade: </label><input type="text" name="professors:txtHomeAddressCity" size="30" placeholder="Obrigatório" maxlength="150" required="required" value="<?php echo hscq($professorObj->homeAddressJson->city ?? ""); ?>"/>
        <label>Estado (UF): </label><input type="text" name="professors:txtHomeAddressState" size="7" placeholder="Obrigatório" maxlength="2" required="required" value="<?php echo hscq($professorObj->homeAddressJson->state ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Currículo resumido</legend>
        <span class="messageFrameWithIcon"><img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/> Escreva resumidamente os pontos mais recentes ou mais relevantes do seu currículo. Este currículo resumido será anexado à solicitação de empenho para o seu pagamento. Limite de 600 caracteres para cada campo.</span>
        <span class="formField">
            <h4>Formação Educacional/Acadêmica</h4>
            <textarea name="professors:txtResumeEducation" maxlength="600" rows="5" required="required" placeholder="Obrigatório"><?php echo hsc($professorObj->miniResumeJson->education ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4>Experiência Profissional</h4>
            <textarea name="professors:txtResumeExperience" maxlength="600" rows="5" required="required" placeholder="Obrigatório"><?php echo hsc($professorObj->miniResumeJson->experience ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4>Informações Complementares</h4>
            <textarea name="professors:txtResumeAdditionalInformation" maxlength="600" rows="5" placeholder="Opcional"><?php echo hsc($professorObj->miniResumeJson->additionalInformation ?? ""); ?></textarea>
        </span>
    </fieldset>
    <fieldset>
        <legend>Dados bancários</legend>
        <span class="formField"><label>Nome do banco: </label><input type="text" name="professors:txtBankDataBankName" size="40" placeholder="Obrigatório" required="required" maxlength="150" value="<?php echo hscq($professorObj->bankDataJson->bankName ?? ""); ?>"/></span>
        <span class="formField"><label>Agência: </label><input type="text" name="professors:txtBankDataAgency" size="10" placeholder="Obrigatório" required="required" maxlength="50" value="<?php echo hscq($professorObj->bankDataJson->agency ?? ""); ?>"/>
        <label>Conta: </label><input type="text" name="professors:txtBankDataAccount" size="30" placeholder="Obrigatório" required="required" maxlength="100" value="<?php echo hscq($professorObj->bankDataJson->account ?? ""); ?>"/></span>
        <span class="formField"><label>Chave PIX: </label><input type="text" name="professors:txtBankDataPix" size="30" placeholder="Opcional" maxlength="150" value="<?php echo hscq($professorObj->bankDataJson->pix ?? ""); ?>"/></span>
    </fieldset>

    <div id="LGPDConsentFormQuestion"><label>Você concorda com o <a href="<?php echo URL\URLGenerator::generateBaseDirFileURL("uploads/terms/$consentFormTermId.pdf"); ?>">termo de consentimento para o tratamento dos seus dados pessoais</a>? (versão <?php echo $consentFormTermInfos['version'] ?? '(Termo não existente)'; ?>)</label>
		<label><input type="checkbox" name="professors:chkAgreesWithConsentForm" value="1" required="required"/>Concordo</label>
    </div>
	<input type="hidden" name="professors:hidConsentFormTermId" value="<?php echo $consentFormTermId; ?>"/>
	<br/>
    <div class="centControl">
	    <input type="submit" id="btnsubmitProfessorEditPersonalInfos" name="btnsubmitProfessorEditPersonalInfos" value="Alterar dados"/>
    </div>
</form>

<?php endif; ?>