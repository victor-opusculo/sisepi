<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="events"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">A-Z</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "date"); ?>">Data de início</a>
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "create"); ?>">Novo</a>
</div>
<?php
$dgComp->render();

$pagComp->render();
?>