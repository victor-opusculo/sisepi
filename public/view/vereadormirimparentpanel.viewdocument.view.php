<?php include 'view/fragment/vereadormirimparents.logoutlink.view.php'; ?>

<?php if (isset($vmStudentObj, $vmDocumentObj)): ?>
<div class="viewDataFrame">
    <label>Vereador mirim: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirimparentpanel', 'viewvmstudent', $vmStudentObj->id) ?>"><?= hsc($vmStudentObj->name) ?></a> <br/>
    <label>ID deste documento: </label><?= $vmDocumentObj->id ?> <br/>
    <label>Modelo deste documento: </label><?= hsc($vmDocumentObj->getOtherProperties()->templateName) ?> <br/>
    <br/>

    <label>Visualização e Assinaturas: </label>
    
    <?php if (date_create($vmDocumentObj->signatureDate) <= new DateTime()): ?>
        <div class="centControl">
            <a class="linkButton" href="<?= URL\URLGenerator::generateFileURL('generate/generateVmDocument.php', [ 'documentId' => $vmDocumentObj->id ])?>">Visualizar PDF</a>
        </div>
        <?php if (isset($signaturesFields) && count($signaturesFields) > 0): ?>
            <form method="post" action="<?= URL\URLGenerator::generateSystemURL('vereadormirimparentpanel', 'signdocument', $vmDocumentObj->id) ?>">
                <p>Leia o termo/documento clicando no botão acima. Estando de acordo com tudo, marque as caixas abaixo para assinar cada campo deste documento em que você é o signatário.
                    Depois, clique no botão "Assinar". A assinatura será feita mediante autenticação via senha temporária enviada para seu e-mail.</p>
                <?php foreach ($signaturesFields as $key => $field): ?>
                    <label><input type="checkbox" name="signatureFieldIds[<?= $key ?>]" value="<?= $field['docSignatureId'] ?>" <?= $field['signed'] ? ' checked readonly disabled ' : '' ?>/> <?= $field['label'] ?></label>
                    <input type="hidden" name="signatureFieldNames[<?= $key ?>]" value="<?= $field['label'] ?>" />
                <?php endforeach; ?>

                <?php
                    $isEverythingSigned = function() use ($signaturesFields)
                    {
                        return array_reduce($signaturesFields, fn($carry, $field) => $field['signed'] && $carry, true);
                    };
                ?>
                <br/>
                <input type="submit" name="btnsubmitPrepareSignatures" value="Assinar" <?php echo $isEverythingSigned() ? ' disabled ' : ''; ?> onclick="document.getElementById('divLoadingGifOTP').style.display = 'block';" />
                <div id="divLoadingGifOTP" style="display:none;">
                    <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/loading.gif'); ?>" alt="Carregado, aguarde..."/>
                </div>
            </form>
        <?php else: ?>
            <p>Não há campos de assinatura neste termo/documento.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Visualização e assinatura liberadas somente a partir do dia <?= date_create($vmDocumentObj->signatureDate)->format('d/m/Y') ?></p>
    <?php endif; ?>
</div>

<?php endif; ?>