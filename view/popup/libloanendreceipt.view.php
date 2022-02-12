<div class="centControl">
	<img src="<?php echo URL\URLGenerator::generateFileURL("pics/biblioteca.png"); ?>" alt="Biblioteca FHC" />
</div>

<h2>Biblioteca Legislativa</h2>
<h3 style="text-align: center;">Presidente Fernando Henrique Cardoso</h3>
<h4 style="text-align: center;">Comprovante de devolução de item do acervo da biblioteca</h4>

<?php if (count($pageMessages) > 0) { ?>
<div class="pageMessagesFrame">
	<?php 
	foreach ($pageMessages as $m)
	{
		echo "<p>" . $m . "</p>";
	}
	?>
</div>
<?php } else { ?>

<div class="viewDataFrame">
	<label>ID do empréstimo: </label><?php echo $bpubObj->id; ?> <br/>
	<label>Publicação emprestada: </label><?php echo hsc($bpubObj->title); ?> (ID: <?php echo $bpubObj->publicationId; ?>) <br/>
	<label>Usuário: </label><?php echo hsc($bpubObj->userName); ?> (ID: <?php echo $bpubObj->libUserId; ?>) <br/>
	<label>Data de empréstimo: </label><?php echo date_format(date_create($bpubObj->borrowDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Data limite para devolução: </label><?php echo date_format(date_create($bpubObj->expectedReturnDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Devolução efetuada em: </label> <?php echo date_format(date_create($bpubObj->returnDatetime), "d/m/Y H:i:s"); ?> <br/>
	<br/>
	<label>Comprovante emitido por: </label><?php echo hsc($_SESSION["username"]); ?> <label>em</label> <?php echo date("d/m/Y H:i:s"); ?>
</div>

<?php } ?>