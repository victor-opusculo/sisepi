<?php if (!isset($_GET["messages"])) { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL('post/createlibpublication.post.php', 'cont=librarycollection&action=create'); ?>?" method="post">

	<span class="formField"><label>Título e subtítulo: <input type="text" name="txtTitle" required="required" size="80" maxlength="280" /></label></span>
	<span class="formField">
		<label>Categoria de acervo: 
			<select name="selColType">
				<?php foreach ($collTypes as $ct): ?>
				<option value="<?php echo $ct["id"]; ?>"><?php echo $ct["value"]; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Data de registro: <input type="date" name="dateRegistrationDate" value="<?php echo date("Y-m-d"); ?>" required="required" /></label></span>
	<span class="formField"><label>Autor: <input type="text" name="txtAuthor"  required="required" size="40" maxlength="110"/></label></span>
	<span class="formField"><label>CDU: <input type="text" name="txtCDU" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>CDD: <input type="text" name="txtCDD" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>ISBN: <input type="text" name="txtISBN" size="30" maxlength="110" /></label></span>
	<span class="formField"><label>Local: <input type="text" name="txtLocal"  required="required" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Editora/Edição: <input type="text" name="txtPublisher_Edition" size="40" maxlength="110" /> </label></span>
	<span class="formField"><label>Número: <input type="text" name="txtNumber" size="40" maxlength="110" /></label></span>
	<span class="formField">
		<label>Periodicidade: 
			<select name="selPeriodicity">
				<option value="0">---</option>
				<?php foreach ($periodicityTypes as $pt): ?>
				<option value="<?php echo $pt["id"]; ?>"><?php echo $pt["value"]; ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Mês: <input type="text" size="30" maxlength="110" name="txtMonth" /></label></span>
	<span class="formField"><label>Duração: <input type="text" size="40" maxlength="110" name="txtDuration" /></label></span>
	<span class="formField"><label>Itens: <input type="text" size="40" maxlength="110" name="txtItems" /></label></span>
	<span class="formField"><label>Ano: <input type="text" size="30" maxlength="110" name="txtYear" /></label></span>
	<span class="formField"><label>Edição: <input type="text" name="txtEdition" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Volume: <input type="text" name="txtVolume" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Exemplar: <input type="text" name="txtCopyNumber" size="40" maxlength="110" /></label></span>
	<span class="formField"><label>Número de páginas: <input type="text" name="txtPageNumber" /></label></span>

	<span class="formField">
		<label>Tipo de aquisição:
			<select name="selAcquisitionType">
				 <?php foreach ($acqTypes as $at): ?>
				<option value="<?php echo $at["id"]; ?>"><?php echo $at["value"] ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</span>
	<span class="formField"><label>Preço: <input type="number" name="numPrice" step="any"/></label></span>
	<span class="formField"><label>Fornecedor: <input type="text" name="txtProvider" size="40" maxlength="110"/></label></span>
	<span class="formField"><label>Data de aquisição: <input type="date" name="dateAcquisition" /></label></span>
	
	<input type="hidden" name="hidRegisteredByUserId" value="<?php echo $_SESSION["userid"]; ?>" /> 
	
	<input type="submit" name="btnsubmitSubmit" value="Enviar dados"/>
</form>
<?php } ?>