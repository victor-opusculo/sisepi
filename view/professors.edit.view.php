<?php if($profObject !== null) 
{
?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/editprofessor.post.php", "cont=professors&action=edit&id=$profObject->id"); ?>" method="post">
	<span class="formField"><label>ID: </label><?php echo $profObject->id; ?> <input type="hidden" name="profId" value="<?php echo $profObject->id; ?>" /></span>
	<span class="formField"><label>Nome: </label><input type="text" name="txtName" value="<?php echo hscq($profObject->name); ?>" size="60" maxlength="120" required="required"/> </span>
	<span class="formField"><label>E-mail: </label><input type="email" name="txtEmail" value="<?php echo hscq($profObject->email); ?>" size="60" maxlength="120" required="required"/> </span>
	<span class="formField"><label>Telefone: </label><input type="text" name="txtTelephone" value="<?php echo hscq($profObject->telephone); ?>" size="20" maxlength="120" required="required"/> </span>
	<span class="formField"><label>Escolaridade: </label><input type="text" name="txtSchoolingLevel" value="<?php echo hscq($profObject->schoolingLevel); ?>" maxlength="120" required="required"/> </span>
	<span class="formField"><label>Temas de interesse: </label><input type="text" name="txtTopicsOfInterest" value="<?php echo hscq($profObject->topicsOfInterest); ?>" size="80" maxlength="300" /> </span>
	<span class="formField"><label>Plataforma Lattes: </label><input type="text" name="txtLattesLink" value="<?php echo hscq($profObject->lattesLink); ?>" size="80" maxlength="120"/> </span>
	<span class="formField"><label>Termo de consentimento para tratamento de dados pessoais: </label><?php echo hsc($profObject->consentForm); ?> </span>
	<span class="formField"><label>Concorda com o termo? </label><?php echo ($profObject->agreesWithConsentForm) ? "Concorda" : "Não concorda"; ?></span>
	<span class="formField"><label>Data de registro: </label><?php echo date_format(date_create($profObject->registrationDate), "d/m/Y H:i:s"); ?></span>
	
	<input type="submit" name="btnsubmitEditProfessor" value="Alterar dados" />
</form>

<?php }
?>