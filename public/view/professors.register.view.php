<?php
$linkToConsentForm = URL\URLGenerator::generateBaseDirFileURL("uploads/terms/$consentFormTermId.pdf");
?>

<?php if (empty($_GET['messages']))
{
?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/professorRegistration.post.php", "cont=professors&action=register"); ?>" method="post">
	<span class="formField"><label>Nome completo: </label><input type="text" name="professors:txtName" size="60" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>E-mail: </label><input type="email" name="professors:txtEmail" size="60" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>Telefone (com prefixo): </label><input type="text" name="professors:txtTelephone" size="20" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>Escolaridade: </label>
		<label><input type="radio" name="professors:radSchoolingLevel" value="Sem titulação" checked="checked"/>Sem titulação</label>
		<label><input type="radio" name="professors:radSchoolingLevel" value="Superior"/>Superior</label>
		<label><input type="radio" name="professors:radSchoolingLevel" value="Especialização"/>Especialização</label>
		<label><input type="radio" name="professors:radSchoolingLevel" value="Mestrado"/>Mestrado</label>
		<label><input type="radio" name="professors:radSchoolingLevel" value="Doutorado"/>Doutorado</label>
		</span>
	<span class="formField">
		<label>Etnia: </label>
		<?php if (isset($races)): foreach ($races as $race): ?>
			<label><input type="radio" name="professors:radRace" value="<?= hscq($race->value) ?>" required ><?= hsc($race->value) ?></label>
		<?php endforeach; endif; ?>
	</span>
	<span class="formField"><label>Temas de interessse: </label><input type="text" name="professors:txtTopicsOfInterest" size="80" maxlength="300" placeholder="Opcional"/></span>
	<span class="formField"><label>Plataforma Lattes: </label><input type="text" name="txtLattesLink" size="80" maxlength="120" placeholder="Opcional"/></span>
	<span class="formField"><label>Você concorda com o <a href="<?php echo $linkToConsentForm; ?>">termo de consentimento para o tratamento dos seus dados pessoais</a>?</label>
		<label><input type="checkbox" name="professors:chkAgreesWithConsentForm" value="1" required="required"/>Concordo</label></span>
	<input type="hidden" name="professors:hidConsentFormTermId" value="<?php echo $consentFormTermId; ?>"/>
	<span class="messageFrameWithIcon"><img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>Após este credenciamento, entre em seu Painel de Docente (menu Docentes acima) e complete as informações restantes na página de alteração de dados cadastrais. 
		O cadastro completo é necessário para o processo do seu pagamento.</span>
	<input type="submit" name="btnsubmitProfessorRegistration" value="Enviar"/>
</form>
<?php } ?>