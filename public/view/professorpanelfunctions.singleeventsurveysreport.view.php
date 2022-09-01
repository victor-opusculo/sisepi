
<script>
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
<style>
    .reportItemContainer
    {
        background-color: #eee;
        min-width: 250px;
        margin: 0.5em;
        border-radius: 10px;
        padding: 15px;
    }

    .reportItemContainerFlex
    {
        display: flex;
    }

    .reportItemContainer h3
    {
        text-align: center;
        margin: 0.3em;
    }

    .reportItemChart { flex: 70%;   }
    .reportItemInfos { flex: 30%; }

    .reportItemInfos .reportItemInfosLabel { font-weight: bold; }
    .reportItemInfos .reportItemInfosDataRowContainer { display: block; line-height: 1.5em;}

    .reportItemInfos .reportItemInfosTotal { font-style: italic; }

    @media all and (max-width: 749px)
    {
        .reportItemContainerFlex { display: block; }
    }
</style>

<?php if (isset($eventObj)): ?>
<div class="viewDataFrame">
    <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventObj->id); ?>"><?php echo hsc($eventObj->name); ?></a>
</div>
<?php endif; ?>

<?php 
if (isset($reportObj))
{
    foreach ($reportObj->getReportItemsHTML() as $html)
    {
        echo $html;
    }
}
?>