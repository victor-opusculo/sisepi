<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="eventchecklisttemplates"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>

<div>
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklisttemplates", "create"); ?>">Novo</a>
</div>

<?php
$dgComp->render();

$pagComp->render();
?>