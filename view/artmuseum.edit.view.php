<?php if ($artPieceObj !== null) { ?>

<script src="<?php echo URL\URLGenerator::generateFileURL("view/artmuseum.edit.view.js"); ?>"></script>

<?php 
$attachsPath = URL\URLGenerator::generateFileURL("uploads/art/" . $artPieceObj->id . "/");
$frmAction = $operation === "edit" ? URL\URLGenerator::generateFileURL("post/editart.post.php?cont=artmuseum&action=edit&id=" . $_GET['id']) : URL\URLGenerator::generateFileURL("post/createart.post.php?cont=artmuseum&action=create");
?>

<form id="frmEditArt" enctype="multipart/form-data" action="<?php echo $frmAction; ?>" method="post">
	<input type="hidden" name="artPieceId" value="<?php echo $artPieceObj->id; ?>"/>
	<span class="formField"><label>ID: </label><?php echo $artPieceObj->id; ?></span>
	<span class="formField"><label>Número de patrimônio (CMI): <input type="number" name="numCMI_propertyNumber" min="0" step="1" value="<?php echo $artPieceObj->CMI_propertyNumber; ?>"/></label></span>
	<span class="formField"><label>Nome da obra: <input type="text" name="txtPieceName" required="required" size="60" maxlength="110" value="<?php echo hscq($artPieceObj->name); ?>"/></label></span>
	<span class="formField"><label>Artista: <input type="text" name="txtArtist" size="40" maxlength="110" value="<?php echo hscq($artPieceObj->artist); ?>"/></label></span>
	<span class="formField"><label>Técnica: <input type="text" name="txtTechnique" size="40" maxlength="110" value="<?php echo hscq($artPieceObj->technique); ?>"/></label></span>
	<span class="formField"><label>Ano: <input type="number" name="numYear" value="<?php echo $artPieceObj->year; ?>"/></label></span>
	<span class="formField"><label>Medidas (cm): <input type="text" name="txtSize" size="30" maxlength="70" value="<?php echo hscq($artPieceObj->size); ?>"/></label></span>
	<span class="formField"><label>Doador: <input type="text" name="txtDonor" size="40" maxlength="110" value="<?php echo hscq($artPieceObj->donor); ?>"/></label></span>
	<span class="formField"><label>Valor: <input type="number" min="0.00" step="any" name="numValue" value="<?php echo $artPieceObj->value; ?>"/></label></span>
	<span class="formField"><label>Local em que está: <input type="text" name="txtLocation" size="40" maxlength="110" value="<?php echo hscq($artPieceObj->location); ?>"/></label></span>
	<span class="formField"><label>Descrição: 
		<textarea rows="5" name="txtDescription" style="width: 100%;"><?php echo hsc($artPieceObj->description); ?></textarea>
	</label>
	</span>
	
	<br/>
	
	<label>Anexos: </label><button id="btnCreateAttachment" type="button">Criar novo</button>
	<table id="tblAttachments">
		<tbody>
			<?php foreach ($artPieceObj->attachments as $a): ?>
				<tr data-id="<?php echo $a->id; ?>">
					<td><span class="existentFileName"><?php echo $a->fileName; ?></span></td>
					<td><label><input type="radio" 
										name="radAttachmentMainImage"
										value="<?php echo $a->fileName; ?>"
										<?php echo ($a->fileName === $artPieceObj->mainImageAttachmentFileName) ? 'checked="checked"' : ''; ?>
										/>Foto principal</label></td>
					<td class="shrinkCell"><button class="btnDelAttachment" type="button" style="min-width: 20px;" data-id="<?php echo $a->id; ?>">X</button></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" id="attachmentsChangesReport" name="attachmentsChangesReport" value=""/>
	<br/>
	<div class="centControl">
		<input type="submit" id="btnsubmitSubmit" name="btnsubmitSubmit" value="Enviar dados" />
	</div>
</form>
<?php } ?>