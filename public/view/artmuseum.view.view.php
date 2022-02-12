<?php if ($artPieceObj !== null) { ?>

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

<?php $attachsPath = URL\URLGenerator::generateBaseDirFileURL("uploads/art/" . $artPieceObj->id . "/"); ?>

<?php if($artPieceObj->mainImageAttachmentFileName !== null) { ?>
<div class="artImageFrame">
	<img src="<?php echo $attachsPath . $artPieceObj->mainImageAttachmentFileName; ?>" alt="<?php echo $artPieceObj->name; ?>" />
</div>
<?php } else { ?>
<p style="text-align: center;">Sem foto</p>
<?php } ?>

<div class="viewDataFrame">
	<label>Nome da obra: </label><?php echo hsc($artPieceObj->name); ?> <br/>
	<label>Artista: </label><?php echo hsc($artPieceObj->artist); ?> <br/>
	<label>Técnica: </label><?php echo hsc($artPieceObj->technique); ?> <br/>
	<label>Ano: </label><?php echo $artPieceObj->year; ?> <br/>
	<label>Medidas (cm): </label><?php echo hsc($artPieceObj->size); ?> <br/>
	<label>Doador: </label><?php echo hsc($artPieceObj->donor); ?> <br/>
	<label>Local em que está: </label><?php echo hsc($artPieceObj->location); ?> <br/>
	<label>Descrição: </label><br/>
	<?php echo nl2br(hsc($artPieceObj->description)); ?>
	<br/>
	
	<label>Anexos: </label>
	<div>
		<ul>
			<?php foreach ($artPieceObj->attachments as $a): ?>
				<li><a href="<?php echo $attachsPath . $a->fileName; ?>"><?php echo $a->fileName; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php } ?>