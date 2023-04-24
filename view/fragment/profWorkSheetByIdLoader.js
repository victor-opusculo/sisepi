//#region Namespace SisEpi.Professors
SisEpi.Professors = SisEpi.Professors || {};
SisEpi.Professors.WorkSheets = SisEpi.Professors.WorkSheets || {};

SisEpi.Professors.WorkSheets.btnSearchWorkSheet_onClick = function(e)
{
    var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selectprofessorworksheet'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
    popup.focus();
};

SisEpi.Professors.WorkSheets.loadWorkSheet = async function(workSheetId)
{
    if (Number.isInteger(workSheetId))
    {
        try
        {
            let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getProfWorkSheetInfos.php' + '?id=' + workSheetId);
            let json = await response.json();

            if (json.error) throw new Error(json.error);

            if (SisEpi.Professors.WorkSheets.setWorkSheetDataFunction)
                SisEpi.Professors.WorkSheets.setWorkSheetDataFunction(json.data);
            else
                throw new Error("Não foi possível carregar dados da ficha de trabalho!");
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
};

SisEpi.Professors.WorkSheets.btnLoadWorkSheet_onClick = function(e)
{
    if (SisEpi.Professors.WorkSheets.getWorkSheetIdFunction)
        SisEpi.Professors.WorkSheets.loadWorkSheet(Number(SisEpi.Professors.WorkSheets.getWorkSheetIdFunction()));
};

SisEpi.Professors.WorkSheets.setWorkSheetDataFunction = null;
SisEpi.Professors.WorkSheets.setWorkSheetIdFunction = null;
SisEpi.Professors.WorkSheets.getWorkSheetIdFunction = null;
SisEpi.Professors.WorkSheets.buttonLoadWorkSheet = null;
SisEpi.Professors.WorkSheets.buttonSearchWorkSheet = null;
//#endregion

function setWorkSheetIdInput(workSheetId)
{
    if (SisEpi.Professors.WorkSheets.setWorkSheetIdFunction)
    {
        SisEpi.Professors.WorkSheets.setWorkSheetIdFunction(Number(workSheetId));
        SisEpi.Professors.WorkSheets.loadWorkSheet(Number(workSheetId));
    }
};

function setUpWorkSheetByIdLoader(props)
{
    SisEpi.Professors.WorkSheets.setWorkSheetDataFunction = props.setData;
    SisEpi.Professors.WorkSheets.setWorkSheetIdFunction = props.setId;
    SisEpi.Professors.WorkSheets.getWorkSheetIdFunction = props.getId;
    SisEpi.Professors.WorkSheets.buttonLoadWorkSheet = props.buttonLoad;
    SisEpi.Professors.WorkSheets.buttonSearchWorkSheet = props.buttonSearch;
}

window.addEventListener('load', function()
{
if (SisEpi.Professors.WorkSheets.buttonLoadWorkSheet)
    SisEpi.Professors.WorkSheets.buttonLoadWorkSheet.onclick = SisEpi.Professors.WorkSheets.btnLoadWorkSheet_onClick;

if (SisEpi.Professors.WorkSheets.buttonSearchWorkSheet)
    SisEpi.Professors.WorkSheets.buttonSearchWorkSheet.onclick = SisEpi.Professors.WorkSheets.btnSearchWorkSheet_onClick;
});