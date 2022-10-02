<?php if (!isset($_GET["messages"])) { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL('post/createlibpublication.post.php', 'cont=librarycollection&action=create'); ?>" method="post">

	<span class="formField"><label>Título e subtítulo: <input type="text" name="libcollection:txtTitle" required="required" size="80" maxlength="280" /></label></span>
	<span class="formField"><label>Data de registro: <input type="date" name="libcollection:dateRegistrationDate" value="<?php echo date("Y-m-d"); ?>" required="required" /></label></span>
	<span class="formField"><label>Autor: <input type="text" name="libcollection:txtAuthor"  required="required" size="40" maxlength="110"/></label></span>
	<span class="formField"><label>CDU: <input type="text" name="libcollection:txtCDU" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>CDD: <input type="text" name="libcollection:txtCDD" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>ISBN: <input type="text" name="libcollection:txtISBN" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>Local: <input type="text" name="libcollection:txtLocal"  required="required" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Editora: <input type="text" name="libcollection:txtPublisher" size="40" maxlength="110" /> </label></span>
	<span class="formField"><label>Número: <input type="text" name="libcollection:txtNumber" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Mês: <input type="text" size="30" maxlength="110" name="libcollection:txtMonth" /></label></span>
	<span class="formField"><label>Ano: <input type="text" size="30" maxlength="110" name="libcollection:txtYear" /></label></span>
	<span class="formField"><label>Edição: <input type="text" name="libcollection:txtEdition" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Volume: <input type="text" name="libcollection:txtVolume" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Exemplar: <input type="text" name="libcollection:txtCopyNumber" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Número de páginas: <input type="text" name="libcollection:txtPageNumber" /></label></span>
	<span class="formField">
		<label>Tipo de aquisição:
			<select name="libcollection:selAcquisitionType">
				 <?php foreach ($acqTypes as $at): ?>
				<option value="<?php echo $at->id; ?>"><?php echo hsc($at->value); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField">
		<label>Preço: <input type="number" name="libcollection:numPrice" step="any"/></label>
		<label><input type="checkbox" name="libcollection:chkProhibitedSale" value="1"/>Venda proibida</label>
	</span>
	<span class="formField"><label>Fornecedor/Nº do termo: <input type="text" name="libcollection:txtProvider" size="40" maxlength="110"/></label></span>
	<span class="formField"><label>Exclusão por doação/Nº do termo: <input type="text" name="libcollection:txtExclusionInfo" size="40" maxlength="250"/></label></span>
	
	<input type="hidden" name="libcollection:hidRegisteredByUserId" value="<?php echo $_SESSION["userid"]; ?>" /> 
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados"/>
</form>
<?php } ?>