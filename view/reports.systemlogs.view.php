
<form method="get">
    <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
        <input type="hidden" name="cont" value="reports" />
        <input type="hidden" name="action" value="systemlogs" />
    <?php endif; ?>
    <span class="searchFormField">
        <label>Arquivo: 
            <select name="file">
                <?php foreach ($availableFiles as $file): ?>
                    <option <?= $_GET['file'] ?? "" === $file ? 'selected' : '' ?>><?= hsc(basename($file)) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <input type="submit" value="Visualizar" />
    </span>
    <span class="formField">
        <a class="linkButton" href="<?= URL\URLGenerator::generateFileURL('generate/generateLogZipArchive.php') ?>">Baixar logs em ZIP</a>
    </span>
</form>

<?php if (isset($dgComp)): ?>
    <h3><?= $_GET['file'] ?? "" ?></h3>
    <?php $dgComp->render(); ?>

<?php if (checkUserPermission("LOG", 2)): ?>
    <button type="button" onclick="if (document.getElementById('chkDeleteConfirm').checked) window.location.href = '<?= URL\URLGenerator::generateFileURL('post/reports.systemlogs_delete.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'file' => $_GET['file'] ]) ?>'; ">Excluir arquivo</button>
    <label><input type="checkbox" id="chkDeleteConfirm" />Confirmar exclusão</label>
<?php endif; ?>
<?php endif; ?>