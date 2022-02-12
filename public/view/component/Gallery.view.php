<style type="text/css">
	.galleryPieceFrame
	{
		position: relative;
		display: inline-block;
		border: 1px solid lightgray;
		margin: 5px;
		padding: 5px;
		height: 350px;
		border-radius: 10px;
		box-shadow: 3px 2px 10px 0 rgb(0 0 0 / 20%);
	}
	
	.galleryPieceFrame .picture { text-align: center; height: 100%; width: 100%; }
	.galleryPieceFrame .picture img 
	{ 
		max-width: 100%;
		max-height: 100%;
		border-radius: 5px; 
		display: block;
		margin-left: auto;
		margin-right: auto;
	}
	

	.galleryPieceFrame .infos 
	{ 
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		background-color: rgba(0,0,0,0.5);
		color: white;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 5px;
		border-radius: 5px;
	}
	
	.galleryPieceFrame .infos .rudLinks { text-align: center; }
	.galleryPieceFrame .infos .rudLinks a { color: white; }
	.galleryPieceFrame .infos p { font-size: 80%; margin: 5px; }
	.galleryPieceFrame .infos .pieceName { font-weight: bold; text-align: center; }
	.galleryPieceFrame .infos .pieceYear { text-align: center; }
	
	@media all and (min-width: 750px)
	{
		.galleryPieceFrame { width: 29.5%; }
	}
	
	@media all and (max-width: 749px)
	{
		.galleryPieceFrame { width: 300px; }
	}
	
</style>

<?php if (isset($dataRows)) { foreach ($dataRows as $dr): ?>

<div class="galleryPieceFrame">
	<div class="picture">
		<a href="<?php echo $detailsButtonURL ? (str_replace("{param}", $dr[$RudButtonsFunctionParamName], $detailsButtonURL)) : "#"; ?>">
			<img src="<?php echo $framesImageGeneratorFunction($dr) ?? "pics/nopic.png"; ?>" />
		</a>
	</div>
	<div class="infos">
		<p class="pieceName"><?php echo hsc($framesTitleGeneratorFunction($dr)); ?></p>
		
		<?php foreach ($framesOtherInfosGeneratorFunctions as $f): ?>
		<p><?php echo hsc($f($dr)); ?></p>
		<?php endforeach; ?>
		
		<p class="pieceYear"><?php echo $framesYearGeneratorFunction($dr); ?></p>
		<p class="rudLinks">
			<?php if ($detailsButtonURL) { ?> <a href="<?php echo str_replace("{param}", $dr[$RudButtonsFunctionParamName], $detailsButtonURL); ?>">Detalhes</a><?php } ?> 
			<?php if ($editButtonURL) { ?><a href="<?php echo str_replace("{param}", $dr[$RudButtonsFunctionParamName], $editButtonURL); ?>">Editar</a><?php } ?>
			<?php if ($deleteButtonURL) { ?><a href="<?php echo str_replace("{param}", $dr[$RudButtonsFunctionParamName], $deleteButtonURL); ?>">Excluir</a><?php } ?>
		</p>
	</div>
</div>

<?php endforeach; } else { ?>
<p>Não há obras cadastradas. </p>
<?php } ?>