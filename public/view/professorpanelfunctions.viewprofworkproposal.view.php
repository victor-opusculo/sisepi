<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<?php if (isset($workProposalObj)): ?>

<?php 
function buildProposalStatus($status)
{
    if (is_null($status))
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/delay.png') . '"/> Análise pendente';
    else if ((bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/check.png') . '"/> Aprovado!';
    else if (!(bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/wrong.png') . '"/> Rejeitado!';
}
?>

    <div class="viewDataFrame">
        <label>Tema: </label><?php echo hsc($workProposalObj->name); ?> <br/>

        <label>Objetivos: </label><?php echo hsc($workProposalObj->infosFields->objectives ?? ''); ?><br/>
        <label>Conteúdo: </label><?php echo hsc($workProposalObj->infosFields->contents ?? ''); ?><br/>
        <label>Procedimentos: </label><?php echo hsc($workProposalObj->infosFields->procedures ?? ''); ?><br/>
        <label>Recursos: </label><?php echo hsc($workProposalObj->infosFields->resources ?? ''); ?><br/>
        <label>Avaliação: </label><?php echo hsc($workProposalObj->infosFields->evaluation ?? ''); ?><br/>

        <label>Outras informações: </label><?php echo nl2br(hsc($workProposalObj->moreInfos)); ?>
        <br/>
        <label>Status: </label><?php buildProposalStatus($workProposalObj->isApproved); ?><br/>
        <?php if ($workProposalObj->ownerProfessorId === $_SESSION['professorid']): ?>
            <label>Feedback: </label><?php echo nl2br(hsc($workProposalObj->feedbackMessage)); ?> <br/>
        <?php endif; ?>
        <label>Relacionamento: </label><?php echo $workProposalObj->ownerProfessorId == $_SESSION['professorid'] ? 'Você é o dono do plano' : 'Você está vinculado a este plano'; ?><br/>
        <label>Arquivo: </label>
        <?php if (isset($workProposalObj->fileExtension)): ?>
            <a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorWorkProposalFile.php', [ 'workProposalId' => $workProposalObj->id ]); ?>" target="__blank">Ver arquivo do plano</a>
            (<?php echo mb_strtoupper($workProposalObj->fileExtension); ?>) <br/>
        <?php else: ?>
            <span style="font-style: italic;">Nenhum</span> <br/>
        <?php endif; ?>
        <label>Data e horário de envio: </label><?php echo date_create($workProposalObj->registrationDate)->format('d/m/Y H:i:s'); ?>
    </div>
    <?php if ($workProposalObj->ownerProfessorId === $_SESSION['professorid']): ?>
    <div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("professorpanelfunctions", "editprofworkproposal", $workProposalObj->id); ?>">Editar plano</a></li>
		</ul>
	</div>
    <?php endif; ?>

    <h3>Fichas de trabalho associadas</h3>
    <?php if (!empty($workSheetsObjs)): ?>
        <?php $tabComp->render(); 
        $tabComp->beginTabsFrame(); ?>
        <?php foreach ($workSheetsObjs as $k => $wso) 
        {
            $tabComp->beginTab($k + 1, $k === 0);
            include('view/fragment/professorpanelfunctions.viewprofworksheet.view.php');
            $tabComp->endTab();
        } 
        $tabComp->endTabsFrame();
        ?>
    <?php else: ?>
        <p>Não há fichas de trabalho no seu nome vinculadas a este plano de aula.</p>
    <?php endif; ?>

<?php endif; ?>