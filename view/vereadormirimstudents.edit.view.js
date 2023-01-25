
function btnSearchLegislature_onClick(e)
{
    var popup = window.open(popupURL.replace('{popup}', 'selectvmlegislature'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}

async function loadVmLegislature(legislatureId)
{
    if (Number.isInteger(legislatureId))
    {
        try
        {
            let response = await fetch(getLegislatureInfosScriptURL + '?id=' + legislatureId);
            let json = await response.json();

            if (json.error) throw new Error(json.error);

            document.getElementById("spanLegislatureName").innerText = json.data.name;
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
}

function btnLoadLegislature_onClick(e)
{
    loadVmLegislature(Number(document.getElementById('inputLegislatureId').value));
}

function setVmLegislatureIdInput(legislatureId)
{
    document.getElementById('inputLegislatureId').value = legislatureId;
    loadVmLegislature(Number(legislatureId));
}

window.addEventListener('load', function()
{
    this.document.getElementById("btnLoadLegislature").onclick = btnLoadLegislature_onClick;
    this.document.getElementById("btnSearchLegislature").onclick = btnSearchLegislature_onClick;
});