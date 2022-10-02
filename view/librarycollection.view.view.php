
<h3>Dados da publicação</h3>

<?php if ($pubObj !== null) { ?>
<div class="viewDataFrame doubleColumnFrame">	
		<label>ID: </label><?php echo $pubObj->id; ?> <br/>
		<label>Título e subtítulo: </label><?php echo hsc($pubObj->title); ?> <br/>
		<label>Data de registro: </label><?php echo date_format(date_create($pubObj->registrationDate), "d/m/Y"); ?> <br/>
		<label>Autor: </label><?php echo hsc($pubObj->author); ?> <br/>
		<label>CDU: </label><?php echo hsc($pubObj->cdu); ?> <br/>
		<label>CDD: </label><?php echo hsc($pubObj->cdd); ?> <br/>
		<label>ISBN: </label><?php echo hsc($pubObj->isbn); ?> <br/>
		<label>Local: </label><?php echo hsc($pubObj->local); ?> <br/>
		<label>Editora/Edição: </label><?php echo hsc($pubObj->publisher_edition); ?> <br/>
		<label>Número: </label><?php echo hsc($pubObj->number); ?> <br/>
		<label>Mês: </label><?php echo hsc($pubObj->month); ?> <br/>
		<label>Ano: </label><?php echo $pubObj->year; ?> <br/>
		<label>Edição: </label><?php echo hsc($pubObj->edition); ?> <br/>
		<label>Volume: </label><?php echo hsc($pubObj->volume); ?> <br/>
		<label>Exemplar: </label><?php echo hsc($pubObj->copyNumber); ?> <br/>
		<label>Número de páginas: </label><?php echo hsc($pubObj->pageNumber); ?> <br/>
		<label>Tipo de aquisição: </label><?php echo hsc($pubObj->getOtherProperties()->acqTypeName); ?> <br/>
		<label>Preço: </label><?php echo formatDecimalToCurrency($pubObj->price); ?> <?php echo (bool)$pubObj->prohibitedSale ? '(Venda proibida)' : ''; ?><br/>
		<label>Fornecedor/Nº do termo: </label><?php echo hsc($pubObj->provider); ?> <br/>
		<label>Exclusão por doação/Nº do termo: </label><?php echo hsc($pubObj->exclusionInfoTerm); ?> <br/>
		<label>Responsável pelo cadastro: </label><?php echo hsc($pubObj->getOtherProperties()->registeredByUserName); ?> <br/>
</div>
<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "edit", $pubObj->id); ?>">Editar</a></li>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "delete", $pubObj->id); ?>">Excluir</a></li>
			<li><a id="linkBorrow" href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "create", null, [ 'pubId' => $pubObj->id ] ); ?>">Emprestar</a></li>
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