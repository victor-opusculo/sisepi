<p style="text-align:center;"><em>Biblioteca Legislativa Presidente Fernando Henrique Cardoso</em></p>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="librarycollection"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"/></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
	<form method="get">
	<span class="searchFormField" action="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "view"); ?>">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="librarycollection"/>
			<input type="hidden" name="action" value="view"/>
		<?php endif; ?>
		<label>Pesquisar por código: <input type="number" name="id" min="1" style="width: 100px;"/></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id");; ?>">Código</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "colltype");; ?>">Cat. acervo</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "title");; ?>">Título</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "author");; ?>">Autor</a>
	
	<br/>
</div>

<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>