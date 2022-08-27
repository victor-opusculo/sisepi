<?php
if (isset($proposalObj))
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/professors2.deleteworkproposal.post.php", [ 'title' => $this->subtitle ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este plano de aula de docente? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Tema: </label><?php echo hsc($proposalObj->name); ?><br/>
		<label>Outras informações: </label><?php echo nl2br(hsc($proposalObj->moreInfos)); ?><br/>
		<label>Docente dono: </label><?php echo hsc($proposalObj->ownerProfessorName); ?><br/>
		<label>Data de envio: </label><?php echo date_format(date_create($proposalObj->registrationDate), "d/m/Y H:i:s") ?><br/><br/>
		<label><input type="checkbox" name="chkDeleteWorkSheets" value="1" /> Excluir também fichas de trabalho associadas</label><br/>
		<input type="hidden" name="workProposalId" value="<?php echo $proposalObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteWorkProposal" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php
}
?>