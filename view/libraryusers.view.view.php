<h3>Dados do usuário</h3>

<?php if ($userObj !== null) { ?>
<div class="viewDataFrame doubleColumnFrame">
	<label>ID: </label><?php echo $userObj->id; ?> <br/>
	<label>Nome: </label><?php echo hsc($userObj->name); ?> <br/>
	<label>Setor (CMI): </label><?php echo hsc($userObj->CMI_Department); ?> <br/>
	<label>Matrícula (CMI): </label><?php echo hsc($userObj->CMI_RegNumber); ?> <br/>
	<label>Telefone: </label><?php echo hsc($userObj->telephone); ?> <br/>
	<label>E-mail: </label><?php echo hsc($userObj->email); ?> <br/>
	<label>Tipo: </label><?php echo hsc($userObj->typeName); ?> <br/>
	<label>Concorda com o termo de consentimento? </label><?php echo $userObj->agreesWithConsentForm ? "Sim" : "Não"; ?> <br/>
	<label>Versão do termo de consetimento: </label><?php echo hsc($userObj->consentForm); ?> 
	<?php if ($userObj->lateDevolutionsCount === 1): ?>
	<p style="color:red;"><?php echo $userObj->lateDevolutionsCount; ?> devolução atrasada nos últimos 90 dias</p>
	<?php elseif ($userObj->lateDevolutionsCount > 1): ?>
	<p style="color:red;"><?php echo $userObj->lateDevolutionsCount; ?> devoluções atrasadas nos últimos 90 dias</p>
	<?php endif; ?>
</div>
<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "edit", $userObj->id); ?>">Editar</a></li>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "delete", $userObj->id); ?>">Excluir</a></li>
			<li><a id="linkBorrow" href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "create", null, [ 'userId' => $userObj->id ]); ?>">Emprestar</a></li>
			<li><a id="linkPrintLoanPermission" href="<?php echo URL\URLGenerator::generateFileURL("generate/libloanpermission.php", [ 'userId' => $userObj->id ]); ?>">Gerar permissão para empréstimo (usuários temporários)</a></li>
		</ul>
	</div>
<?php } ?>

<h3>Últimos empréstimos</h3>
<?php if ($loansDgComp) 
	if (checkUserPermission("LIBR", 10))
		$loansDgComp->render(); 
	else
		echo "<p>Você não tem permissão para ver os empréstimos</p>";
	?>

<h3>Últimas reservas</h3>
<?php if ($reservationsDgComp) 
	if (checkUserPermission("LIBR", 12))
		$reservationsDgComp->render(); 
	else
		echo "<p>Você não tem permissão para ver as reservas</p>";
	?>