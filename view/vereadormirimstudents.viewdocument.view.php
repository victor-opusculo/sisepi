
<div class="viewDataFrame">
    <label>Vereador mirim: </label><a href="<?= URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'view', $vmStudentObj->id) ?>"><?= hsc($vmStudentObj->name) ?></a> <br/>
    <label>ID deste documento: </label><?= $vmDocumentObj->id ?> <br/>
    <label>Modelo deste documento: </label><?= hsc($vmDocumentObj->getOtherProperties()->templateName) ?> <br/>
    <br/>
    <label>Assinaturas: </label>
    <?php if (count($signaturesFields) > 0): ?>
    <table>
        <thead>
            <tr>
                <?php foreach (array_keys($signaturesFields[0]) as $key): ?>
                    <th><?= hsc($key) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($signaturesFields as $field): ?>
                <tr>
                    <?php foreach ($field as $key => $value): ?>
                        <td data-th="<?= hscq($key) ?>"><?= $value ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <br/>
    <div class="rightControl">
        <a class="linkButton" href="<?= URL\URLGenerator::generateFileURL('generate/generateVmDocument.php', [ 'documentId' => $vmDocumentObj->id ])?>">Gerar PDF</a>
    </div>
</div>