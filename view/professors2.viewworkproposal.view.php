<?php
require_once "controller/component/ExpandablePanel.class.php";
use SisEpi\Controller\Component\ExpandablePanel;

if (isset($proposalObj)): ?>

<?php 
function buildProposalStatus($status)
{
    if (is_null($status))
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/delay.png') . '"/> Análise pendente';
    else if ((bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/check.png') . '"/> Aprovado!';
    else if (!(bool)$status)
        echo '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL('pics/wrong.png') . '"/> Rejeitado!';
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
        <label>Tema: </label><?php echo hsc($proposalObj->name); ?> <br/>
              
        <label>Objetivos: </label><?php echo hsc($proposalObj->infosFields->objectives ?? ''); ?><br/>
        <label>Conteúdo: </label><?php echo hsc($proposalObj->infosFields->contents ?? ''); ?><br/>
        <label>Procedimentos: </label><?php echo hsc($proposalObj->infosFields->procedures ?? ''); ?><br/>
        <label>Recursos: </label><?php echo hsc($proposalObj->infosFields->resources ?? ''); ?><br/>
        <label>Avaliação: </label><?php echo hsc($proposalObj->infosFields->evaluation ?? ''); ?><br/>
    
        <label>Outras informações: </label><?php echo nl2br(hsc($proposalObj->moreInfos)); ?>
        <br/>
        <label>Status: </label><?php buildProposalStatus($proposalObj->isApproved); ?><br/>
        <label>Docente dono: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'view', $proposalObj->ownerProfessorId); ?>"><?php echo $proposalObj->ownerProfessorName; ?></a><br/>
        <label>Arquivo: </label>
        <?php if (isset($proposalObj->fileExtension)): ?>
            <a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorWorkProposalFile.php', [ 'workProposalId' => $proposalObj->id ]); ?>" target="__blank">Ver arquivo do plano</a>
        (<?php echo mb_strtoupper($proposalObj->fileExtension); ?>) <br/>
        <?php else: ?>
            <span style="font-style: italic;">Nenhum</span> <br/>
        <?php endif; ?>
        <label>Data e horário de envio: </label><?php echo date_create($proposalObj->registrationDate)->format('d/m/Y H:i:s'); ?>
    </div>

    <form 
        method="post"
        action="<?php echo URL\URLGenerator::generateFileURL("post/professors2.viewworkproposal.post.php", "cont=professors2&action=viewworkproposal&id=" . $proposalObj->id); ?>"
        class="centControl"
        onsubmit="return disableFeedbackButtons();">
        <?php if (checkUserPermission('PROFE', 6)): ?>
            <label>Mensagem de feedback: <textarea name="txtFeedbackMessage" rows="4" maxlength="600" tabindex="4"><?php echo $proposalObj->feedbackMessage; ?></textarea></label>
            <button type="submit" class="btnFeedback" name="btnApprove">Aprovar</button>
            <button type="submit" class="btnFeedback" name="btnReject">Rejeitar</button>
            <br/>
            <label><input type="checkbox" name="chkSendProfessorEmail" value="1"/> Enviar e-mail ao docente informando a aprovação/rejeição e a mensagem de feedback</label>
            <input type="hidden" name="workProposalId" value="<?php echo $proposalObj->id; ?>" />
        <?php else: ?>
            <p>Você não tem permissão para aprovar ou rejeitar planos de aula.</p>
        <?php endif; ?>
    </form>
    <br/>
    <fieldset>
        <legend>Objetivos de Desenvolvimento Sustentável (ODS)</legend>

        <?php if (!empty($odsProposalCodes))
        {
            ExpandablePanel::writeCssRules();

            $tabIndex = 5;
            foreach ($odsData as $ods)
            {
                $childs = [];
                foreach ($ods->goals as $goal)
                    if (in_array("{$ods->number}.{$goal->id}", $odsProposalCodes))
                        $childs[] = "<li><strong>{$ods->number}.{$goal->id}</strong> - " . hsc($goal->description) . "</li>";

                if (count($childs) > 0)
                    (new ExpandablePanel(['caption' => $ods->number . '. ' . $ods->description, 'children' => [ '<ul>', ...$childs, '</ul>' ], 'tabIndex' => $tabIndex++ ]))->render();
            }
            ?>
            <label>Copiar para relação ODS: </label>
            <button type="button" onclick="window.location.href='<?= URL\URLGenerator::generateSystemURL('odsrelations', 'create', null, [ 'checked' => $odsProposal->odsGoals ]) ?>'">Nova</a>
            <button type="button" id="btnCopyToOdsRelation">Existente</a>
            <script src="<?= URL\URLGenerator::generateFileURL('view/fragment/odsRelationByIdLoader.js') ?>" ></script>
            <script>
                setUpOdsRelationByIdLoader
                ({
                    setId: id => 
                        window.location.href = '<?= URL\URLGenerator::generateSystemURL('odsrelations', 'edit', '{odsRelationId}', [ 'checked' => $odsProposal->odsGoals ]) ?>'.replace('{odsRelationId}', id),
                    setData: () => void 0,
                    getId: () => void 0,
                    buttonLoad: null,
                    buttonSearch: document.getElementById('btnCopyToOdsRelation')
                });
            </script>
            <?php 
        }
        else
        { ?>
            <p><em>Nenhuma meta ODS informada pelo docente.</em></p>
        <?php } ?>
    </fieldset>

    <div class="editDeleteButtonsFrame">
		<ul>
			<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "editworkproposal", $proposalObj->id); ?>">Editar plano de aula</a></li>
			<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "deleteworkproposal", $proposalObj->id); ?>">Excluir plano de aula</a></li>
		</ul>
	</div>

    <h3>Fichas de trabalho vinculadas</h3>
    <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'createworksheet', null, [ 'workProposalId' => $proposalObj->id ]); ?>">Nova</a>

    <?php $dgComp->render(); ?>
    
<?php endif; ?>