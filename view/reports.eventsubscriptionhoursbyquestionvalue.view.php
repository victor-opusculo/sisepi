<form method="get">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
            <input type="hidden" name="cont" value="reports" />
            <input type="hidden" name="action" value="eventsubscriptionhoursbyquestionvalue" />
        <?php endif; ?>
        <span class="formField">
            <label>Resposta esperada em qualquer campo: <input type="text" name="questValue" size="60" maxlength="255" list="enums" value="<?= hscq($_GET['questValue'] ?? '') ?>" required/></label>
        </span>
        <span class="formField">
            <label>Eventos de <input type="date" name="begin" required value="<?= hscq($_GET['begin'] ?? '') ?>"/></label>
            <label> até <input type="date" name="end" required value="<?= hscq($_GET['end'] ?? '') ?>"/></label>
        </span>
        <datalist id="enums">
            <?php foreach ($enumsValues as $val): ?>
                <option><?= hsc($val) ?></option>
            <?php endforeach; ?>
        </datalist>
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