<?php if (isset($termObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/terms.delete.post.php", [ 'title' => $this->subtitle ] ); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este termo? Esta operação é irreversível!</p>
	<p style="text-align: center; color:red; font-weight: bold;">ATENÇÃO: Normalmente não é recomendável excluir termos, pois isso invalidará as referências a eles feitas
	em outros dados (como inscrições de eventos). Não exclua termos antigos, apenas aqueles que não chegaram a ser usados/referenciados em outros dados!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo hsc($termObj->name); ?><br/>
		<label>Versão: </label><?php echo hsc($termObj->version); ?><br/>
		<label>Data de registro: </label><?php echo date_format(date_create($termObj->registrationDate), "d/m/Y H:i:s") ?><br/><br/>
		<input type="hidden" name="termId" value="<?php echo $termObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteTerm" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php endif; ?>