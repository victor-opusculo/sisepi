<p style="text-align:center;"><em>Museu de Arte do Parlamento de Itapevi "Emanuel von Lauenstein Massarani"</em></p>


<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="artmuseum"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>

<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome da obra</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "artist"); ?>">Artista</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "donor"); ?>">Doador</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "year"); ?>">Ano</a>
</div>

<div class="viewDataFrame">
	<?php if (strlen(($_GET["q"] ?? "")) > 3): ?>
	<label>Patrimônio (resultados da pesquisa): </label>
	<?php else: ?>
	<label>Patrimônio: </label>
	<?php endif; ?>
	<br/>
	<label>Quantidade de obras: </label><?php echo $piecesCount; ?>
</div>

<?php $galComp->render(); ?>

<?php $pagComp->render(); ?>