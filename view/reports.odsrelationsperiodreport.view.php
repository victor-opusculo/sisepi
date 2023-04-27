<form method="get">
    <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
        <input type="hidden" name="cont" value="reports" />
        <input type="hidden" name="action" value="odsrelationsperiodreport" />
    <?php endif; ?>
    <span class="searchFormField">
        <label>Exercício: <input type="number" name="year" min="2000" step="1" value="<?= date('Y') ?>" required /></label>
        <input type="submit" name="btnsubmitViewReport" value="Visualizar" />
    </span>
</form>

<?php require_once "view/fragment/reports.css.php"; ?>

<?php 
if (isset($reportObj))
{
    foreach ($reportObj->getReportItemsHTML() as $html)
    {
        echo $html;
    }
}
?>