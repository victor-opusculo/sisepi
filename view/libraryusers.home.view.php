<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="libraryusers"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get" action="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "view"); ?>">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="libraryusers"/>
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
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "email"); ?>">E-mail</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "typeName"); ?>">Tipo</a>
	
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "create"); ?>">Novo</a>
</div>
<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfulluserstotable.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para tabela</a>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfulluserstocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>