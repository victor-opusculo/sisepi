<h2>Selecionar ficha de trabalho</h2>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="page" value="selectprofessorworksheet"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Atividade A-Z</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "profName"); ?>">Nome de docente</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "date"); ?>">Data de assinatura</a>
</div>
<script>
	var setIdCallback = null;
	function btnSelectWorkSheet_onClick(e, wsId)
	{
		e.preventDefault();
		if (window.opener)
		{
			window.opener.setWorkSheetIdInput(wsId);
			window.close();
		}
	}
</script>
<style>
	body
	{
		font-size: large;
	}
</style>
<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>