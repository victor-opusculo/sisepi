function changeItemTypeToCheck(itemDiv)
{
    let optionalCheckboxBlock = itemDiv.querySelector(".eventCheckListItem_optionalCheckBoxBlock");
    if (!optionalCheckboxBlock.children[0])
    {
        for (const child of document.getElementById("itemOptionalCheckbox").children)
            optionalCheckboxBlock.appendChild(child.cloneNode(true));
    }

    let responsibleUserBlock = itemDiv.querySelector(".eventCheckListItem_responsibleUserBlock");
    if (!responsibleUserBlock.children[0])
    {
        for (const child of document.getElementById("itemResponsibleUser").children)
            responsibleUserBlock.appendChild(child.cloneNode(true));
    }

    let subChecklistBlock = itemDiv.querySelector(".eventChecklistItem_subChecklist");
    while (subChecklistBlock.firstChild) subChecklistBlock.removeChild(subChecklistBlock.firstChild);
}

function changeItemTypeToCheckList(itemDiv)
{
    itemDiv.querySelector("input[name='itemValue']").value = '';

    let optionalCheckboxBlock = itemDiv.querySelector(".eventCheckListItem_optionalCheckBoxBlock");
    while (optionalCheckboxBlock.firstChild) optionalCheckboxBlock.removeChild(optionalCheckboxBlock.firstChild);

    let responsibleUserBlock = itemDiv.querySelector(".eventCheckListItem_responsibleUserBlock");
    if (!responsibleUserBlock.children[0])
    {
        for (const child of document.getElementById("itemResponsibleUser").children)
            responsibleUserBlock.appendChild(child.cloneNode(true));
    }

    let subChecklistBlock = itemDiv.querySelector(".eventChecklistItem_subChecklist");
    while (subChecklistBlock.firstChild) subChecklistBlock.removeChild(subChecklistBlock.firstChild);

    for (const child of document.getElementById("itemSubChecklist").children)
    {
        let cloned = child.cloneNode(true);
        cloned.querySelectorAll("input[type='text']").forEach( input => input.required = true );
        subChecklistBlock.appendChild(cloned);
    }
}

function changeItemTypeToCheckListWithResponsibleUser(itemDiv)
{
    itemDiv.querySelector("input[name='itemValue']").value = '';

    let optionalCheckboxBlock = itemDiv.querySelector(".eventCheckListItem_optionalCheckBoxBlock");
    while (optionalCheckboxBlock.firstChild) optionalCheckboxBlock.removeChild(optionalCheckboxBlock.firstChild);

    let responsibleUserBlock = itemDiv.querySelector(".eventCheckListItem_responsibleUserBlock");
    while (responsibleUserBlock.firstChild) responsibleUserBlock.removeChild(responsibleUserBlock.firstChild);

    let subChecklistBlock = itemDiv.querySelector(".eventChecklistItem_subChecklist");
    while (subChecklistBlock.firstChild) subChecklistBlock.removeChild(subChecklistBlock.firstChild);

    for (const child of document.getElementById("itemSubChecklistWithResponsibleUser").children)
    {
        let cloned = child.cloneNode(true);
        cloned.querySelectorAll("input[type='text']").forEach( input => input.required = true );
        subChecklistBlock.appendChild(cloned);
    }
}

function addSubItem(itemOl)
{
    let subItemElements = document.getElementById("itemSubChecklist").querySelector("li").cloneNode(true);
    subItemElements.querySelectorAll("input[type='text']").forEach( input => input.required = true );
    itemOl.appendChild(subItemElements);
}

function addSubItemWithResponsibleUser(itemOl)
{
    let subItemElements = document.getElementById("itemSubChecklistWithResponsibleUser").querySelector("li").cloneNode(true);
    subItemElements.querySelectorAll("input[type='text']").forEach( input => input.required = true );
    itemOl.appendChild(subItemElements);
}

function btnMoveItemUp_onClick(self)
{
    let itemDiv = self.parentElement;
    let sectionDiv = itemDiv.parentNode;

    if (itemDiv.previousElementSibling)
        sectionDiv.insertBefore(itemDiv, itemDiv.previousElementSibling);
}

function btnMoveItemDown_onClick(self)
{
    let itemDiv = self.parentElement;
    let sectionDiv = itemDiv.parentNode;

    if (itemDiv.nextElementSibling)
        sectionDiv.insertBefore(itemDiv.nextElementSibling, itemDiv);
}

function cmbItemType_onInput(self)
{
    switch(self.value)
    {
        case "text":
        case "check":
            changeItemTypeToCheck(self.parentNode.parentNode.parentNode);
            break;
        case "checkList":
            changeItemTypeToCheckList(self.parentNode.parentNode.parentNode);
            break;
        case "checkListWithResponsibleUser":
            changeItemTypeToCheckListWithResponsibleUser(self.parentNode.parentNode.parentNode);
            break;
    }
}

function btnDeleteSubItem_onClick(self)
{
    let subItemLi = self.parentNode;
    let subItemsOl = subItemLi.parentNode;

    if (subItemsOl.children.length > 1)
        subItemsOl.removeChild(subItemLi);
    else
        alert("É necessário haver pelo menos um subitem!");
}

function btnAddSubItem_onClick(self)
{
    let subItemsOl = self.parentNode.querySelector("ol");

    switch (self.parentNode.parentNode.querySelector("select[name='itemType']").value)
    {
        case "checkListWithResponsibleUser":
            addSubItemWithResponsibleUser(subItemsOl);
            break;
        case "checkList":
            addSubItem(subItemsOl);
            break;
    }
}

function btnNewItem_onClick(self)
{
    let blockDiv = self.previousElementSibling;

    for (const child of document.getElementById("itemTemplate").children)
    {
        let cloned = child.cloneNode(true);
        cloned.querySelectorAll("input[type='text']").forEach( input => input.required = true );
        blockDiv.appendChild(cloned);
    }
}

function btnDeleteItem_onClick(self)
{
    let itemDiv = self.parentElement;
    let blockDiv = itemDiv.parentElement;

    blockDiv.removeChild(itemDiv);
}

function generateChecklistJson()
{
    function getInputValueByName(parentNode, nodeName, nameAttr)
    {
        let input = parentNode.querySelector(`${nodeName}[name='${nameAttr}']`);
        if (input)
        {
            if (input.type === 'checkbox')
                return input.checked;
            else
                return input.value;
        }
        else 
            return null;
    }

    function convertBlockToObject(blockDiv)
    {
        const output = [];
        for (const eventChecklistItem of blockDiv.querySelectorAll('.eventChecklistItem'))
        {
            const itemObject = 
            {
                title: getInputValueByName(eventChecklistItem, "input", "itemName"),
                type: getInputValueByName(eventChecklistItem, "select", "itemType"),
                optional: Boolean(getInputValueByName(eventChecklistItem, "input", "itemOptional"))
            };
            
            let responsibleUser = getInputValueByName(eventChecklistItem, "select", "itemResponsible");
            if (responsibleUser !== null)
                itemObject.responsibleUser = Number(responsibleUser);

            let itemValue = getInputValueByName(eventChecklistItem, "input", "itemValue");
            if (itemValue !== null && itemValue !== "")
                itemObject.value = itemValue;

            const subChecklist = eventChecklistItem.getElementsByClassName("eventChecklistItem_subChecklist")[0];
            if (subChecklist.children[0])
            {
                itemObject.checkList = [];
                let subItemsLi = subChecklist.querySelectorAll("li");

                for (const li of subItemsLi)
                {
                    const subItemObject = 
                    {
                        name: getInputValueByName(li, "input", "subItemName"),
                        optional: getInputValueByName(li, "input", "subItemOptional")
                    };

                    let subItemResponsibleUser = getInputValueByName(li, "select", "subItemResponsible");
                    if (subItemResponsibleUser !== null)
                        subItemObject.responsibleUser = Number(subItemResponsibleUser);

                    let subItemValue = getInputValueByName(li, "input", "subItemValue");
                    if (subItemValue !== null && subItemValue !== "")
                        subItemObject.value = subItemValue;

                    itemObject.checkList.push(subItemObject);
                }
            }

            output.push(itemObject);
        }

        return output;
    }

    let preEventBlock = document.getElementsByClassName("eventChecklistPreEventBlock")[0];
    let eventDatesBlock = document.getElementsByClassName("eventChecklistEventDateBlock")[0];
    let postEventBlock = document.getElementsByClassName("eventChecklistPostEventBlock")[0];

    let fullObject = {};
    if (preEventBlock)
        fullObject.preevent = convertBlockToObject(preEventBlock);

    if (eventDatesBlock)
        fullObject.eventdate = convertBlockToObject(eventDatesBlock);

    if (postEventBlock)
        fullObject.postevent = convertBlockToObject(postEventBlock);

    return fullObject;
}