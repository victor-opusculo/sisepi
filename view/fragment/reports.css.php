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

    .reportItemChart { flex: 70%; }
    .reportItemInfos { flex: 30%; }

    .reportItemInfos .reportItemInfosLabel { font-weight: bold; }
    .reportItemInfos .reportItemInfosDataRowContainer { display: block; line-height: 1.5em;}

    .reportItemInfos .reportItemInfosTotal { font-style: italic; }

    .collapsibleReportSection { display: none; }
    input[type='checkbox']:checked + label + div.collapsibleReportSection { display: block; }

    .hover-text { text-decoration: underline dotted darkgray; position: relative; }

    .tooltip-text 
    {
        left: 0;
        top: 0;
        visibility: hidden;
        position: absolute;
        z-index: 2;
        min-width: 200px;
        max-width: 100vw;
        color: white;
        font-size: 0.8em;
        font-weight: normal;
        background-color: #192733;
        border-radius: 8px;
        padding: 5px 10px 5px 10px;
        text-decoration: none;
    }

    .hover-text:hover .tooltip-text 
    {
        visibility: visible;
    }

    @media all and (max-width: 749px)
    {
        .reportItemContainerFlex { display: block; }
    }

    @media print
    {
        .reportItemContainer { page-break-inside: avoid; }
    }
</style>