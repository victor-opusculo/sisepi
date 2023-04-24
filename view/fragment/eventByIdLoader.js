//#region Namespace SisEpi.Events
SisEpi.Events = SisEpi.Events || {};

SisEpi.Events.btnSearchEvent_onClick = function(e)
{
    var popup = window.open(SisEpi.Url.popupTemplateUrl.replace('{popup}', 'selectevent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
    popup.focus();
};

SisEpi.Events.loadEvent = async function(eventId)
{
    if (Number.isInteger(eventId))
    {
        try
        {
            let response = await fetch(SisEpi.Url.fileBaseUrl + '/generate/getEventInfos.php' + '?id=' + eventId);
            let json = await response.json();

            if (json.error) throw new Error(json.error);

            if (SisEpi.Events.setEventDataFunction)
                SisEpi.Events.setEventDataFunction(json.data);
            else
                throw new Error("Não foi possível carregar dados do evento!");
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
};

SisEpi.Events.btnLoadEvent_onClick = function(e)
{
    if (SisEpi.Events.getEventIdFunction)
        SisEpi.Events.loadEvent(Number(SisEpi.Events.getEventIdFunction()));
};

SisEpi.Events.setEventDataFunction = null;
SisEpi.Events.setEventIdFunction = null;
SisEpi.Events.getEventIdFunction = null;
SisEpi.Events.buttonLoadEvent = null;
SisEpi.Events.buttonSearchEvent = null;
//#endregion

function setEventIdInput(eventId)
{
if (SisEpi.Events.setEventIdFunction)
{
    SisEpi.Events.setEventIdFunction(Number(eventId));
    SisEpi.Events.loadEvent(Number(eventId));
}
}

function setUpEventByIdLoader(props)
{
SisEpi.Events.setEventDataFunction = props.setData;
SisEpi.Events.setEventIdFunction = props.setId;
SisEpi.Events.getEventIdFunction = props.getId;
SisEpi.Events.buttonLoadEvent = props.buttonLoad;
SisEpi.Events.buttonSearchEvent = props.buttonSearch;
}

window.addEventListener('load', function()
{
if (SisEpi.Events.buttonLoadEvent)
    SisEpi.Events.buttonLoadEvent.onclick = SisEpi.Events.btnLoadEvent_onClick;

if (SisEpi.Events.buttonSearchEvent)
    SisEpi.Events.buttonSearchEvent.onclick = SisEpi.Events.btnSearchEvent_onClick;
});