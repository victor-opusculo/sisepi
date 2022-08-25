<?php if (!empty($eventObj)): ?>
<h3>1. Identificação do evento</h3>
<div class="viewDataFrame">
    <label>Nome: </label><?php echo hsc($eventObj->name); ?> <br/>
    <label>Programa: </label><?php echo hsc($eventObj->workPlan->programName); ?> <br/>
    <label>Público-alvo: </label><?php echo hsc($eventObj->workPlan->targetAudience); ?> <br/>
    <label>Duração: </label><?php echo hsc($eventObj->workPlan->duration); ?> <br/>
    <label>Recursos: </label><?php echo hsc($eventObj->workPlan->resources); ?>
</div>

<h3>2. Responsáveis</h3>
<div class="viewDataFrame">
    <label>Coordenadores: </label><?php echo hsc($eventObj->workPlan->coordinators); ?> <br/>
    <label>Equipe: </label><?php echo hsc($eventObj->workPlan->team); ?> <br/>
    <label>Equipe associada: </label><?php echo hsc($eventObj->workPlan->assocTeam); ?> <br/>
</div>

<h3>3. Fundamentação Legal</h3>
<?php echo hsc($eventObj->workPlan->legalSubstantiation); ?>

<h3>4. Objetivo do Evento</h3>
<?php echo hsc($eventObj->workPlan->eventObjective); ?>

<h3>5. Objetivo específico</h3>
<?php echo hsc($eventObj->workPlan->specificObjective); ?>

<h3>6. Indicadores de Avaliação dos Resultados</h3>
<div class="viewDataFrame">
    <label>Vagas disponibilizadas: </label><?php echo (bool)$eventObj->subscriptionListNeeded ? $eventObj->maxSubscriptionNumber : "Não aplicável."; ?> <br/> 
    <label>Número de inscritos: </label><?php echo $subscriptionCount; ?> <br/>
    <label>Número de presentes: </label><?php echo $presentStudentsCount; ?> <br/>
</div>

<h3>7. Contrapartida Institucional</h3>
<?php if (empty($eventObj->certificateText)): ?>
<?php echo nl2br(hsc($eventObj->workPlan->manualCertificatesInfos)); ?>
<?php else: ?>
<div class="viewDataFrame">
    <label>Emissão de certificados: </label>Sim, automática. <br/> 
    <label>Número de certificados disponíveis: </label><?php echo $availableCertificatesCount; ?> <br/>
    <label>Número de certificados emitidos: </label><?php echo $generatedCertificatesCount; ?>
</div>
<?php endif; ?>

<h3>8. Observação Complementar</h3>
<?php echo hsc($eventObj->workPlan->observations); ?>

<h3>9. Descrição do Evento</h3>
<?php echo nl2br(hsc($eventObj->workPlan->eventDescription)); ?>

<h3>Anexos privados</h3>
<ul>
    <?php $workPlanAttachsPath = URL\URLGenerator::generateFileURL("uploads/eventworkplans/" . $eventObj->workPlan->id . "/"); ?>
    <?php foreach ($eventObj->workPlan->workPlanAttachments as $wpAtt): ?>
        <li><a target="__blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/viewEventWorkPlanAttachment.php', [ 'workPlanId' => $eventObj->workPlan->id, 'file' => $wpAtt->fileName ]); ?>"><?php echo hsc($wpAtt->fileName); ?></a></li>
    <?php endforeach; ?>
</ul>

<?php endif; ?>