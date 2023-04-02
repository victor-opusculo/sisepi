<script>
	window.addEventListener('load', function()
	{
		function chkExtraColumns_onChange(e)
		{
			let extraColumns = [...document.querySelectorAll("#extraColumnsCheckboxes input")].map( (el) => el.checked ? Number(el.value) : 0 ).reduce( (prev, curr) => prev + curr );		
			window.location.href = "?<?php echo URL\QueryString::getQueryStringForHtmlExcept('extraColumns'); ?>" + (window.location.search.length > 1 ? "&" : "") + "extraColumns=" + extraColumns;
		}
		
		document.querySelectorAll("#extraColumnsCheckboxes input").forEach( el => el.onchange = chkExtraColumns_onChange );
	});
</script>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="professors"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>

<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">A-Z</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "registrationDate"); ?>">Data de registro</a>

	<br/>

	<div id="extraColumnsCheckboxes">
		Coluna extra: 
		<label><input type="checkbox" value="<?= professors::EXTRA_COLUMN_TOPICS_OF_INTEREST ?>" <?php echo professors::checkForExtraColumnFlag(professors::EXTRA_COLUMN_TOPICS_OF_INTEREST) ? ' checked="checked"' : ''; ?>/>Temas de interesse</label>
	</div>
</div>
<br/>

<?php
$dgComp->render();
$pagComp->render();
?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/professorstocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>