
function regularCheck_onChange(e, self)
{
    sendChangedItemValue(self.getAttribute('data-checklist-path'), self.checked ? String(self.value) : '0');
}

function doesNotApplyCheck_onChange(e, self)
{
    var parentNode = self.parentNode.parentNode;
    if (self.checked)
    {
        parentNode.querySelector('.regularCheck').disabled = true;
        sendChangedItemValue(self.getAttribute('data-checklist-path'), String(self.value));
    }
    else
    {
        let regularCheck = parentNode.querySelector('.regularCheck');
        regularCheck.disabled = false;
        sendChangedItemValue(self.getAttribute('data-checklist-path'), regularCheck.checked ? String(regularCheck.value) : '0');
    }
}

function btnSaveTextInputValue_onClick(e, self)
{
    var textInput = self.parentNode.querySelector('.textInput');
    sendChangedItemValue(textInput.getAttribute('data-checklist-path'), textInput.value);
}

function sendChangedItemValue(itemPath, value)
{
    var json = JSON.stringify(
        { 
            checklistId: document.getElementById("checklistId").value, 
            itemPath: itemPath,
            value: value
        });

    fetch(postURL, 
        {
            method: 'POST',
            headers:
            {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: json
        }).then( res => res.json() ).then( jsonRes => showPostResponse(jsonRes) );
}

function showPostResponse(json)
{
    var obj = json;

    if (obj)
    {
        if (obj.success)
            showBottomScreenMessageBox(BottomScreenMessageBoxType.success, obj.message);
        else
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, obj.message);
    }
    else
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, 'Erro ao obter resposta do servidor.');
}