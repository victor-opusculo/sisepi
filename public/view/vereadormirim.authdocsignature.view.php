
<?php if ($showData): ?>
    <div class="viewDataFrame">
        <?php if (!empty($signatureObj)): ?>
            <div style="color: green; text-align: center; font-weight: bold; font-size: x-large; margin-bottom: 20px;">
                <span>Assinatura válida!</span>
            </div>
            <label>Tipo de termo/documento: </label><?= hsc($signatureObj->getOtherProperties()->documentTemplateName) ?> <br/>
            <label>Vinculado ao Vereador Mirim/Candidato: </label><?= hsc($signatureObj->getOtherProperties()->vmDocOwnerStudentName) ?> 
            <br/>
            <br/>
            <label>Tipo de signatário: </label><?= hsc($signatureObj->getSignerTypePtBr()) ?> <br/>
            <label>Nome: </label><?= hsc($signatureObj->getSignerName()) ?> <br/>
            <label>Campo: </label><?= $fieldLabel ?> 
            <br/>
            <br/>
            <label>Código da assinatura: </label><?= $signatureObj->id ?> <br/>
            <label>Data e horário de assinatura: </label><?= date_create($signatureObj->signatureDateTime)->format('d/m/Y H:i:s') ?>

        <?php else: ?>
            <div style="color: red; text-align: center; font-weight: bold; font-size: x-large;">
                <span>Assinatura inválida ou não existente!</span>
            </div>
        <?php endif; ?>
    </div>
<?php else: ?>

    <form method="get">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
            <input type="hidden" name="cont" value="<?= $_GET['cont'] ?>" />
            <input type="hidden" name="action" value="<?= $_GET['action'] ?>" />
        <?php endif; ?>
        <div class="centControl">Informe os dados que estão no campo da assinatura.</div>
        <span class="formField">
            <label>Código: <input type="number" min="1" step="1" name="code" required /></label>
        </span>
        <span class="formField">
            <label>Data e horário de assinatura:
                <input type="date" name="date" required />
                <input type="time" name="time" step="1" required />
            </label>
        </span>
        <input type="submit" value="Verificar" />
    </form>

<?php endif; ?>