<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<?php if (isset($workProposalObj)): ?>

<?php 
function buildProposalStatus($status)
{
    if (is_null($status))
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/delay.png') . '"/> Análise pendente';
    else if ((bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/check.png') . '"/> Aprovada!';
    else if (!(bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateBaseDirFileURL('pics/wrong.png') . '"/> Rejeitada!';
}
?>

    <div class="viewDataFrame">
        <label>Nome: </label><?php echo hsc($workProposalObj->name); ?> <br/>
        <label>Descrição: </label><?php echo nl2br(hsc($workProposalObj->description)); ?>
        <br/>
        <label>Status: </label><?php buildProposalStatus($workProposalObj->isApproved); ?><br/>
        <label>Relacionamento: </label><?php echo $workProposalObj->ownerProfessorId == $_SESSION['professorid'] ? 'Você é o dono da proposta' : 'Você está vinculado a esta proposta'; ?><br/>
        <label>Arquivo: </label><a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorWorkProposalFile.php', [ 'workProposalId' => $workProposalObj->id ]); ?>" target="__blank">Ver arquivo da proposta</a>
        (<?php echo mb_strtoupper($workProposalObj->fileExtension); ?>) <br/>
        <label>Data e horário de envio: </label><?php echo date_create($workProposalObj->registrationDate)->format('d/m/Y H:i:s'); ?>
    </div>
    <?php if ($workProposalObj->ownerProfessorId === $_SESSION['professorid']): ?>
    <div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("professorpanelfunctions", "editprofworkproposal", $workProposalObj->id); ?>">Editar dados</a></li>
		</ul>
	</div>
    <?php endif; ?>

    <h3>Fichas de trabalho associadas</h3>
    <?php if (!empty($workSheetsObjs)): ?>
        <?php $tabComp->render(); 
        $tabComp->beginTabsFrame(); ?>
        <?php foreach ($workSheetsObjs as $k => $wso) 
        {
            $participationData = json_decode($wso->participationEventDataJson);
            $tabComp->beginTab($participationData->activityName ?? "", $k === 0);
            echo 'TODO';
            $tabComp->endTab();
        } 
        $tabComp->endTabsFrame();
        ?>
    <?php else: ?>
        <p>Não há fichas de trabalho no seu nome vinculadas a esta proposta de trabalho.</p>
    <?php endif; ?>

<?php endif; ?>