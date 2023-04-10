<div>
	<form method="get">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="budget"/>
		<?php endif; ?>
        <span class="formField centControl" style="font-weight: bold; font-size: 1.2em;">
            <label>Exercício: <input type="number" name="year" step="1" min="2000" value="<?= $_GET['year'] ?? date('Y') ?>" style="width: 100px;" /></label>
        </span>
        <br/>
        <fieldset>
            <legend>Pesquisar</legend>
            <span class="formField">
                <label>Detalhes: <input type="search" name="q" size="40" maxlength="100" value="<?php echo hsc(($_GET["q"] ?? "")); ?>"></label>
            </span>
            <span class="formField">
                <label>Data de <input type="date" name="fromDate" value="<?= $_GET['fromDate'] ?? '' ?>" /></label>
                <label> até <input type="date" name="toDate" value="<?= $_GET['toDate'] ?? '' ?>" /></label>
            </span>
            <span class="formField">
                <label>Valor de <input type="number" step="0.01" min="0" name="fromValue" value="<?= $_GET['fromValue'] ?? '' ?>" /></label>
                <label> até <input type="number" step="0.01" min="0" name="toValue" value="<?= $_GET['toValue'] ?? '' ?>" /></label>
            </span>
            <button type="submit"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/> Pesquisar</button>
        </fieldset>
	</form>
</div>
<br/>

<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "id"); ?>">ID</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "dateAsc"); ?>">Data Asc</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "dateDesc"); ?>">Data Desc</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "valueAsc"); ?>">Valor Asc</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "valueDesc");; ?>">Valor Desc</a>
</div>

<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("budget", "create"); ?>">Nova dotação</a>
</div>
<br/>

<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/artpiecestocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>