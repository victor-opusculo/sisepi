<h2>Selecionar docente</h2>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="page" value="selectprofessor"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">A-Z</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "registrationDate"); ?>">Data de registro</a>
</div>
<script>
	function btnSelectProfessor_onClick(e, profId)
	{
		e.preventDefault();
		if (window.opener)
		{
			window.opener.setProfessorIdInput(profId);
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