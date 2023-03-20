<div>
	<form method="get">
		<span class="searchFormField">
			<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
				<input type="hidden" name="cont" value="vereadormirimstudents"/>
			<?php endif; ?>
			<label>Pesquisar: <input type="search" size="40" name="q" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"> </label>
			<label> Legislatura ID: <input type="number" min="1" step="1" name="legislatureId" id="numLegislatureId" class="databaseEntityIdInput" value="<?= $_GET['legislatureId'] ?? '' ?>"/></label>
			<button type="button" id="btnSearchLegislature">Procurar</button>
			<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
		</span>
		<script src="<?= URL\URLGenerator::generateFileURL('view/fragment/vereadorMirimLegislatureByIdLoader.js') ?>"></script>
		<script>
			setUpLegislatureByIdLoader
			({
				setData: data => void(0),
				setId: id => document.getElementById('numLegislatureId').value = id,
				getId: () => document.getElementById('numLegislatureId').value,
				buttonLoad: null,
				buttonSearch: document.getElementById('btnSearchLegislature')
			});
		</script>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "registrationDate"); ?>">Data de cadastro</a>
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("vereadormirimstudents", "create"); ?>">Novo</a>
</div>
<?php
$dgComp->render();

$pagComp->render();
?>