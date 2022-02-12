<?php if (!empty($_GET['messages'])) { ?>

<?php } else { ?>

<?php if ($pubObj !== null) { ?>
<form action="<?php echo URL\URLGenerator::generateFileURL('post/deletelibpublication.post.php', "cont=librarycollection&action=delete&id=$pubObj->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta publicação do acervo? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<input type="hidden" name="publicationId" value="<?php echo $pubObj->id; ?>"/>
		<label>ID: </label><?php echo $pubObj->id; ?> <br/>
		<label>Título e subtítulo: </label><?php echo hsc($pubObj->title); ?> <br/>
		<label>Autor: </label><?php echo hsc($pubObj->author); ?> <br/>
		<label>CDU: </label><?php echo hsc($pubObj->cdu); ?> <br/>
		<label>CDD: </label><?php echo hsc($pubObj->cdd); ?> <br/>
		<label>ISBN: </label><?php echo hsc($pubObj->isbn); ?> <br/>
		<label>Editora/Edição: </label><?php echo hsc($pubObj->publisher_edition); ?> <br/>
		<label>Ano: </label><?php echo $pubObj->year; ?> <br/>
		<label>Volume: </label><?php echo hsc($pubObj->volume); ?> <br/>
		<label>Exemplar: </label><?php echo hsc($pubObj->copyNumber); ?> <br/>
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeletePub" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("librarycollection"); ?>';"/>
	</div>
</form>
<?php }
} ?>