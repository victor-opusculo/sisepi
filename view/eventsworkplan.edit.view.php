<div id="workPlanPageElementsTemplates" style="display: none;">
    <table>
        <tr id="newWpAttachmentTemplate">
            <td><input type="file" class="fileAttachmentFileName"/></td>
            <td class="shrinkCell"><button type="button" class="workPlanAttachmentDeleteButton" style="min-width: 20px;">&times;</button></td>
        </tr>
    </table>
</div>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/eventsworkplan.edit.view.js'); ?>"></script>

<?php if (!empty($eventObj)): ?>

<input type="hidden" name="eventworkplans:workplanId" value="<?php echo $eventObj->workPlan->id; ?>" />
<input type="hidden" name="eventworkplans:eventId" value="<?php echo $eventObj->id; ?>" />

<h3>1. Identificação do evento</h3>

<span class="formField"><label>Programa: <input type="text" name="eventworkplans:programName" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->programName); ?>" /></label></span>
<span class="formField"><label>Público-alvo: <input type="text" name="eventworkplans:targetAudience" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->targetAudience); ?>" /></label></span>
<span class="formField"><label>Duração: <input type="text" name="eventworkplans:duration" size="40" maxlength="255" value="<?php echo hscq($eventObj->workPlan->duration); ?>" /></label></span>
<span class="formField"><label>Recursos: <input type="text" name="eventworkplans:resources" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->resources); ?>" /></label></span>


<h3>2. Responsáveis</h3>

<span class="formField"><label>Coordenadores: <input type="text" name="eventworkplans:coordinators" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->coordinators); ?>" /></label></span>
<span class="formField"><label>Equipe: <input type="text" name="eventworkplans:team" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->team); ?>" /></label></span>
<span class="formField"><label>Equipe associada: <input type="text" name="eventworkplans:assocTeam" size="60" maxlength="255" value="<?php echo hscq($eventObj->workPlan->assocTeam); ?>" /></label></span>


<h3>3. Fundamentação Legal</h3>
<span class="formField">
    <textarea name="eventworkplans:legalSubstantiation" maxlength="255" rows="3"><?php echo hsc($eventObj->workPlan->legalSubstantiation); ?></textarea>
</span>

<h3>4. Objetivo do Evento</h3>
<span class="formField">
    <textarea name="eventworkplans:eventObjective" maxlength="255" rows="3"><?php echo hsc($eventObj->workPlan->eventObjective); ?></textarea>
</span>

<h3>5. Objetivo específico</h3>
<span class="formField">
    <textarea name="eventworkplans:specificObjective" maxlength="255" rows="3"><?php echo hsc($eventObj->workPlan->specificObjective); ?></textarea>
</span>

<h3>6. Indicadores de Avaliação dos Resultados</h3>
<p>Informações geradas automaticamente.</p>

<h3>7. Contrapartida Institucional</h3>
<span class="formField">
    <label>Caso o certificado seja produzido manualmente ou este evento não forneça certificado, informe abaixo as informações pertinentes ao caso. 
        O texto inserido só será exibido se a geração automática de certificados estiver desabilitada. </label>
    <textarea name="eventworkplans:manualCertificatesInfos" maxlength="280" rows="3"><?php echo hsc($eventObj->workPlan->manualCertificatesInfos); ?></textarea>
</span>

<h3>8. Observação Complementar</h3>
<span class="formField">
    <textarea name="eventworkplans:observations" maxlength="300" rows="3"><?php echo hsc($eventObj->workPlan->observations); ?></textarea>
</span>

<h3>9. Descrição do Evento</h3>
<span class="formField">
    <textarea name="eventworkplans:eventDescription" maxlength="65000" rows="8"><?php echo hsc($eventObj->workPlan->eventDescription); ?></textarea>
</span>

<h3>Anexos privados</h3>

<button id="btnAddWorkPlanAttachment" type="button">Novo</button>
<table>
    <tbody id="tbodyWpAttachments">
        <?php foreach ($eventObj->workPlan->workPlanAttachments as $wpAtt): ?>
            <tr data-wpattid="<?php echo $wpAtt->id; ?>">
                <td><span class="existentFileName"><?php echo hsc($wpAtt->fileName); ?></span></td>
                <td class="shrinkCell"><button type="button" class="workPlanAttachmentDeleteButton" style="min-width: 20px;">&times;</button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<input type="hidden" id="eventWorkPlanAttachmentsChangesReport" name="eventWorkPlanAttachmentsChangesReport" value="" />

<?php endif; ?>