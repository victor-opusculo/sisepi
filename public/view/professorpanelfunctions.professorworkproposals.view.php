<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<div>
	<form method="get">
	<span class="searchFormField">
		<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="professorpanelfunctions"/>
			<input type="hidden" name="action" value="professorworkproposals"/>
		<?php endif; ?>
		<label>Pesquisar: <input type="search" name="q" size="40" maxlength="100" value="<?php echo htmlspecialchars(($_GET["q"] ?? ""), ENT_QUOTES, "UTF-8"); ?>"></label>
		<button type="submit" class="searchButton"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/search.png"); ?>" alt="pesquisar"/></button>
	</span>
	</form>
</div>
<br/>

<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'newprofworkproposal'); ?>">Nova proposta &#10010;</a>
<br/>

<?php $dgComp->render(); ?>

<?php $pagComp->render(); ?>