<?php if ($userObj !== null) { ?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/editlibuser.post.php", "cont=libraryusers&action=edit&id=$userObj->id"); ?>" method="post">
	<input type="hidden" name="userId" value="<?php echo $userObj->id; ?>"/>

	<span class="formField"><label>ID: <?php echo $userObj->id; ?> </label></span>
	<span class="formField"><label>Nome: <input type="text" name="txtName" size="60" maxlength="110" required="required" value="<?php echo hscq($userObj->name); ?>"/></label></span>
	<span class="formField"><label>Setor (funcionários da CMI): <input type="text" name="txtCMIDepartment" size="50" maxlength="110" value="<?php echo hscq($userObj->CMI_Department); ?>"/></label></span>
	<span class="formField"><label>Matrícula (funcionários da CMI): <input type="text" name="txtCMIRegNumber" size="50" maxlength="110" value="<?php echo hscq($userObj->CMI_RegNumber); ?>"/></label></span>
	
	<span class="formField"><label>Telefone: <input type="text" name="txtTelephone" size="30" maxlength="80" value="<?php echo hscq($userObj->telephone); ?>"/></label></span>
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="40" maxlength="110" value="<?php echo hscq($userObj->email); ?>"/></label></span>
	<span class="formField">
		<label>Tipo: 
			<select name="selUserType">
				<?php foreach ($userTypes as $ut): ?>
				<option value="<?php echo $ut["id"]; ?>" <?php echo ((int)$ut["id"] === (int)$userObj->typeId) ? 'selected="selected"': ''; ?>><?php echo hsc($ut["value"]); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Concorda com o termo de consentimento? <?php echo $userObj->agreesWithConsentForm ? "Sim" : "Não"; ?> </label></span>
	<span class="formField">
		<label>Versão do termo de consetimento: </label><?php echo $userObj->consentForm; ?>
		<?php if ($userObj->consentForm != $consentFormVersion): ?>
		<br/>
		<label><input type="checkbox" name="chkUpdateConsentForm" value="<?php echo $consentFormVersion; ?>"/>Usar o novo termo de consentimento: </label><a href="<?php echo $currentConsentFormFile; ?>">Ver e imprimir</a>
		<?php endif; ?>
		<input type="hidden" name="hidRegisteredOldConsentForm" value="<?php echo $userObj->consentForm; ?>"/>
	</span>
	
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados" />
</form>
<?php } ?>