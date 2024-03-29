<div class="viewDataFrame">
    <h3>Plano de aula vinculado</h3>
    <label>Tema: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'viewworkproposal', $proposalObject->id); ?>">
    <?php echo hsc($proposalObject->name); ?></a> <br/>
    <label>Outras informações: </label><?php echo nl2br(hsc($proposalObject->moreInfos)); ?> <br/>
    <label>Docente dono: </label><?php echo hsc($proposalObject->ownerProfessorName); ?>

    <h3>Evento vinculado</h3>
    <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $workSheetObject->eventId); ?>"><?php echo hsc($workSheetObject->attachedData['eventName']); ?></a> <br/>

    <h3>Docente desta ficha</h3>
    <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'view', $workSheetObject->professorId); ?>"><?php echo hsc($workSheetObject->attachedData['professorName']); ?></a> <br/>
    <label>Função: </label><?php echo hsc($workSheetObject->paymentInfosJson->professorTypes[$workSheetObject->professorTypeId]->name); ?>

    <h3>Atividade exercida</h3>
    <label>Nome: </label><?php echo hsc($workSheetObject->participationEventDataJson->activityName) ?? ''; ?><br/>
    <label>Datas: </label><?php echo hsc($workSheetObject->participationEventDataJson->dates) ?? ''; ?><br/>
    <label>Horários: </label><?php echo hsc($workSheetObject->participationEventDataJson->times) ?? ''; ?><br/>
    <label>Carga horária: </label><?php echo hsc($workSheetObject->participationEventDataJson->workTime) ?? ''; ?>

    <h3>Pagamento</h3>
    <?php 
        $paymentBaseValue = 0;
        $paymentSubsAllowanceValue = 0;
    ?>
    <label>Mês de referência: </label><?php echo date_create($workSheetObject->referenceMonth)->format('m/Y'); ?> <br/>
    <label>Quadro: </label><?php echo hsc($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentTableId]->tableName); ?> <br/>
    <label>Nível: </label><?php echo hsc($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentTableId]->levels[$workSheetObject->paymentLevelId]->name); ?> <br/>
    <label>Valor da hora-aula: </label><?php echo hsc( 
        formatDecimalToCurrency(
            $workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentTableId]->levels[$workSheetObject->paymentLevelId]->classTimeValue
            *
            ($workSheetObject->paymentInfosJson->professorTypes[$workSheetObject->professorTypeId]->paymentMultiplier)
            )
        ); ?> <br/>
    <label>Horas-aula cumpridas: </label><?php echo hsc(sprintf('%g', $workSheetObject->classTime)); ?><br/>
    <label>Total: </label><?php echo hsc(
        formatDecimalToCurrency( $paymentBaseValue = 
            ($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentTableId]->levels[$workSheetObject->paymentLevelId]->classTimeValue)
            *
            ($workSheetObject->classTime)
            *
            ($workSheetObject->paymentInfosJson->professorTypes[$workSheetObject->professorTypeId]->paymentMultiplier)
        )
    );
    ?>   
    <?php if (!is_null($workSheetObject->paymentSubsAllowanceTableId)): ?>
        <h5>&#10152; Ajuda de custo</h5>
        <label>Quadro: </label><?php echo hsc($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentSubsAllowanceTableId]->tableName); ?> <br/>
        <label>Nível: </label><?php echo hsc($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentSubsAllowanceTableId]->levels[$workSheetObject->paymentSubsAllowanceLevelId]->name); ?> <br/>
        <label>Valor da hora-aula de ajuda de custo: </label><?php echo hsc( 
            formatDecimalToCurrency(
                $workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentSubsAllowanceTableId]->levels[$workSheetObject->paymentSubsAllowanceLevelId]->classTimeValue
                )
            ); ?> <br/>
        <label>Horas-aula de ajuda de custo: </label><?php echo hsc(sprintf('%g', $workSheetObject->paymentSubsAllowanceClassTime)); ?><br/>
        <label>Total: </label>
    <?php echo hsc(
        formatDecimalToCurrency( $paymentSubsAllowanceValue = 
            ($workSheetObject->paymentInfosJson->paymentLevelTables[$workSheetObject->paymentSubsAllowanceTableId]->levels[$workSheetObject->paymentSubsAllowanceLevelId]->classTimeValue)
            *
            ($workSheetObject->paymentSubsAllowanceClassTime)
        )
    );
    ?>   
    <?php endif; ?>
    <h5>&#10152; INSS</h5>
    <?php if ((bool)$workSheetObject->paymentInfosJson->collectInss): ?>
        <span>Docente recolhe INSS.</span> <br/>
        <fieldset>
            <legend>Declaração INSS</legend>
            <?php 
            $inssPeriodBegin = $workSheetObject->paymentInfosJson->inssPeriodBegin ?? null;
            $inssPeriodEnd = $workSheetObject->paymentInfosJson->inssPeriodEnd ?? null;
            ?>
            <label>Período: </label><?php echo $inssPeriodBegin ? date_create($inssPeriodBegin)->format('d/m/Y') : '***' ; ?> a 
            <?php echo $inssPeriodEnd ? date_create($inssPeriodEnd)->format('d/m/Y') : '***'; ?>
            <table>
                <thead>
                    <tr>
                        <th>Empresa</th><th>CNPJ</th><th>Remuneração</th><th>INSS retido</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    <?php $icCount = !empty($workSheetObject->paymentInfosJson->companies) ? count($workSheetObject->paymentInfosJson->companies) : 0;
                    for ($i = 0; $i < $icCount; $i++): 
                        $icName = $workSheetObject->paymentInfosJson->companies[$i]->name;
                        $icCnpj = $workSheetObject->paymentInfosJson->companies[$i]->cnpj;
                        $icWage = $workSheetObject->paymentInfosJson->companies[$i]->wage;
                        $icCollectedInss = $workSheetObject->paymentInfosJson->companies[$i]->collectedInss;
                        
                        if (!$icName && !$icCnpj && !$icWage && !$icCollectedInss)
                            continue;
                        ?>
                        <tr>
                            <td><?php echo hsc($icName); ?></td>
                            <td><?php echo hsc($icCnpj); ?></td>
                            <td><?php echo $icWage ? hsc(formatDecimalToCurrency($icWage)) : ''; ?></td>
                            <td><?php echo $icCollectedInss ? hsc(formatDecimalToCurrency($icCollectedInss)) : ''; ?></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </fieldset>
    <?php else: ?>
        <span>Docente não recolhe INSS.</span>
    <?php endif; ?>

    <?php 
    $valueToDiscount = 0;
    if (!empty($workSheetObject->paymentInfosJson->discounts)): ?>
    <h5>&#10152; Descontos</h5>
    <ul>
        <?php foreach ($workSheetObject->paymentInfosJson->discounts as $key => $disc): ?>
            <?php 
                $thisDiscount = ($disc->discount ?? 0) * $paymentBaseValue;
                $valueToDiscount += $thisDiscount;
            ?>
            <li><label><?= $disc->label ?? '' ?></label>: <?= formatDecimalToCurrency($thisDiscount) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <h5>&#10152; Valor total</h5>
    <label>Bruto: </label><?php echo formatDecimalToCurrency($paymentBaseValue + $paymentSubsAllowanceValue); ?> <br/>
    <label>Com descontos aplicados: </label><?= formatDecimalToCurrency($paymentBaseValue + $paymentSubsAllowanceValue - $valueToDiscount) ?> <br/>

    <h3>Certificado de docente</h3>
    <?php echo !empty($workSheetObject->professorCertificateText) ? 'Habilitado' : 'Desabilitado'; ?>
    <?php if (!empty($workSheetObject->professorCertificateText)): ?>
        <br/>
        <a class="linkButton" target="__blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/generateProfessorCertificate.php', [ 'workSheetId' => $workSheetObject->id ]); ?>">Gerar certificado</a>
    <?php endif; ?>

    <h3>Documentação para empenho</h3>
    <label>Modelo de documentação: </label><?php echo $workSheetObject->attachedData['docTemplateName']; ?> <br/>
    <label>Assinaturas liberadas a partir de: </label><?php echo date_create($workSheetObject->signatureDate)->format('d/m/Y'); ?> <br/>
    <a class="linkButton" target="__blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/generateProfessorWorkDocs.php', ['workSheetId' => $workSheetObject->id ]); ?>">Visualizar documentação</a>

    <h4>Assinaturas</h4>
    <ul>
    <?php
    $checkIfFieldIsSigned = function($docSignatureId) use ($workSheetObject)
    {
        foreach ($workSheetObject->_signatures as $sign)
            if ($sign->docSignatureId === (int)$docSignatureId)
                return true;
        return false;
    };
    
    foreach ($workSheetObject->_signaturesFields as $sf): ?>
        <li><?php echo $sf->signatureLabel; ?>: <?php echo $checkIfFieldIsSigned($sf->docSignatureId) ? '<span style="color:green;">Assinado</span>' : '<span style="color:red;">Não assinado</span>'; ?></li>
    <?php endforeach; ?>
    </ul>
</div>

<div class="editDeleteButtonsFrame">
    <ul>
        <li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "editworksheet", $workSheetObject->id); ?>">Editar</a></li>
        <li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "deleteworksheet", $workSheetObject->id); ?>">Excluir</a></li>
    </ul>
</div>