
<?php if ($pubObj !== null) { ?>
<form action="<?php echo URL\URLGenerator::generateFileURL('post/editlibpublication.post.php', "cont=librarycollection&action=edit&id=$pubObj->id"); ?>" method="post">
	<input type="hidden" name="publicationId" value="<?php echo $pubObj->id; ?>"/>
	<span class="formField"><label>ID: <?php echo $pubObj->id; ?></label></span>
	<span class="formField"><label>Título e subtítulo: <input type="text" name="txtTitle" required="required" size="80" maxlength="280" value="<?php echo hscq($pubObj->title); ?>"/></label></span>
	<span class="formField">
		<label>Categoria de acervo: 
			<select name="selColType">
				<?php foreach ($collTypes as $ct): ?>
				<option value="<?php echo $ct["id"]; ?>" <?php echo ($ct["id"] == $pubObj->collectionTypeId) ? 'selected="selected"' : ''; ?>><?php echo $ct["value"]; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Data de registro: <input type="date" name="dateRegistrationDate"  required="required" value="<?php echo $pubObj->registrationDate; ?>" /></label></span>
	<span class="formField"><label>Autor: <input type="text" name="txtAuthor"  required="required" size="40" maxlength="110" value="<?php echo hscq($pubObj->author); ?>"/></label></span>
	<span class="formField"><label>CDU: <input type="text" name="txtCDU" size="30" maxlength="110" value="<?php echo hscq($pubObj->cdu); ?>"/></label></span>
	<span class="formField"><label>CDD: <input type="text" name="txtCDD" size="30" maxlength="110" value="<?php echo hscq($pubObj->cdd); ?>"/></label></span>
	<span class="formField"><label>ISBN: <input type="text" name="txtISBN" size="30" maxlength="110" value="<?php echo hscq($pubObj->isbn); ?>"/></label></span>
	<span class="formField"><label>Local: <input type="text" name="txtLocal"  required="required" size="40" maxlength="110" value="<?php echo hscq($pubObj->local); ?>"/></label></span>
	<span class="formField"><label>Editora/Edição: <input type="text" name="txtPublisher_Edition" size="40" maxlength="110" value="<?php echo hscq($pubObj->publisher_edition); ?>"/> </label></span>
	<span class="formField"><label>Número: <input type="text" name="txtNumber" size="40" maxlength="110" value="<?php echo hscq($pubObj->number); ?>"/> </label></span>
	<span class="formField">
		<label>Periodicidade: 
			<select name="selPeriodicity">
				<option value="0">---</option>
				<?php foreach ($periodicityTypes as $ct): ?>
				<option value="<?php echo $ct["id"]; ?>" <?php echo ($ct["id"] == $pubObj->periodicityId) ? 'selected="selected"' : ''; ?>><?php echo hsc($ct["value"]); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Mês: <input type="text" size="30" name="txtMonth" maxlength="110" value="<?php echo hscq($pubObj->month); ?>"/> </label></span>
	<span class="formField"><label>Duração: <input type="text" name="txtDuration" size="40" maxlength="110" value="<?php echo hscq($pubObj->duration); ?>"/> </label></span>
	<span class="formField"><label>Itens: <input type="text" name="txtItems" size="40" maxlength="110" value="<?php echo hscq($pubObj->items); ?>"/> </label></span>
	<span class="formField"><label>Ano: <input type="text" name="txtYear" value="<?php echo $pubObj->year; ?>"/></label></span>
	<span class="formField"><label>Edição: <input type="text" name="txtEdition" size="40" maxlength="110" value="<?php echo hscq($pubObj->edition); ?>"/></label></span>
	<span class="formField"><label>Volume: <input type="text" name="txtVolume" size="40" maxlength="110" value="<?php echo hscq($pubObj->volume); ?>"/></label></span>
	<span class="formField"><label>Exemplar: <input type="text" name="txtCopyNumber" size="40" maxlength="110" value="<?php echo hscq($pubObj->copyNumber); ?>"/></label></span>
	<span class="formField"><label>Número de páginas: <input type="text" name="txtPageNumber" value="<?php echo hscq($pubObj->pageNumber); ?>"/></label></span>
	
	
	<span class="formField">
		<label>Tipo de aquisição:
			<select name="selAcquisitionType">
				 <?php foreach ($acqTypes as $at): ?>
				<option value="<?php echo $at["id"]; ?>" <?php echo ($at["id"] == $pubObj->typeAcquisitionId) ? 'selected="selected"' : ''; ?>><?php echo hsc($at["value"]) ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Preço: <input type="number" name="numPrice" step="any" value="<?php echo $pubObj->price; ?>"/></label></span>
	<span class="formField"><label>Fornecedor: <input type="text" name="txtProvider" size="40" maxlength="110" value="<?php echo hscq($pubObj->provider); ?>"/></label></span>
	<span class="formField"><label>Data de aquisição: <input type="date" name="dateAcquisition" value="<?php echo $pubObj->dateAcquisition; ?>"/></label></span>
	
	<input type="hidden" name="hidRegisteredByUserId" value="<?php echo $_SESSION["userid"]; ?>" /> 
	
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados"/>
</form>
<?php } ?>