
<form method="get">
    <span class="searchFormField">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
            <input type="hidden" name="cont" value="reports" />
            <input type="hidden" name="action" value="calendarperiodreport" />
        <?php endif; ?>
        <label>Período de <input type="date" name="begin" value="<?= hscq($_GET['begin'] ?? '') ?>"/></label>
        <label> até <input type="date" name="end" value="<?= hscq($_GET['end'] ?? '') ?>"/></label>
        <input type="submit" name="btnsubmitViewReport" value="Visualizar" />
    </span>
    
</form>

<br/>

<?php 
if (isset($reportObj))
{
    foreach ($reportObj->getReportItemsHTML() as $html)
    {
        echo $html;
    }
}
?>
<?php if (!empty($_GET['begin']) && !empty($_GET['end'])): ?>
<br/>
<div class="rightControl">
    <a class="linkButton" href="<?= URL\URLGenerator::generateFileURL('generate/calendarPeriodToXlsx.php', [ 'begin' => $_GET['begin'], 'end' => $_GET['end'] ] ) ?>">Exportar para XLSX</a>
</div>
<?php endif; ?>