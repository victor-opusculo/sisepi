
//#region Namespace SisEpi.VereadorMirim.Legislatures
    SisEpi.VereadorMirim = SisEpi.VereadorMirim || {};
    SisEpi.VereadorMirim.Legislatures = SisEpi.VereadorMirim.Legislatures || {};

    SisEpi.VereadorMirim.Legislatures.btnSearchLegislature_onClick = function(e)
    {
        var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selectvmlegislature'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    };

    SisEpi.VereadorMirim.Legislatures.loadVmLegislature = async function(legislatureId)
    {
        if (Number.isInteger(legislatureId))
        {
            try
            {
                let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getVmLegislatureInfos.php' + '?id=' + legislatureId);
                let json = await response.json();

                if (json.error) throw new Error(json.error);

                if (SisEpi.VereadorMirim.Legislatures.setLegislatureDataFunction)
                    SisEpi.VereadorMirim.Legislatures.setLegislatureDataFunction(json.data);
                else
                    throw new Error("Não foi possível carregar dados da legislatura!");
            }
            catch (err)
            {
                showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
            }
        }
    };

    SisEpi.VereadorMirim.Legislatures.btnLoadLegislature_onClick = function(e)
    {
        if (SisEpi.VereadorMirim.Legislatures.getLegislatureIdFunction)
            loadVmLegislature(Number(SisEpi.VereadorMirim.Legislatures.getLegislatureIdFunction()));
    };

    SisEpi.VereadorMirim.Legislatures.setLegislatureDataFunction = null;
    SisEpi.VereadorMirim.Legislatures.setLegislatureIdFunction = null;
    SisEpi.VereadorMirim.Legislatures.getLegislatureIdFunction = null;
    SisEpi.VereadorMirim.Legislatures.buttonLoadLegislature = null;
    SisEpi.VereadorMirim.Legislatures.buttonSearchLegislature = null;
//#endregion

function setVmLegislatureIdInput(legislatureId)
{
    if (SisEpi.VereadorMirim.Legislatures.setLegislatureIdFunction)
    {
        SisEpi.VereadorMirim.Legislatures.setLegislatureIdFunction(Number(legislatureId));
        SisEpi.VereadorMirim.Legislatures.loadVmLegislature(Number(legislatureId));
    }
}

function setUpLegislatureByIdLoader(props)
{
    SisEpi.VereadorMirim.Legislatures.setLegislatureDataFunction = props.setData;
    SisEpi.VereadorMirim.Legislatures.setLegislatureIdFunction = props.setId;
    SisEpi.VereadorMirim.Legislatures.getLegislatureIdFunction = props.getId;
    SisEpi.VereadorMirim.Legislatures.buttonLoadLegislature = props.buttonLoad;
    SisEpi.VereadorMirim.Legislatures.buttonSearchLegislature = props.buttonSearch;
}

window.addEventListener('load', function()
{
    if (SisEpi.VereadorMirim.Legislatures.buttonLoadLegislature)
        SisEpi.VereadorMirim.Legislatures.buttonLoadLegislature.onclick = SisEpi.VereadorMirim.Legislatures.btnLoadLegislature_onClick;

    if (SisEpi.VereadorMirim.Legislatures.buttonSearchLegislature)
        SisEpi.VereadorMirim.Legislatures.buttonSearchLegislature.onclick = SisEpi.VereadorMirim.Legislatures.btnSearchLegislature_onClick;
});