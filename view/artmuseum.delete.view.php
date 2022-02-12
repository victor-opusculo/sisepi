<?php if (isset($artPieceObj)) { ?>

<style type="text/css">
	.artImageFrame
	{
		text-align: center;
		height: 60vh;
		width: 100%;
	}
	
	.artImageFrame img
	{
		max-height: 100%;
		max-width: 100%;
	}
</style>

<?php 
$attachsPath = URL\URLGenerator::generateFileURL("uploads/art/" . $artPieceObj->id . "/");
?>

<?php if ($artPieceObj->mainImageAttachmentFileName !== null) { ?>
<div class="artImageFrame">
	<img src="<?php echo $attachsPath . $artPieceObj->mainImageAttachmentFileName; ?>" alt="<?php echo $artPieceObj->name; ?>" />
</div>
<?php } else { ?>
<p style="text-align: center;">Sem foto</p>
<?php } ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/deleteart.post.php", "cont=artmuseum&action=delete&id=$artPieceObj->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta obra de arte? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<input type="hidden" name="artPieceId" value="<?php echo $artPieceObj->id; ?>"/>
		<label>ID: </label><?php echo $artPieceObj->id; ?> <br/>
		<label>Nome da obra: </label><?php echo hsc($artPieceObj->name); ?> <br/>
		<label>Artista: </label><?php echo hsc($artPieceObj->artist); ?> <br/>
		<label>Técnica: </label><?php echo hsc($artPieceObj->technique); ?> <br/>
		<label>Ano: </label><?php echo $artPieceObj->year; ?> <br/>
		<label>Medidas (cm): </label><?php echo hsc($artPieceObj->size); ?> <br/>
		<label>Doador: </label><?php echo hsc($artPieceObj->donor); ?> <br/>
		<label>Valor: </label><?php echo formatDecimalToCurrency($artPieceObj->value); ?> <br/>
		<label>Local em que está: </label><?php echo hsc($artPieceObj->location); ?> <br/>
		<br/>
		
		<label>Anexos: </label>
		<ul>
			<?php foreach ($artPieceObj->attachments as $a): ?>
				<li><a href="<?php echo $attachsPath . $a->fileName; ?>"><?php echo $a->fileName; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteArt" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("artmuseum"); ?>';"/>
	</div>
</form>
<?php } ?>