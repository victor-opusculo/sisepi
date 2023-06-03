
const extraDatesChangesReport = { create: null, update: null, delete: [] };
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
    var inputTraitsJson = document.getElementById('masterDateTraits');

    inputTraitsJson.value = JSON.stringify(Array.from(document.querySelectorAll('#tbodyDateTraits img.dateTrait')).map( img => Number(img.getAttribute('data-traitId')) ));
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
    extraDatesChangesReport.create = [];
    extraDatesChangesReport.update = [];

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
                createReg.dateTraits = Array.from(document.querySelectorAll('#tbodyDateTraits img.dateTrait')).map( img => Number(img.getAttribute('data-traitId')) );

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
                updateReg.dateTraits = Array.from(document.querySelectorAll('#tbodyDateTraits img.dateTrait')).map( img => Number(img.getAttribute('data-traitId')) );

                extraDatesChangesReport.update.push(updateReg);
            }
        });

    document.getElementById("extraDatesChangesReport").value = JSON.stringify(extraDatesChangesReport);
}

 //#region Traits

function btnAddTraitId_onClick(e)
{
    let traitId = document.getElementById('numAddTraitId').value;
    traits_loadInfos(traitId);
}

function btnTraitSearch_onClick(e)
{
    var popup = window.open(popupURL.replace('{popup}', 'selecttrait'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}

function setTraitIdInput(traitId)
{
	traits_loadInfos(traitId);
}

function btnTraitRemove_onClick(e)
{
    document.getElementById('tbodyDateTraits').removeChild(this.parentNode.parentNode);
}

async function traits_loadInfos(traitId)
{
    let res = await fetch(getTraitInfosScript + '?id=' + traitId);
    let obj = await res.json();

    if (!obj.error && obj.data)
	{
		let newTrBlueprint = document.getElementById("newTraitTr").cloneNode(true);
		let traitIcon = newTrBlueprint.querySelector('img.dateTrait');
		let btnRemove = newTrBlueprint.querySelector('button.btnTraitRemove');

		traitIcon.src = traitIconsPath + obj.data.id + '.' + obj.data.fileExtension;
		traitIcon.alt = traitIcon.title = obj.data.name;
		traitIcon.setAttribute('data-traitId', obj.data.id);
		btnRemove.onclick = btnTraitRemove_onClick;

        document.getElementById('tbodyDateTraits').appendChild(newTrBlueprint);
	}
	else
		showBottomScreenMessageBox(BottomScreenMessageBoxType.error, obj.error);
}

 //#endregion

window.addEventListener("load", function(e)
{
    this.document.getElementById("btnAddDate").onclick = btnAddDate_onClick; 
    this.document.getElementById("btnsubmitSubmitDate").onclick = btnsubmitSubmitDate_onClick;
    this.document.getElementById("btnAddTraitId").onclick = btnAddTraitId_onClick;
    this.document.getElementById("btnTraitSearch").onclick = btnTraitSearch_onClick;
    this.document.querySelectorAll(".btnRemoveExtraDate").forEach( btn => btn.onclick = btnRemoveExtraDate_onClick );
    this.document.querySelectorAll(".btnTraitRemove").forEach ( btn => btn.onclick = btnTraitRemove_onClick );

    form = this.document.getElementById("frmCreateDate");
});