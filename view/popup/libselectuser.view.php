<h2>Selecionar usuário da biblioteca</h2>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="page" value="libselectuser"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "email"); ?>">E-mail</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "typeName"); ?>">Tipo</a>
</div>
<script>
	function btnSelectUser_onClick(e, userId)
	{
		e.preventDefault();
		if (window.opener)
		{
			window.opener.setUserIdInput(userId);
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