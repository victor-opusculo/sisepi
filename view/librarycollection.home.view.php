<script>
	window.onload = function()
	{		
		function chkExtraColumns_onChange(e)
		{
			let extraColumns = [...document.querySelectorAll("#extraColumnsCheckboxes input")].map( (el) => el.checked ? Number(el.value) : 0 ).reduce( (prev, curr) => prev + curr );
						
			window.location.href = "?<?php echo URL\QueryString::getQueryStringForHtmlExcept('extraColumns'); ?>" + (window.location.search.length > 1 ? "&" : "") + "extraColumns=" + extraColumns;
		}
		
		document.querySelectorAll("#extraColumnsCheckboxes input").forEach( el => el.onchange = chkExtraColumns_onChange );
		
	};
</script>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="librarycollection"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"/></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get" action="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "view"); ?>">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="librarycollection"/>
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
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "title"); ?>">Título</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "author"); ?>">Autor</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "cdd"); ?>">CDD</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "authorCode"); ?>">Cód. Autor</a>
	
	<br/>
	
	<div id="extraColumnsCheckboxes">
		Colunas extras: 
		<label><input type="checkbox" value="1" <?php echo checkForExtraColumnFlag(1) ? ' checked="checked"' : ''; ?>/>Edição</label>
		<label><input type="checkbox" value="2" <?php echo checkForExtraColumnFlag(2) ? ' checked="checked"' : ''; ?>/>Volume</label>
		<label><input type="checkbox" value="4" <?php echo checkForExtraColumnFlag(4) ? ' checked="checked"' : ''; ?>/>Exemplar</label>
	</div>
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "create"); ?>">Novo</a>
</div>
<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfullcollectiontotags_letter10tags.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Gerar etiquetas</a>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfullcollectiontotable.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para tabela</a>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/libfullcollectiontocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>