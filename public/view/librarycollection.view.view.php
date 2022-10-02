<?php if ($pubObj !== null) { ?>
<div class="viewDataFrame doubleColumnFrame">	
		<label>Código: </label><?php echo $pubObj->id; ?> <br/>
		<label>Título e subtítulo: </label><?php echo hsc($pubObj->title); ?> <br/>
		<label>Autor: </label><?php echo hsc($pubObj->author); ?> <br/>
		<label>CDU: </label><?php echo hsc($pubObj->cdu); ?> <br/>
		<label>CDD: </label><?php echo hsc($pubObj->cdd); ?> <br/>
		<label>ISBN: </label><?php echo hsc($pubObj->isbn); ?> <br/>
		<label>Local: </label><?php echo hsc($pubObj->local); ?> <br/>
		<label>Editora: </label><?php echo hsc($pubObj->publisher_edition); ?> <br/>
		<label>Número: </label><?php echo hsc($pubObj->number); ?> <br/>
		<label>Mês: </label><?php echo hsc($pubObj->month); ?> <br/>
		<label>Ano: </label><?php echo $pubObj->year; ?> <br/>
		<label>Edição: </label><?php echo hsc($pubObj->edition); ?> <br/>
		<label>Volume: </label><?php echo hsc($pubObj->volume); ?> <br/>
		<label>Exemplar: </label><?php echo hsc($pubObj->copyNumber); ?> <br/>
		<label>Número de páginas: </label><?php echo hsc($pubObj->pageNumber); ?> <br/>
		
		<label>Disponível para empréstimo? </label><?php echo $isAvailable ? '<img src="' . URL\URLGenerator::generateBaseDirFileURL("pics/check.png") . '" alt="Sim"/>' : '<img src="' . URL\URLGenerator::generateBaseDirFileURL("pics/wrong.png") . '" alt="Não"/>'; ?><br/>
		<label>Reservado por: </label><?php echo $resNumber; ?> pessoa(s)
</div>

<?php } ?>