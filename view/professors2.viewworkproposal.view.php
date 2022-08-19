<?php

 if (isset($proposalObj)): ?>

<?php 
function buildProposalStatus($status)
{
    if (is_null($status))
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/delay.png') . '"/> Análise pendente';
    else if ((bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/check.png') . '"/> Aprovada!';
    else if (!(bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/wrong.png') . '"/> Rejeitada!';
}
?>
<script>
    var feedbackSent = false;
    function disableFeedbackButtons()
    {
        if (!feedbackSent)
        {
            document.querySelectorAll('.btnFeedback').forEach( btn => btn.style.cursor = 'wait' );
            feedbackSent = true;
            return true;
        }
        else
            return false;
    }
</script>
    <div class="viewDataFrame">
        <label>Nome: </label><?php echo hsc($proposalObj->name); ?> <br/>
        <label>Descrição: </label><?php echo nl2br(hsc($proposalObj->description)); ?>
        <br/>
        <label>Status: </label><?php buildProposalStatus($proposalObj->isApproved); ?><br/>
        <label>Docente dono: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'view', $proposalObj->ownerProfessorId); ?>"><?php echo $proposalObj->ownerProfessorName; ?></a><br/>
        <label>Arquivo: </label><a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorWorkProposalFile.php', [ 'workProposalId' => $proposalObj->id ]); ?>" target="__blank">Ver arquivo da proposta</a>
        (<?php echo mb_strtoupper($proposalObj->fileExtension); ?>) <br/>
        <label>Data e horário de envio: </label><?php echo date_create($proposalObj->registrationDate)->format('d/m/Y H:i:s'); ?>
    </div>

    <form 
        method="post"
        action="<?php echo URL\URLGenerator::generateFileURL("post/professors2.viewworkproposal.post.php", "cont=professors2&action=viewworkproposal&id=" . $proposalObj->id); ?>"
        class="centControl"
        onsubmit="return disableFeedbackButtons();">
        <?php if (checkUserPermission('PROFE', 6)): ?>
            <label>Mensagem de feedback: <textarea name="txtFeedbackMessage" rows="4" maxlength="600"><?php echo $proposalObj->feedbackMessage; ?></textarea></label>
            <button type="submit" class="btnFeedback" name="btnApprove">Aprovar</button>
            <button type="submit" class="btnFeedback" name="btnReject">Rejeitar</button>
            <br/>
            <label><input type="checkbox" name="chkSendProfessorEmail" value="1"/> Enviar e-mail ao docente informando a aprovação/rejeição e a mensagem de feedback</label>
            <input type="hidden" name="workProposalId" value="<?php echo $proposalObj->id; ?>" />
        <?php else: ?>
            <p>Você não tem permissão para aprovar ou rejeitar propostas de trabalho.</p>
        <?php endif; ?>
    </form>

    <div class="editDeleteButtonsFrame">
		<ul>
			<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "editworkproposal", $proposalObj->id); ?>">Editar</a></li>
			<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "deleteworkproposal", $proposalObj->id); ?>">Excluir</a></li>
		</ul>
	</div>

    <h3>Fichas de trabalho vinculadas</h3>
    <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'createworksheet', null, [ 'workProposalId' => $proposalObj->id ]); ?>">Nova</a>

    <?php $dgComp->render(); ?>
    
<?php endif; ?>