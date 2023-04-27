<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="odsrelations"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>

<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome A-Z</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "goals"); ?>">Nº de metas</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "year"); ?>">Exercício</a>
</div>
<br/>

<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("odsrelations", "create"); ?>">Nova</a>
</div>
<?php
$dgComp->render();
$pagComp->render();
?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/odsRelationsToCsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>