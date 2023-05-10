<form method="get">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
            <input type="hidden" name="cont" value="reports" />
            <input type="hidden" name="action" value="professorspaymentsumperiod" />
        <?php endif; ?>
        <span class="formField">
            <label>Fichas de trabalho de <input type="date" name="begin" required value="<?= hscq($_GET['begin'] ?? '') ?>"/></label>
            <label> até <input type="date" name="end" required value="<?= hscq($_GET['end'] ?? '') ?>"/></label>
        </span>
    </span>
    
    <input type="submit" name="btnsubmitViewReport" value="Visualizar" />
</form>
<br/>

<?php 
if (isset($reportObj))
{
    foreach ($reportObj->getReportItemsHTML() as $comp)
    {
        $comp->render();
    }
}
?>