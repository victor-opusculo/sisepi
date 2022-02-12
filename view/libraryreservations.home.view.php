<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="libraryreservations"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get" action="<?php echo URL\URLGenerator::generateSystemURL("libraryreservations", "view"); ?>">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="libraryreservations"/>
			<input type="hidden" name="action" value="view"/>
		<?php endif; ?>
		<label>Pesquisar por ID: <input type="number" name="id" min="1" style="width: 100px;"/></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>

	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "title"); ?>">Publicação</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "userName"); ?>">Usuário</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "reservationDatetime"); ?>">Data da reserva</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "isFinalized"); ?>">Atendida</a>
	
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("libraryreservations", "create"); ?>">Nova</a>
</div>
<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfullreservationstotable.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para tabela</a>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfullreservationstocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>