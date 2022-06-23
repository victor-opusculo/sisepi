<?php $consentFormLink = $consentFormFile; ?>

<?php if (!empty($_GET["messages"])) { ?>

<?php if (isset($_GET["newId"]) && isId($_GET["newId"])) { ?>
<div class="centControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "view", $_GET["newId"] ?? 0); ?>">Ver usuário</a>
</div>
<?php } ?>
<?php } else { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/createlibuser.post.php", "cont=libraryusers&action=create"); ?>" method="post">

	<span class="formField"><label>Nome: <input type="text" name="txtName" size="60" maxlength="110" required="required" /></label></span>
	<span class="formField"><label>Setor (funcionários da CMI): <input type="text" name="txtCMIDepartment" size="50" maxlength="110"/></label></span>
	<span class="formField"><label>Matrícula (funcionários da CMI): <input type="text" name="txtCMIRegNumber" size="50" maxlength="110"/></label></span>
	<span class="formField"><label>Telefone: <input type="text" name="txtTelephone" size="30" maxlength="80"/></label></span>
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="40" maxlength="110"/></label></span>
	<span class="formField">
		<label>Tipo: 
			<select name="selUserType">
				<?php foreach ($userTypes as $ut): ?>
				<option value="<?php echo $ut["id"]; ?>"><?php echo $ut["value"]; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField">Concorda com o termo de consentimento? <label><input type="checkbox" name="chkAgreesWithConsentForm" required="required" value="1"/>Concorda</label></span>
	<span class="formField"><label>Termo de consetimento: <a href="<?php echo $consentFormLink; ?>"><?php echo $consentFormLink; ?></a></label></span>
	<input type="hidden" name="hidConsentForm" value="<?php echo $consentFormVersion; ?>"/>
	
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados" />
</form>
<?php } ?>