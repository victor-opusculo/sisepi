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
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, 1); ?> value="1" required="required" oninput="document.getElementById('inputPIS_PASEP').required = true;"/>Sim. E autoriza desconto de 11%.</label>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, 0); ?> value="0" required="required" oninput="document.getElementById('inputPIS_PASEP').required = false;"/>Não</label>
            <label><input type="radio" name="professors:radCollectInss" <?php echo writeSelectedStatus($profObject->collectInss, null); ?> value="" required="required" oninput="document.getElementById('inputPIS_PASEP').required = false;"/>Indefinido</label>
        </span>
    </fieldset>
    <fieldset>
        <legend>Declaração INSS</legend>
        <?php 
        $inssPeriodBegin = $professorObj->inssCollectInfos->periodBegin ?? date("Y-01-01");
        $inssPeriodEnd = $professorObj->inssCollectInfos->periodEnd ?? date("Y-12-31");
        ?>
        <label>Período: <input type="date" name="professors:dateInssPeriodBegin" required="required" value="<?php echo $inssPeriodBegin; ?>" /></label>
        <label> a <input type="date" name="professors:dateInssPeriodEnd" required="required" value="<?php echo $inssPeriodEnd; ?>"/></label>

        <table>
            <thead>
                <tr>
                    <th>Empresa</th><th>CNPJ</th><th>Remuneração</th><th>INSS retido</th><th>Categoria trabalhador</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <?php $icCount = !empty($profObject->inssCollectInfos->companies) ? count($profObject->inssCollectInfos->companies) : 4;
                for ($i = 0; $i < $icCount; $i++): 
                    $icName = $profObject->inssCollectInfos->companies[$i]->name ?? '';
                    $icCnpj = $profObject->inssCollectInfos->companies[$i]->cnpj ?? '';
                    $icWage = $profObject->inssCollectInfos->companies[$i]->wage ?? '';
                    $icCollectedInss = $profObject->inssCollectInfos->companies[$i]->collectedInss ?? '';
                    $icWorkerCategory = $profObject->inssCollectInfos->companies[$i]->workerCategory ?? '';
                    ?>
                    <tr>
                        <td><input type="text" name="professors:inssCompanies[<?php echo $i; ?>][name]" maxlength="120" size="30" value="<?php echo hscq($icName); ?>"/></td>
                        <td><input type="text" name="professors:inssCompanies[<?php echo $i; ?>][cnpj]" maxlength="120" size="15" value="<?php echo hscq($icCnpj); ?>"/></td>
                        <td><input type="number" name="professors:inssCompanies[<?php echo $i; ?>][wage]" min="0" step="any" maxlength="120" style="width:150px;" value="<?php echo hscq($icWage); ?>"/></td>
                        <td><input type="number" name="professors:inssCompanies[<?php echo $i; ?>][collectedInss]" step="any" maxlength="120" style="width:150px;" value="<?php echo hscq($icCollectedInss); ?>"/></td>
                        <td><input type="text" name="professors:inssCompanies[<?php echo $i; ?>][workerCategory]" maxlength="120" size="10" value="<?php echo hscq($icWorkerCategory); ?>"/></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend>Documentos pessoais</legend>
        <span class="formField"><label>RG: </label><input type="text" name="professors:txtRGNumber" size="30"  maxlength="50" value="<?php echo hscq($profObject->personalDocs->rg ?? ""); ?>"/>
        <label>Órgão emissor: </label><input type="text" name="professors:txtRGIssuingAgency" size="15" maxlength="30" value="<?php echo hscq($profObject->personalDocs->rgIssuingAgency ?? ""); ?>"/></span>
        <span class="formField"><label>CPF: </label><input type="text" name="professors:txtCPFNumber" size="30" maxlength="50" value="<?php echo hscq($profObject->personalDocs->cpf ?? ""); ?>"/></span>
        <span class="formField"><label>PIS/PASEP: </label><input id="inputPIS_PASEP" type="text" name="professors:txtPIS_PASEP" size="30" maxlength="50" <?php echo !empty($profObject->collectInss) ? ' required ' : ''; ?> value="<?php echo hscq($profObject->personalDocs->pis_pasep ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Endereço residencial</legend>
        <span class="formField"><label>Logradouro: </label><input type="text" name="professors:txtHomeAddressStreet" size="40" maxlength="150" value="<?php echo hscq($profObject->homeAddress->street ?? ""); ?>"/>
        <label>Nº: </label><input type="text" name="professors:txtHomeAddressNumber" size="10" maxlength="30" value="<?php echo hscq($profObject->homeAddress->number ?? ""); ?>"/>
        <label>Complemento: </label><input type="text" name="professors:txtHomeAddressComplement" size="15" maxlength="100" value="<?php echo hscq($profObject->homeAddress->complement ?? ""); ?>"/></span>
        <span class="formField"><label>Bairro: </label><input type="text" name="professors:txtHomeAddressNeighborhood" size="30" maxlength="150" value="<?php echo hscq($profObject->homeAddress->neighborhood ?? ""); ?>"/></span>
        <span class="formField"><label>Cidade: </label><input type="text" name="professors:txtHomeAddressCity" size="30" maxlength="150" value="<?php echo hscq($profObject->homeAddress->city ?? ""); ?>"/>
        <label>Estado (UF): </label><input type="text" name="professors:txtHomeAddressState" size="7" maxlength="2" value="<?php echo hscq($profObject->homeAddress->state ?? ""); ?>"/></span>
    </fieldset>
    <fieldset>
        <legend>Currículo resumido</legend>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Formação Educacional/Acadêmica</h4>
            <textarea name="professors:txtResumeEducation" maxlength="600" rows="5"><?php echo hsc($profObject->miniResume->education ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Experiência Profissional</h4>
            <textarea name="professors:txtResumeExperience" maxlength="600" rows="5"><?php echo hsc($profObject->miniResume->experience ?? ""); ?></textarea>
        </span>
        <span class="formField">
            <h4 style="margin: 0.3rem;">Informações Complementares</h4>
            <textarea name="professors:txtResumeAdditionalInformation" maxlength="600" rows="5"><?php echo hsc($profObject->miniResume->additionalInformation ?? ""); ?></textarea>
        </span>
    </fieldset>
    <fieldset>
        <legend>Dados bancários</legend>
        <span class="formField"><label>Nome do banco: </label><input type="text" name="professors:txtBankDataBankName" size="40" maxlength="150" value="<?php echo hscq($profObject->bankData->bankName ?? ""); ?>"/></span>
        <span class="formField"><label>Agência: </label><input type="text" name="professors:txtBankDataAgency" size="10" maxlength="50" value="<?php echo hscq($profObject->bankData->agency ?? ""); ?>"/>
        <label>Conta: </label><input type="text" name="professors:txtBankDataAccount" size="30" maxlength="100" value="<?php echo hscq($profObject->bankData->account ?? ""); ?>"/></span>
        <span class="formField"><label>Chave PIX: </label><input type="text" name="professors:txtBankDataPix" size="30" maxlength="150" value="<?php echo hscq($profObject->bankData->pix ?? ""); ?>"/></span>
    </fieldset>

	<fieldset>
		<legend>LGPD</legend>
    	<span class="formField"><label>Versão do termo de consentimento para tratamento de dados pessoais: </label><?php echo hsc($profObject->consentForm); ?> </span>
		<span class="formField"><label>Concorda com o termo? </label><?php echo ($profObject->agreesWithConsentForm) ? "Concorda" : "Não concorda"; ?></span>
	</fieldset>

	<input type="hidden" name="professors:profId" value="<?php echo $profObject->id; ?>" />
	<input type="hidden" name="professors:hidConsentFormVersion" value="<?php echo $profObject->consentForm; ?>"/>
	<br/>
    <div class="centControl">
	    <input type="submit" id="btnsubmitProfessorEditPersonalInfos" name="btnsubmitProfessorEditPersonalInfos" value="Alterar dados"/>
    </div>
</form>

<?php }
?>