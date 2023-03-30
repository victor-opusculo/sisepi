
<?php if ($pubObj !== null) { ?>
<form action="<?php echo URL\URLGenerator::generateFileURL('post/editlibpublication.post.php', "cont=librarycollection&action=edit&id=$pubObj->id"); ?>" method="post">
	<input type="hidden" name="libcollection:publicationId" value="<?php echo $pubObj->id; ?>"/>
	<span class="formField"><label>ID: <?php echo $pubObj->id; ?></label></span>
	<span class="formField"><label>Título e subtítulo: <input type="text" name="libcollection:txtTitle" required="required" size="80" maxlength="280" value="<?php echo hscq($pubObj->title); ?>"/></label></span>
	<span class="formField"><label>Data de registro: <input type="date" name="libcollection:dateRegistrationDate"  required="required" value="<?php echo $pubObj->registrationDate; ?>" /></label></span>
	<span class="formField"><label>Autor: <input type="text" name="libcollection:txtAuthor"  required="required" size="40" maxlength="110" value="<?php echo hscq($pubObj->author); ?>"/></label></span>
	<span class="formField"><label>CDU: <input type="text" name="libcollection:txtCDU" size="30" maxlength="110" value="<?php echo hscq($pubObj->cdu); ?>"/></label></span>
	<span class="formField"><label>CDD: <input type="text" name="libcollection:txtCDD" size="30" maxlength="110" value="<?php echo hscq($pubObj->cdd); ?>"/></label></span>
	<span class="formField"><label>ISBN: <input type="text" name="libcollection:txtISBN" size="30" maxlength="110" value="<?php echo hscq($pubObj->isbn); ?>"/></label></span>
	<span class="formField">
		<label>Código do autor (Cutter-Sanborn): <input type="text" name="libcollection:txtAuthorCode" size="30" maxlength="110" value="<?= hscq($pubObj->authorCode) ?>" /></label>
		<button type="button" id="btnLoadCutterCode">Gerar</button>
	</span>
	<span class="formField"><label>Local: <input type="text" name="libcollection:txtLocal"  required="required" size="40" maxlength="110" value="<?php echo hscq($pubObj->local); ?>"/></label></span>
	<span class="formField"><label>Editora: <input type="text" name="libcollection:txtPublisher" size="40" maxlength="110" value="<?php echo hscq($pubObj->publisher_edition); ?>"/> </label></span>
	<span class="formField"><label>Número: <input type="text" name="libcollection:txtNumber" size="40" maxlength="110" value="<?php echo hscq($pubObj->number); ?>"/> </label></span>
	<span class="formField"><label>Mês: <input type="text" size="30" name="libcollection:txtMonth" maxlength="110" value="<?php echo hscq($pubObj->month); ?>"/> </label></span>
	<span class="formField"><label>Ano: <input type="text" name="libcollection:txtYear" value="<?php echo $pubObj->year; ?>"/></label></span>
	<span class="formField"><label>Edição: <input type="text" name="libcollection:txtEdition" size="40" maxlength="110" value="<?php echo hscq($pubObj->edition); ?>"/></label></span>
	<span class="formField"><label>Volume: <input type="text" name="libcollection:txtVolume" size="40" maxlength="110" value="<?php echo hscq($pubObj->volume); ?>"/></label></span>
	<span class="formField"><label>Exemplar: <input type="text" name="libcollection:txtCopyNumber" size="40" maxlength="110" value="<?php echo hscq($pubObj->copyNumber); ?>"/></label></span>
	<span class="formField"><label>Número de páginas: <input type="text" name="libcollection:txtPageNumber" value="<?php echo hscq($pubObj->pageNumber); ?>"/></label></span>
	
	<span class="formField">
		<label>Tipo de aquisição:
			<select name="libcollection:selAcquisitionType">
				 <?php foreach ($acqTypes as $at): ?>
				<option value="<?php echo $at->id; ?>" <?php echo ($at->id == $pubObj->typeAcquisitionId) ? 'selected="selected"' : ''; ?>><?php echo hsc($at->value) ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField">
		<label>Preço: <input type="number" name="libcollection:numPrice" step="any" value="<?php echo $pubObj->price; ?>"/></label>
		<label><input type="checkbox" name="libcollection:chkProhibitedSale" value="1" <?php echo (bool)$pubObj->prohibitedSale ? 'checked' : ''; ?>/>Venda proibida</label>
	</span>
	<span class="formField"><label>Fornecedor/Nº do termo: <input type="text" name="libcollection:txtProvider" size="40" maxlength="110" value="<?php echo hscq($pubObj->provider); ?>"/></label></span>
	<span class="formField"><label>Exclusão por doação/Nº do termo: <input type="text" name="libcollection:txtExclusionInfo" value="<?php echo hsc($pubObj->exclusionInfoTerm); ?>"/></label></span>
	
	<input type="hidden" name="libcollection:hidRegisteredByUserId" value="<?php echo $_SESSION["userid"]; ?>" /> 
	
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados"/>
</form>

<script src="<?= URL\URLGenerator::generateFileURL('view/fragment/libraryCollectionGetCutterCode.js') ?>"></script>
<script>
	const form = document.querySelector('form');
	setUpCutterCodeLoader
	({
		setData: data => form.elements['libcollection:txtAuthorCode'].value = `${data.initial}${data.code}${(form.elements['libcollection:txtTitle'].value[0] || '').toLowerCase()}`,
		getName: () => form.elements['libcollection:txtAuthor'].value,
		buttonLoad: document.getElementById('btnLoadCutterCode')
	});
	
</script>

<?php } ?>