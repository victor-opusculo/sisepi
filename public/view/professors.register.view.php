<?php
$linkToConsentForm = $consentFormFile;
?>

<?php if (empty($_GET['messages']))
{
?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/professorRegistration.post.php", "cont=professors&action=register"); ?>" method="post">
	<span class="formField"><label>Nome completo: </label><input type="text" name="txtName" size="60" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>E-mail: </label><input type="email" name="txtEmail" size="60" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>Telefone (com prefixo): </label><input type="text" name="txtTelephone" size="20" placeholder="Obrigatório" maxlength="120" required="required"/></span>
	<span class="formField"><label>Escolaridade: </label>
		<label><input type="radio" name="radSchoolingLevel" value="Sem titulação" checked="checked"/>Sem titulação</label>
		<label><input type="radio" name="radSchoolingLevel" value="Superior"/>Superior</label>
		<label><input type="radio" name="radSchoolingLevel" value="Especialização"/>Especialização</label>
		<label><input type="radio" name="radSchoolingLevel" value="Mestrado"/>Mestrado</label>
		<label><input type="radio" name="radSchoolingLevel" value="Doutorado"/>Doutorado</label>
		</span>
	<span class="formField"><label>Temas de interessse: </label><input type="text" name="txtTopicsOfInterest" size="80" maxlength="300" placeholder="Opcional"/></span>
	<span class="formField"><label>Plataforma Lattes: </label><input type="text" name="txtLattesLink" size="80" maxlength="120" placeholder="Opcional"/></span>
	<span class="formField"><label>Você concorda com o <a href="<?php echo $linkToConsentForm; ?>">termo de consentimento para o tratamento dos seus dados pessoais</a>?</label>
		<label><input type="checkbox" name="chkAgreesWithConsentForm" value="1" required="required"/>Concordo</label></span>
	<input type="hidden" name="hidConsentFormVersion" value="<?php echo $consentFormVersion; ?>"/>
	<span class="messageFrameWithIcon"><img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>Após este cadastro, entre em seu Painel de Docente (menu Docentes acima) e complete as informações restantes na página de alteração de dados cadastrais. 
		O cadastro completo é necessário para o processo do seu pagamento.</span>
	<input type="submit" name="btnsubmitProfessorRegistration" value="Enviar"/>
</form>
<?php } ?>