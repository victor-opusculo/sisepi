
const extraDatesChangesReport = { create: [], update: [], delete: [] };
var form;

function btnAddDate_onClick(e)
{
    var newDateSpan = document.getElementById("spanNewDate").cloneNode(true);
    newDateSpan.style.display = 'inherit';
    newDateSpan.querySelector(".btnRemoveExtraDate").onclick = btnRemoveExtraDate_onClick;

    document.getElementById("datesList").appendChild(newDateSpan);
}

function btnRemoveExtraDate_onClick(e)
{
    if (this.parentNode.getAttribute('data-id'))
        extraDatesChangesReport.delete.push( { id: this.parentNode.getAttribute('data-id') } );

    document.getElementById("datesList").removeChild(this.parentNode);
}

function btnsubmitSubmitDate_onClick(e)
{
    var inputStyleJson = document.getElementById('customStyleJson');

    inputStyleJson.value = generateStyleJson();
    generateExtraDatesChangesReport();
}

function generateStyleJson()
{
    var colorBgColorPicker = document.getElementById('colorStyleBgColor');
    var colorTextColorPicker = document.getElementById('colorStyleTextColor');
    var checkSetCustomStyle = document.getElementById('chkSetCustomStyle');

    if (checkSetCustomStyle.checked)
    {
        var styleObj =
        {
            backgroundColor: colorBgColorPicker.value,
            textColor: colorTextColorPicker.value
        };

        return JSON.stringify(styleObj);
    }
    else
        return '';
}

function generateExtraDatesChangesReport()
{
    var checkSetCustomStyle = document.getElementById('chkSetCustomStyle');
    var colorBgColorPicker = document.getElementById('colorStyleBgColor');
    var colorTextColorPicker = document.getElementById('colorStyleTextColor');

    document.getElementById("datesList").querySelectorAll(".extraDate").forEach( spanED =>
        {
            if (!spanED.getAttribute("data-id"))
            {
                let createReg = {};
                createReg.title = form.elements['calendardates:txtName'].value;
                createReg.type = form.elements['calendardates:radType'].value;
                createReg.date = spanED.querySelector('input[type="date"]').value;
                createReg.beginTime = spanED.querySelector('input[name="extra:timeBeginTime"]').value;
                createReg.endTime = spanED.querySelector('input[name="extra:timeEndTime"]').value;
                createReg.description = form.elements['calendardates:txtDescription'].value;
                createReg.styleJson = generateStyleJson();

                extraDatesChangesReport.create.push(createReg);
            }
            else
            {
                let updateReg = {};
                updateReg.id = spanED.getAttribute('data-id');
                updateReg.title = form.elements['calendardates:txtName'].value;
                updateReg.type = form.elements['calendardates:radType'].value;
                updateReg.date = spanED.querySelector('input[type="date"]').value;
                updateReg.beginTime = spanED.querySelector('input[name="extra:timeBeginTime"]').value;
                updateReg.endTime = spanED.querySelector('input[name="extra:timeEndTime"]').value;
                updateReg.description = form.elements['calendardates:txtDescription'].value;
                updateReg.styleJson = generateStyleJson();

                extraDatesChangesReport.update.push(updateReg);
            }
        });

    document.getElementById("extraDatesChangesReport").value = JSON.stringify(extraDatesChangesReport);
}

window.addEventListener("load", function(e)
{
    this.document.getElementById("btnAddDate").onclick = btnAddDate_onClick; 
    this.document.getElementById("btnsubmitSubmitDate").onclick = btnsubmitSubmitDate_onClick;
    this.document.querySelectorAll(".btnRemoveExtraDate").forEach( btn => btn.onclick = btnRemoveExtraDate_onClick );

    form = this.document.getElementById("frmCreateDate");
});