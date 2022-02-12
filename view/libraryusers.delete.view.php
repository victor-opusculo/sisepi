<?php if (!empty($_GET['messages'])) { ?>

<?php } else { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/deletelibuser.post.php", "cont=libraryusers&action=delete&id=$userObj->id"); ?>?" method="post">
	<p style="text-align: center;">Deseja realmente excluir este usuário da biblioteca? Esta operação é irreversível!</p>
	<div class="viewDataFrame" style="column-count: 2;">
		<input type="hidden" name="userId" value="<?php echo $userObj->id; ?>"/>
		<label>ID: </label><?php echo $userObj->id; ?> <br/>
		<label>Nome: </label><?php echo hsc($userObj->name); ?> <br/>
		<label>Setor (CMI): </label><?php echo hsc($userObj->CMI_Department); ?> <br/>
		<label>Matrícula (CMI): </label><?php echo hsc($userObj->CMI_RegNumber); ?> <br/>
		<label>Telefone: </label><?php echo hsc($userObj->telephone); ?> <br/>
		<label>E-mail: </label><?php echo hsc($userObj->email); ?> <br/>
		<label>Tipo: </label><?php echo hsc($userObj->typeName); ?> <br/>
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteUser" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("libraryusers"); ?>';"/>
	</div>
</form>

<?php } ?>