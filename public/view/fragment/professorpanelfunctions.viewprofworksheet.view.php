<?php
$workSheetObject = $wso;
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
        $inssDiscountValue = 0;
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
        <span>Descontar e recolher INSS.</span> <br/>
        <label>Desconto de: </label><?php echo $workSheetObject->paymentInfosJson->inssPercent; ?>% <br/>
        <label>Valor a descontar: </label><?php echo hsc(
            formatDecimalToCurrency( $inssDiscountValue =
                ($paymentBaseValue + $paymentSubsAllowanceValue) * ($workSheetObject->paymentInfosJson->inssPercent / 100)
            )
        );
        ?>
    <?php else: ?>
        <span>Não recolher INSS.</span>
    <?php endif; ?>
    <h5>&#10152; Valores totais</h5>
    <label>Bruto (proventos): </label><?php echo formatDecimalToCurrency($paymentBaseValue + $paymentSubsAllowanceValue); ?> <br/>
    <label>Bruto menos INSS: </label><?php echo formatDecimalToCurrency($paymentBaseValue + $paymentSubsAllowanceValue - $inssDiscountValue); ?>
    
    <div class="messageFrameWithIcon">
        <img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>
        O valor líquido a ser creditado em sua conta pode diferir do valor acima caso você some, dentro de um mês, remunerações altas o suficiente para a cobrança de imposto de renda.
    </div>

    <h3>Certificado de docente</h3>
    <?php if (!empty($workSheetObject->professorCertificateText)): ?>
        <p>Disponível a partir de <?php echo date_create($workSheetObject->signatureDate)->format('d/m/Y'); ?>.</p>
        <?php if (date_create($workSheetObject->signatureDate) <= new DateTime('now')): ?>
            <a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL('generate/generateProfessorCertificate.php', [ 'workSheetId' => $workSheetObject->id ]); ?>">Gerar certificado</a>
        <?php endif; ?>
    <?php else: ?>
        </p>Não disponível</p>
    <?php endif; ?>

    <h3>Assinaturas</h3>
    <label>Assinaturas liberadas a partir de: </label><?php echo date_create($workSheetObject->signatureDate)->format('d/m/Y'); ?>
</div>