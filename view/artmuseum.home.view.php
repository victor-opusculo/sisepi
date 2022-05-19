<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="artmuseum"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get" action="<?php echo URL\URLGenerator::generateSystemURL("artmuseum", "view"); ?>">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="artmuseum"/>
			<input type="hidden" name="action" value="view"/>
		<?php endif; ?>
		<label>Pesquisar por número de patrimônio: <input type="number" name="cmiPropNumber" min="0" style="width: 100px;"/></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get" action="<?php echo URL\URLGenerator::generateSystemURL("artmuseum", "view"); ?>">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="artmuseum"/>
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
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome da obra</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "artist"); ?>">Artista</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "donor"); ?>">Doador</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "value");; ?>">Valor</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "year");; ?>">Ano</a>
</div>

<div class="viewDataFrame">
	<?php if (strlen(($_GET["q"] ?? "")) > 3): ?>
	<label>Patrimônio (resultados da pesquisa): </label>
	<?php else: ?>
	<label>Patrimônio: </label>
	<?php endif; ?>
	<br/>
	<label>Valor total: </label><?php echo formatDecimalToCurrency($totalValue); ?>  
	<label>Quantidade de obras: </label><?php echo $piecesCount; ?>
</div>
<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("artmuseum", "create"); ?>">Nova obra</a>
</div>

<?php $galComp->render(); ?>

<?php $pagComp->render(); ?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/artpiecestocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/artpiecestoprint.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para Impressão</a>
</div>