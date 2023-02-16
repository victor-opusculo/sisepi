<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="events3"/>
			<input type="hidden" name="action" value="listsubscriptions"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" size="40" name="q" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome A-Z</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "email"); ?>">E-mail A-Z</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "date"); ?>">Data de inscrição</a>
</div>
<br/>
<?php
$dgComp->render();

$pagComp->render();
?>

<div class="rightControl">
    <a 
        class="linkButton"
        href="<?= URL\URLGenerator::generateFileURL('generate/allSubscriptionsToCsv.php', [ 'orderBy' => $_GET['orderBy'] ?? '', 'q' => $_GET['q'] ?? '' ] ) ?>">
        Exportar para CSV
    </a>
</div>