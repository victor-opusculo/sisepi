<h2>Selecionar publicação da biblioteca</h2>

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
			<input type="hidden" name="page" value="libselectpublication"/>
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
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "title"); ?>">Título</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "author"); ?>">Autor</a>
		
	<br/>
	
	<div id="extraColumnsCheckboxes">
		Colunas extras: 
		<label><input type="checkbox" value="1" <?php echo checkForExtraColumnFlag(1) ? ' checked="checked"' : ''; ?>/>Edição</label>
		<label><input type="checkbox" value="2" <?php echo checkForExtraColumnFlag(2) ? ' checked="checked"' : ''; ?>/>Volume</label>
		<label><input type="checkbox" value="4" <?php echo checkForExtraColumnFlag(4) ? ' checked="checked"' : ''; ?>/>Exemplar</label>
	</div>
</div>
<script>
	function btnSelectPublication_onClick(e, pubId)
	{
		e.preventDefault();
		if (window.opener)
		{
			window.opener.setPublicationIdInput(pubId);
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