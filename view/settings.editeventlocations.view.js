const locationsChangesReport = { create: null, update: null, delete: [] };

function btnAddNewLocation_onClick(e)
{
    var trNewLocationBlueprint = document.getElementById("trNewLocation").cloneNode(true);
    trNewLocationBlueprint.id = null;
    trNewLocationBlueprint.querySelector(".btnRemoveLocation").onclick = btnRemoveLocation_onClick;

    document.getElementById("tbodyLocationRows").appendChild(trNewLocationBlueprint);
}

function btnRemoveLocation_onClick(e)
{
    var tr = this.parentNode.parentNode;

    document.getElementById("tbodyLocationRows").removeChild(tr);

    if (tr.getAttribute("data-id"))
        locationsChangesReport.delete.push( { id: tr.getAttribute("data-id") } );
}

function btnsubmitSubmitLocations_onClick(e)
{
    generateChangesReport();
}

function generateChangesReport()
{
    locationsChangesReport.create = [];
    locationsChangesReport.update = [];

    document.getElementById("tbodyLocationRows").querySelectorAll("tr").forEach( tr =>
    {
        if (!tr.getAttribute("data-id"))
        {
            let createReg = {};
            createReg.name = tr.querySelector(".txtLocationName").value;
            createReg.type = tr.querySelector(".selLocationType").value;
            createReg.calendarInfoBoxStyleJson = JSON.stringify(
                {
                    backgroundColor: tr.querySelector(".colorStyleBgColor").value,
                    textColor: tr.querySelector(".colorStyleTextColor").value
                });
            
            locationsChangesReport.create.push(createReg);
        }
        else
        {
            let updateReg = {};
            updateReg.id = tr.getAttribute("data-id");
            updateReg.name = tr.querySelector(".txtLocationName").value;
            updateReg.type = tr.querySelector(".selLocationType").value;
            updateReg.calendarInfoBoxStyleJson = JSON.stringify(
                {
                    backgroundColor: tr.querySelector(".colorStyleBgColor").value,
                    textColor: tr.querySelector(".colorStyleTextColor").value
                });
            
            locationsChangesReport.update.push(updateReg);
        }
    });

    document.getElementById("locationChangesReport").value = JSON.stringify(locationsChangesReport);
}

window.addEventListener("load", function(e)
{
    this.document.getElementById("btnsubmitSubmitLocations").onclick = btnsubmitSubmitLocations_onClick;
    this.document.getElementById("btnAddNewLocation").onclick = btnAddNewLocation_onClick;
    this.document.querySelectorAll(".btnRemoveLocation").forEach( btn => btn.onclick = btnRemoveLocation_onClick );
});