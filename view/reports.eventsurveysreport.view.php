
<script>
    const popupURL = '<?php echo URL\URLGenerator::generatePopupURL('{popup}'); ?>';
    const getEventInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getEventInfos.php'); ?>';

    function escapeHtml(unsafe) 
    {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function setEventIdInput(eventId)
    {
        if (eventId)
        {
            let existentIds = [...document.querySelectorAll('input[name="eventIds[]"]')].find( input => input.value == eventId );
            if (!existentIds)
                fetch(getEventInfosScriptURL + '?id=' + eventId).then( res => res.json() ).then( json => applyEventInfos(json) );
        }
    }

    function applyEventInfos(responseObj)
    {
        if (!responseObj.error)
        {
            let newTr = 
            `<tr>
                <td>${responseObj.data.id}</td>
                <td>${escapeHtml(responseObj.data.name)}</td>
                <td>
                    <button type="button" style="min-width: 20px;" onclick="document.getElementById('tbodyAddedEvents').removeChild(this.parentNode.parentNode);">&times;</button>
                    <input type="hidden" name="eventIds[]" value="${responseObj.data.id}" />
                </td>
            </tr>`;
            
            document.getElementById('tbodyAddedEvents').insertAdjacentHTML('beforeend', newTr);
        }
        else
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, responseObj.error);
    }

    function btnAddEventId_onClick(e)
    {
        var txtId = document.getElementById('txtEventIdToAdd').value;
        setEventIdInput(txtId);
    }

    function btnSearchEvent_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectevent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	    popup.focus();
    }

    window.addEventListener('load', function()
    {
        document.getElementById('btnSearchEvent').onclick = btnSearchEvent_onClick;
        document.getElementById('btnAddEventId').onclick = btnAddEventId_onClick;
    });

    window.addEventListener('resize', function()
    {
        if (chart_chart0)
        {
            let currchartNumber = 0;
            let currentChart = chart_chart0;
            while (currentChart)
            {
                currentChart.resize();
                currentChart = window['chart_chart' + (++currchartNumber)];
            }
        }
    });
</script>

<form method="get">
    <span class="searchFormField">
        <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
            <input type="hidden" name="cont" value="reports" />
            <input type="hidden" name="action" value="eventsurveysreport" />
        <?php endif; ?>
        <label>Evento ID: <input type="number" step="1" style="width: 150px;" id="txtEventIdToAdd" /></label>
        <button type="button" id="btnAddEventId" style="min-width: 20px;"><?php echo hsc('+'); ?></button>
        <button type="button" id="btnSearchEvent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
    </span>
    <table>
        <thead>
            <tr>
                <th class="shrinkCell">ID</th><th>Evento</th><th class="shrinkCell"></th>
            </tr>
        </thead>
        <tbody id="tbodyAddedEvents">
            <?php if (isset($loadedEvents))
            foreach ($loadedEvents as $ev): ?>
                <tr>
                    <td><?= $ev->id ?></td>
                    <td><?= hsc($ev->name) ?></td>
                    <td>
                        <button type="button" style="min-width: 20px;" onclick="document.getElementById('tbodyAddedEvents').removeChild(this.parentNode.parentNode);">&times;</button>
                        <input type="hidden" name="eventIds[]" value="<?= $ev->id ?>" />
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="submit" name="btnsubmitViewReport" value="Visualizar" />
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