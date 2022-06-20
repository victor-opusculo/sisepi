function changeItemTypeToDefault(itemDiv)
{
    let optionalCheckbox = itemDiv.querySelector("input[name='itemOptional']");
    optionalCheckbox.disabled = false;

    let checklistBlock = itemDiv.querySelector(".eventSurveyItem_checklist");
    while (checklistBlock.firstChild) checklistBlock.removeChild(checklistBlock.firstChild);
}

function changeItemTypeToCheckList(itemDiv)
{
    let optionalCheckbox = itemDiv.querySelector("input[name='itemOptional']");
    optionalCheckbox.checked = false;
    optionalCheckbox.disabled = true;

    let checklistBlock = itemDiv.querySelector(".eventSurveyItem_checklist");
    while (checklistBlock.firstChild) checklistBlock.removeChild(checklistBlock.firstChild);

    for (const child of document.getElementById("itemChecklist").children)
    {
        let cloned = child.cloneNode(true);
        cloned.querySelectorAll("input[type='text']").forEach( input => input.required = true );
        checklistBlock.appendChild(cloned);
    }
}

function addSubItem(itemOl)
{
    let subItemElements = document.getElementById("itemChecklist").querySelector("li").cloneNode(true);
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
        case "checkList":
            changeItemTypeToCheckList(self.parentNode.parentNode.parentNode);
            break;
        default:
            changeItemTypeToDefault(self.parentNode.parentNode.parentNode);
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
    addSubItem(subItemsOl);
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

function generateSurveyJson()
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
        for (const eventSurveyItem of blockDiv.querySelectorAll('.eventSurveyItem'))
        {
            const itemObject = 
            {
                title: getInputValueByName(eventSurveyItem, "input", "itemName"),
                type: getInputValueByName(eventSurveyItem, "select", "itemType"),
                optional: Boolean(getInputValueByName(eventSurveyItem, "input", "itemOptional"))
            };

            const checklist = eventSurveyItem.getElementsByClassName("eventSurveyItem_checklist")[0];
            if (checklist.children[0])
            {
                itemObject.checkList = [];
                let subItemsLi = checklist.querySelectorAll("li");

                for (const li of subItemsLi)
                {
                    const subItemObject = 
                    {
                        name: getInputValueByName(li, "input", "subItemName")
                    };

                    itemObject.checkList.push(subItemObject);
                }
            }

            output.push(itemObject);
        }

        return output;
    }

    let headBlock = document.getElementsByClassName("eventSurveyHeadBlock")[0];
    let bodyBlock = document.getElementsByClassName("eventSurveyBodyBlock")[0];
    let footBlock = document.getElementsByClassName("eventSurveyFootBlock")[0];

    let fullObject = {};
    if (headBlock)
        fullObject.head = convertBlockToObject(headBlock);

    if (bodyBlock)
        fullObject.body = convertBlockToObject(bodyBlock);

    if (footBlock)
        fullObject.foot = convertBlockToObject(footBlock);

    return fullObject;
}