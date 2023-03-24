
//#region Namespace SisEpi.VereadorMirim.Schools
    SisEpi.VereadorMirim = SisEpi.VereadorMirim || {};
    SisEpi.VereadorMirim.Schools = SisEpi.VereadorMirim.Schools || {};

    SisEpi.VereadorMirim.Schools.btnSearchSchool_onClick = function(e)
    {
        var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selectvmschool'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    };

    SisEpi.VereadorMirim.Schools.loadVmSchool = async function(schoolId)
    {
        if (Number.isInteger(schoolId))
        {
            try
            {
                let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getVmSchoolInfos.php' + '?id=' + schoolId);
                let json = await response.json();

                if (json.error) throw new Error(json.error);

                if (SisEpi.VereadorMirim.Schools.setSchoolDataFunction)
                    SisEpi.VereadorMirim.Schools.setSchoolDataFunction(json.data);
                else
                    throw new Error("Não foi possível carregar dados da escola!");
            }
            catch (err)
            {
                showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
            }
        }
    };

    SisEpi.VereadorMirim.Schools.btnLoadSchool_onClick = function(e)
    {
        if (SisEpi.VereadorMirim.Schools.getSchoolIdFunction)
            SisEpi.VereadorMirim.Schools.loadVmSchool(Number(SisEpi.VereadorMirim.Schools.getSchoolIdFunction()));
    };

    SisEpi.VereadorMirim.Schools.setSchoolDataFunction = null;
    SisEpi.VereadorMirim.Schools.setSchoolIdFunction = null;
    SisEpi.VereadorMirim.Schools.getSchoolIdFunction = null;
    SisEpi.VereadorMirim.Schools.buttonLoadSchool = null;
    SisEpi.VereadorMirim.Schools.buttonSearchSchool = null;
//#endregion

function setVmSchoolIdInput(schoolId)
{
    if (SisEpi.VereadorMirim.Schools.setSchoolIdFunction)
    {
        SisEpi.VereadorMirim.Schools.setSchoolIdFunction(Number(schoolId));
        SisEpi.VereadorMirim.Schools.loadVmSchool(Number(schoolId));
    }
}

function setUpSchoolByIdLoader(props)
{
    SisEpi.VereadorMirim.Schools.setSchoolDataFunction = props.setData;
    SisEpi.VereadorMirim.Schools.setSchoolIdFunction = props.setId;
    SisEpi.VereadorMirim.Schools.getSchoolIdFunction = props.getId;
    SisEpi.VereadorMirim.Schools.buttonLoadSchool = props.buttonLoad;
    SisEpi.VereadorMirim.Schools.buttonSearchSchool = props.buttonSearch;
}

window.addEventListener('load', function()
{
    if (SisEpi.VereadorMirim.Schools.buttonLoadSchool)
        SisEpi.VereadorMirim.Schools.buttonLoadSchool.onclick = SisEpi.VereadorMirim.Schools.btnLoadSchool_onClick;

    if (SisEpi.VereadorMirim.Schools.buttonSearchSchool)
        SisEpi.VereadorMirim.Schools.buttonSearchSchool.onclick = SisEpi.VereadorMirim.Schools.btnSearchSchool_onClick;
});