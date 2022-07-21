<script>
	window.onload = function()
	{
		document.getElementById("selEventFilter").onchange = function(e)
		{
			let eventId = this.value;
			
			window.location.href = "?<?php echo URL\QueryString::getQueryStringForHtmlExcept('pageNum', 'eventId'); ?>" + (window.location.search.length > 1 ? "&" : "") + "eventId=" + eventId;
		};
	};
</script>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="mailing"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "email"); ?>">E-mail</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "name"); ?>">Nome</a> 
	Filtrar por evento:
	<select id="selEventFilter" style="width: 300px;">
		<option value="">(Exibir tudo)</option>
		<?php foreach($eventList as $e): ?>
		<option value="<?php echo $e["id"]; ?>" <?php echo ($e["id"] == ($_GET["eventId"] ?? null)) ? 'selected="selected"' : ''; ?>><?php echo hsc($e["name"]); ?></option>
		<?php endforeach; ?>
	</select>
</div>
<br/>

<?php 
$dgComp->render(); 
$pagComp->render();
?>

<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/mailingtocsv.php", URL\QueryString::getQueryStringForHtmlExcept("cont", "action")); ?>">Exportar para CSV</a>
</div>