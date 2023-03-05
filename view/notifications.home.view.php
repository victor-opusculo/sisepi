<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="notifications"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateFileURL('pics/search.png'); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>
<div class="rightControl">
<label>Ordem de exibição: </label>

	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "title"); ?>">Título</a> 
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "isRead"); ?>">Não lida/lida</a>
	<a href="?<?php echo URL\QueryString::getQueryStringForHtmlExcept("orderBy") . URL\QueryString::formatNew("orderBy", "dateTime"); ?>">Data e hora</a>
	
</div>

<?php if (isset($notListComp)): ?>

    <a class="linkButton" href="<?= URL\URLGenerator::generateSystemURL('notifications', 'subscribe'); ?>">Alterar inscrições</a>

    <p class="labelUnreadNotificationsCount" style="font-weight: bold;"><?= $unreadCount <= 0 ? 'Todas lidas' : ($unreadCount == 1 ? ' 1 não lida ' : "$unreadCount não lidas " ) ?></p>

    <?php $notListComp->render(); ?>
    <?php $pagComp->render(); ?>

<?php endif; ?>