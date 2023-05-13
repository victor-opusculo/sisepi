//#region Namespace SisEpi.Ods
SisEpi.Ods = SisEpi.Ods || {};
SisEpi.Ods.OdsRelations = SisEpi.Ods.OdsRelations || {};

SisEpi.Ods.OdsRelations.btnSearchOdsRelation_onClick = function(e)
{
    var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selectodsrelation'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
    popup.focus();
};

SisEpi.Ods.OdsRelations.loadOdsRelation = async function(odsRelationId)
{
    if (Number.isInteger(odsRelationId))
    {
        try
        {
            let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getOdsRelationInfos.php' + '?id=' + odsRelationId);
            let json = await response.json();

            if (json.error) throw new Error(json.error);

            if (SisEpi.Ods.OdsRelations.setOdsRelationDataFunction)
                SisEpi.Ods.OdsRelations.setOdsRelationDataFunction(json.data);
            else
                throw new Error("Não foi possível carregar dados da relação ODS!");
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
};

SisEpi.Ods.OdsRelations.btnLoadOdsRelation_onClick = function(e)
{
    if (SisEpi.Ods.OdsRelations.getOdsRelationIdFunction)
        SisEpi.Ods.OdsRelations.loadOdsRelation(Number(SisEpi.Ods.OdsRelations.getOdsRelationIdFunction()));
};

SisEpi.Ods.OdsRelations.setOdsRelationDataFunction = null;
SisEpi.Ods.OdsRelations.setOdsRelationIdFunction = null;
SisEpi.Ods.OdsRelations.getOdsRelationIdFunction = null;
SisEpi.Ods.OdsRelations.buttonLoadOdsRelation = null;
SisEpi.Ods.OdsRelations.buttonSearchOdsRelation = null;
//#endregion

function setOdsRelationIdInput(workSheetId)
{
    if (SisEpi.Ods.OdsRelations.setOdsRelationIdFunction)
    {
        SisEpi.Ods.OdsRelations.setOdsRelationIdFunction(Number(workSheetId));
        SisEpi.Ods.OdsRelations.loadOdsRelation(Number(workSheetId));
    }
};

function setUpOdsRelationByIdLoader(props)
{
    SisEpi.Ods.OdsRelations.setOdsRelationDataFunction = props.setData;
    SisEpi.Ods.OdsRelations.setOdsRelationIdFunction = props.setId;
    SisEpi.Ods.OdsRelations.getOdsRelationIdFunction = props.getId;
    SisEpi.Ods.OdsRelations.buttonLoadOdsRelation = props.buttonLoad;
    SisEpi.Ods.OdsRelations.buttonSearchOdsRelation = props.buttonSearch;
}

window.addEventListener('load', function()
{
if (SisEpi.Ods.OdsRelations.buttonLoadOdsRelation)
    SisEpi.Ods.OdsRelations.buttonLoadOdsRelation.onclick = SisEpi.Ods.OdsRelations.btnLoadOdsRelation_onClick;

if (SisEpi.Ods.OdsRelations.buttonSearchOdsRelation)
    SisEpi.Ods.OdsRelations.buttonSearchOdsRelation.onclick = SisEpi.Ods.OdsRelations.btnSearchOdsRelation_onClick;
});