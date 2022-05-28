<form method="get">
<span class="searchFormField">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="events2"/>
			<input type="hidden" name="action" value="searchcertificates"/>
		<?php endif; ?>
		<label>E-mail: <input type="email" name="email" size="40" maxlength="250" value="<?php echo htmlspecialchars(($_GET["email"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
</form>

<?php
if (isset($dgComp)): ?>
    <h3>Resultados da pesquisa</h3>
    <?php $dgComp->render(); ?>
<?php endif; ?>