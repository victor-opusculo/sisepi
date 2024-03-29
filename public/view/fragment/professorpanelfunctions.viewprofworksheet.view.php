<?php
$workSheetObject = $wso;
$signatureDate = date_create($workSheetObject->signatureDate);
?>
<div class="viewDataFrame">

    <h3>Evento vinculado</h3>
    <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $workSheetObject->eventId); ?>"><?php echo hsc($workSheetObject->attachedData['eventName']); ?></a> <br/>

    <h3>Docente desta ficha</h3>
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
            <br/>
            <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'editinssdeclaration', null, [ 'workSheetId' => $workSheetObject->id ] ); ?>">Editar</a>
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
    
    <div class="messageFrameWithIcon">
        <img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>
        O valor líquido a ser creditado em sua conta pode diferir do valor acima caso você some, dentro de um mês, remunerações altas o suficiente para a cobrança de imposto de renda e também diferirá de acordo com sua contribuição com a previdência.
    </div>

    <h3>Certificado de docente</h3>
    <?php if (!empty($workSheetObject->professorCertificateText)): ?>
        <p>Disponível a partir de <?php echo $signatureDate->format('d/m/Y'); ?>.</p>
        <?php if ($signatureDate <= new DateTime('now')): ?>
            <a class="linkButton" target="__blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/generateProfessorCertificate.php', [ 'workSheetId' => $workSheetObject->id ]); ?>">Gerar certificado</a>
        <?php endif; ?>
    <?php else: ?>
        </p>Não disponível</p>
    <?php endif; ?>

    <h3>Documentação para o empenho</h3>
    <label>Visualização e assinaturas liberadas a partir de: </label><?php echo $signatureDate->format('d/m/Y'); ?> <br/>
    <?php if ($signatureDate <= new DateTime('now')): ?>
        <br/>
        <a class="linkButton" target="__blank" href="<?php echo URL\URLGenerator::generateFileURL('generate/generateProfessorWorkDocs.php', ['workSheetId' => $workSheetObject->id ]) ?>">Visualizar documentação</a>

        <h4>Aceite e assinatura</h4>
        <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'signworkdoc', null, [ 'workSheetId' => $workSheetObject->id ] ); ?>">
            <p>Após ler a documentação acima e conferir se seus dados estão corretos, marque as caixas abaixo para assinar cada documento e demonstrar seu
                consentimento de seus conteúdos. A assinatura será feita mediante autenticação via senha temporária enviada para seu e-mail.
            </p>
            <?php 
            $isAlreadySigned = function($docSignatureId) use ($workSheetObject)
            {
                foreach ($workSheetObject->_signatures as $sign)
                    if ($sign['docSignatureId'] === (int)$docSignatureId)
                        return true;
                return false;
            }; 

            $isEverythingSigned = function() use ($workSheetObject)
            {
                return count($workSheetObject->_signatures) === count($workSheetObject->_signaturesFields);
            };

            foreach ($workSheetObject->_signaturesFields as $sfi => $signField): ?>
                <label>
                    <input type="checkbox" name="signatureFieldIds[<?php echo $sfi; ?>]" <?php echo $isAlreadySigned($signField->docSignatureId) ? ' checked readonly disabled ' : ''; ?>
                    value="<?php echo $signField->docSignatureId ?? ''; ?>" /> <?php echo hsc($signField->signatureLabel ?? '(Documento não definido)') ?>
                </label><br/>
                <input type="hidden" name="signatureFieldNames[<?php echo $sfi; ?>]" value="<?php echo $signField->signatureLabel ?? '(Documento não definido)'; ?>" />
            <?php endforeach; ?>
            <input type="submit" name="btnsubmitPrepareSignatures" value="Assinar" <?php echo $isEverythingSigned() ? ' disabled ' : ''; ?> onclick="document.getElementById('divLoadingGifOTP').style.display = 'block';" />
            <div id="divLoadingGifOTP" style="display:none;">
                <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/loading.gif'); ?>" alt="Carregado, aguarde..."/>
            </div>

            <?php if ($isEverythingSigned()): ?>
                <p style="color:green;">Você assinou tudo!</p>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>