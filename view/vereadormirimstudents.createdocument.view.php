<?php if (isset($vmStudentObj, $vmDocumentTemplates)): ?>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimstudents.createdocument.post.php', [ 'cont' => $_GET['cont'], 'action' => 'view', 'id' => $vmStudentObj->id ]) ?>">
    <span class="formField">
        <label>Modelo de documento:
            <select name="vmdocuments:selTemplateId" required>
                <?php foreach ($vmDocumentTemplates as $t): ?>
                    <option value="<?= $t->id ?>"><?= $t->name ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Data de assinatura: <input type="date" name="vmdocuments:dateSignature" required/></label>
    </span>
    <div class="centControl">
        <input type="hidden" name="vmdocuments:hidVmStudentId" value="<?= $_GET['vmStudentId'] ?>"/>
        <input type="submit" name="btnsubmitSubmitDocument" value="Criar documento" />
    </div>
</form>

<?php endif; ?>