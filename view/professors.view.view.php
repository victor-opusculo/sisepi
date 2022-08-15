<?php if($profObject !== null) 
{
?>

<div class="viewDataFrame">
	<label>ID: </label><?php echo $profObject->id; ?> <br/>
	<label>Data de registro: </label><?php echo date_format(date_create($profObject->registrationDate), "d/m/Y H:i:s"); ?>

	<fieldset>
		<legend>Informações básicas</legend>
		<label>Nome: </label><?php echo hsc($profObject->name); ?> <br/>
		<label>E-mail: </label><?php echo hsc($profObject->email); ?> <br/>
		<label>Telefone: </label><?php echo hsc($profObject->telephone); ?> <br/>
		<label>Escolaridade: </label><?php echo hsc($profObject->schoolingLevel); ?> <br/>
		<label>Temas de interesse: </label><?php echo hsc($profObject->topicsOfInterest); ?> <br/>
		<label>Plataforma Lattes: </label><?php echo hsc($profObject->lattesLink); ?> <br/>
		<label>Recolhe INSS? </label><?php echo hsc(isset($profObject->collectInss) ? ((bool)$profObject->collectInss ? 'Sim. E Autoriza desconto de 11%.' : 'Não') : 'Indefinido' ); ?> 
	</fieldset>
	<fieldset>
        <legend>Declaração INSS</legend>
        <?php 
        $inssPeriodBegin = $profObject->inssCollectInfos->periodBegin ?? null;
        $inssPeriodEnd = $profObject->inssCollectInfos->periodEnd ?? null;
        ?>
        <label>Período: </label><?php echo $inssPeriodBegin ? date_create($inssPeriodBegin)->format('d/m/Y') : '***' ; ?> a 
		<?php echo $inssPeriodEnd ? date_create($inssPeriodEnd)->format('d/m/Y') : '***'; ?>
        <table>
            <thead>
                <tr>
                    <th>Empresa</th><th>CNPJ</th><th>Remuneração</th><th>INSS retido</th><th>Categoria trabalhador</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <?php $icCount = !empty($profObject->inssCollectInfos->companies) ? count($profObject->inssCollectInfos->companies) : 0;
                for ($i = 0; $i < $icCount; $i++): 
                    $icName = $profObject->inssCollectInfos->companies[$i]->name;
                    $icCnpj = $profObject->inssCollectInfos->companies[$i]->cnpj;
                    $icWage = $profObject->inssCollectInfos->companies[$i]->wage;
                    $icCollectedInss = $profObject->inssCollectInfos->companies[$i]->collectedInss;
                    $icWorkerCategory = $profObject->inssCollectInfos->companies[$i]->workerCategory;
					
					if (!$icName && !$icCnpj && !$icWage && !$icCollectedInss && !$icWorkerCategory)
						continue;
                    ?>
                    <tr>
                        <td><?php echo hsc($icName); ?></td>
                        <td><?php echo hsc($icCnpj); ?></td>
                        <td><?php echo hsc($icWage); ?></td>
                        <td><?php echo hsc($icCollectedInss); ?></td>
                        <td><?php echo hsc($icWorkerCategory); ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>
	<fieldset>
		<legend>Documentos pessoais</legend>
		<label>RG: </label><?php echo hsc($profObject->personalDocs->rg ?? ""); ?> <?php echo hsc($profObject->personalDocs->rgIssuingAgency ?? ""); ?> <br/>
		<label>CPF: </label><?php echo hsc($profObject->personalDocs->cpf ?? ""); ?> <br/>
		<label>PIS/PASEP: </label><?php echo hsc($profObject->personalDocs->pis_pasep ?? ""); ?>
	</fieldset>
	<fieldset>
		<legend>Endereço residencial</legend>
		<label>Logradouro: </label><?php echo hsc($profObject->homeAddress->street ?? ""); ?> <br/>
		<label>Nº: </label><?php echo hsc($profObject->homeAddress->number ?? ""); ?> <br/>
		<label>Complemento: </label><?php echo hsc($profObject->homeAddress->complement ?? ""); ?> <br/>
		<label>Bairro: </label><?php echo hsc($profObject->homeAddress->neighborhood ?? ""); ?> <br/>
		<label>Cidade/Estado: </label><?php echo hsc($profObject->homeAddress->city ?? "") . "/" . hsc($profObject->homeAddress->state ?? ""); ?>
	</fieldset>
	<fieldset>
		<legend>Currículo resumido</legend>
		<label>Formação educacional/acadêmica: </label><br/>
			<?php echo nl2br(hsc($profObject->miniResume->education  ?? "")); ?> <br/><br/>
		<label>Experiência profissional: </label><br/>
			<?php echo nl2br(hsc($profObject->miniResume->experience  ?? "")); ?> <br/><br/>
		<label>Informações complementares: </label><br/>
			<?php echo nl2br(hsc($profObject->miniResume->additionalInformation ?? "")); ?>
	</fieldset>
	<fieldset>
		<legend>Dados bancários</legend>
		<label>Banco: </label><?php echo hsc($profObject->bankData->bankName ?? ""); ?> <br/>
		<label>Agência: </label><?php echo hsc($profObject->bankData->agency ?? ""); ?> <br/>
		<label>Conta: </label><?php echo hsc($profObject->bankData->account ?? ""); ?> <br/>
		<label>Chave PIX: </label><?php echo hsc($profObject->bankData->pix ?? ""); ?>
	</fieldset>

	<fieldset>
		<legend>LGPD</legend>
		<label>Versão do termo de consentimento para tratamento de dados pessoais: </label><?php echo hsc($profObject->consentForm); ?> <br/>
		<label>Concorda com o termo? </label><?php echo ($profObject->agreesWithConsentForm) ? "Concorda" : "Não concorda"; ?><br/>
	</fieldset>

	<fieldset>
		<legend>Documentos enviados</legend>
		<?php if (isset($profPersonalDocs) && count($profPersonalDocs) > 0): ?>
			<table>
				<thead>
					<tr>
						<th>Arquivo</th><th>Tipo de documento</th><th>Data de upload</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($profPersonalDocs as $pd): ?>
						<tr>
							<td>
								<a target="_blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorPersonalDocFile.php', ['professorId' => $profObject->id, 'file' => $pd->fileName ] ); ?>">
									<?php echo $pd->fileName; ?>
								</a>
							</td>
							<td>
								<?php echo $pd->typeName; ?>
							</td>
							<td>
								<?php 
								$expiresAfterDays = $pd->expiresAfterDays;
								$changedTimestamp = filemtime(PROFESSORS_UPLOADS_DIR . "/{$profObject->id}/docs/" . $pd->fileName);
								echo date('d/m/Y H:i:s', $changedTimestamp); 
								if (!is_null($expiresAfterDays))
								{
									$expiryDateTime = date_create()->setTimestamp($changedTimestamp)->add(new DateInterval("P{$expiresAfterDays}D"));
									echo $expiryDateTime < date_create('now') ? ' <span style="color:red;">Expirado!</span>' : '';
								}
								?>
							</td>
						</tr>
					<?php endforeach; ?> 
				</tbody>
			</table>
			<br/>
		<?php else: ?>
			<p>Este docente não tem documentos cadastrados.</p>
		<?php endif; ?>
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'uploadpersonaldocs', null, [ 'professorId' => $profObject->id ] ); ?>">Editar uploads</a>
	</fieldset>
	
	<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'edit', $profObject->id); ?>">Editar</a></li>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'delete', $profObject->id); ?>">Excluir</a></li>
		</ul>
	</div>
</div>

<?php } 
else
{
	echo "Registro não localizado";
}
?>