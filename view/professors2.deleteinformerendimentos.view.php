
<?php if (isset($IrAttach)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/professors2.deleteinformerendimentos.post.php", [ 'cont' => 'professors', 'action' => 'view', 'id' => $IrAttach->professorId ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este informe de rendimentos de docente? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Docente: </label><?php echo hsc($IrAttach->getOtherProperties()->professorName); ?><br/>
		<label>Ano-calendário: </label><?php echo hsc($IrAttach->year) ?><br/>

		<input type="hidden" name="professors_ir_attachs:hidIrAttId" value="<?php echo $IrAttach->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteIr" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php endif; ?>