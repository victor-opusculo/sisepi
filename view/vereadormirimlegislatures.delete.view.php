<?php if (!empty($legislatureObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimlegislatures.delete.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta legislatura? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $legislatureObj->id ?><br/>
		<label>Nome: </label><?php echo hsc($legislatureObj->name); ?><br/>
		<label>Início: </label><?php echo date_format(date_create($legislatureObj->begin), "d/m/Y") ?><br/>
		<label>Fim: </label><?php echo date_format(date_create($legislatureObj->end), "d/m/Y") ?><br/><br/>
		<input type="hidden" name="vmlegislatures:vmLegislatureId" value="<?php echo $legislatureObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteLegislature" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>