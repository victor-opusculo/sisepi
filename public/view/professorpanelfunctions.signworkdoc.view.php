<?php
if ($operation === "verifyotp"): ?>
<?php if (isset($workSheetObj)): ?>
    <div class="viewDataFrame">
        <p style="text-align: center;">Você está assinando os seguintes documentos da seguinte atividade de docente: </p>
        <label>Atividade: </label><?php echo $workSheetObj->participationEventDataJson->activityName; ?> <br/>
        <label>Datas:</label><?php echo $workSheetObj->participationEventDataJson->dates; ?> <br/>
        <label>Horário:</label><?php echo $workSheetObj->participationEventDataJson->times; ?> <br/>
        <br/>
        <ul>
        <?php 
        $signatureDocNames = array_filter($_POST['signatureFieldNames'] ?? [], fn($docnIndex) => array_key_exists($docnIndex, $_POST['signatureFieldIds']), ARRAY_FILTER_USE_KEY);
        foreach ($signatureDocNames as $fn): ?>
            <li><?php echo $fn; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>

    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'signworkdoc', null, [ 'workSheetId' => $workSheetObj->id ] ); ?>">
        <div class="centControl">    
            <span class="formField">
                <label>Insira a senha temporária enviada para o seu e-mail: <input type="text" size="20" pattern="[0-9]{6}" name="givenOTP"/></label>
                <input style="vertical-align: middle;" type="submit" name="btnsubmitSubmitOTPforSignature" value="Validar" />
            </span>
            <?php if ($wrongOTP): ?>
                <p style="color: red;">Senha incorreta!</p>
            <?php endif; ?>
            <input type="hidden" name="otpId" value="<?php echo $otpId; ?>" />
            <input type="hidden" name="workSheetId" value="<?php echo $workSheetObj->id; ?>" />
            <?php foreach ($_POST['signatureFieldNames'] as $fn): ?>
                <input type="hidden" name="signatureFieldNames[]" value="<?php echo $fn; ?>"/>
            <?php endforeach; ?>
            <?php foreach ($_POST['signatureFieldIds'] as $fid): ?>
                <input type="hidden" name="signatureFieldIds[]" value="<?php echo $fid; ?>"/>
            <?php endforeach; ?>
        </div>
    </form>
<?php endif; ?>

<?php elseif ($operation === "postsign"): ?>

    <div class="centControl">
        <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'viewprofworkproposal', $workSheetObj->professorWorkProposalId) ?>">Voltar à pagina da proposta</a>
    </div>

<?php elseif ($operation === "error"): ?>

    <form class="centControl" method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'signworkdoc', null, [ 'workSheetId' => $workSheetObj->id ] ); ?>">
        <?php foreach ($_POST['signatureFieldNames'] as $fn): ?>
            <input type="hidden" name="signatureFieldNames[]" value="<?php echo $fn; ?>"/>
        <?php endforeach; ?>
        <?php foreach ($_POST['signatureFieldIds'] as $fid): ?>
            <input type="hidden" name="signatureFieldIds[]" value="<?php echo $fid; ?>"/>
        <?php endforeach; ?>
        <input type="hidden" name="workSheetId" value="<?php echo $workSheetObj->id; ?>" />
    
        <input type="submit" name="btnsubmitSendOTPAgain" value="Enviar nova senha" />
    </form>

<?php endif; ?>