//#region Namespace SisEpi.Events.Tests.Templates

SisEpi.Events = SisEpi.Events || {};
SisEpi.Events.Tests = SisEpi.Events.Tests || {};
SisEpi.Events.Tests.Templates = SisEpi.Events.Tests.Templates || {};

SisEpi.Events.Tests.Templates.btnSearchTemplate_onClick = function(e)
{
    var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selecteventtesttemplate'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
    popup.focus();
};

SisEpi.Events.Tests.Templates.loadTemplate = async function(templateId)
{
    if (Number.isInteger(templateId))
    {
        try
        {
            let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getEventTestTemplateInfos.php' + '?id=' + templateId);
            let json = await response.json();

            if (json.error) throw new Error(json.error);

            if (SisEpi.Events.Tests.Templates.setTemplateDataFunction)
                SisEpi.Events.Tests.Templates.setTemplateDataFunction(json.data);
            else
                throw new Error("Não foi possível carregar dados do modelo de avaliação!");
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
};

SisEpi.Events.Tests.Templates.btnLoadTemplate_onClick = function(e)
{
    if (SisEpi.Events.Tests.Templates.getTemplateIdFunction)
        SisEpi.Events.Tests.Templates.loadTemplate(Number(SisEpi.Events.Tests.Templates.getTemplateIdFunction()));
};

SisEpi.Events.Tests.Templates.setTemplateDataFunction = null;
SisEpi.Events.Tests.Templates.setTemplateIdFunction = null;
SisEpi.Events.Tests.Templates.getTemplateIdFunction = null;
SisEpi.Events.Tests.Templates.buttonLoadTemplate = null;
SisEpi.Events.Tests.Templates.buttonSearchTemplate = null;
//#endregion

function setEventTestTemplateIdInput(templateId)
{
    if (SisEpi.Events.Tests.Templates.setTemplateIdFunction)
    {
        SisEpi.Events.Tests.Templates.setTemplateIdFunction(Number(templateId));
        SisEpi.Events.Tests.Templates.loadTemplate(Number(templateId));
    }
};

function setUpEventTestTemplateByIdLoader(props)
{
    SisEpi.Events.Tests.Templates.setTemplateDataFunction = props.setData;
    SisEpi.Events.Tests.Templates.setTemplateIdFunction = props.setId;
    SisEpi.Events.Tests.Templates.getTemplateIdFunction = props.getId;
    SisEpi.Events.Tests.Templates.buttonLoadTemplate = props.buttonLoad;
    SisEpi.Events.Tests.Templates.buttonSearchTemplate = props.buttonSearch;
}

window.addEventListener('load', function()
{
if (SisEpi.Events.Tests.Templates.buttonLoadTemplate)
    SisEpi.Events.Tests.Templates.buttonLoadTemplate.onclick = SisEpi.Events.Tests.Templates.btnLoadTemplate_onClick;

if (SisEpi.Events.Tests.Templates.buttonSearchTemplate)
    SisEpi.Events.Tests.Templates.buttonSearchTemplate.onclick = SisEpi.Events.Tests.Templates.btnSearchTemplate_onClick;
});