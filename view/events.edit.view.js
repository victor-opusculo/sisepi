const eventDatesChangesReport = 
{
	create: [],
	update: [],
	delete: []
};

const eventAttachmentsChangesReport =
{
	create: [],
	delete: []
};

function chkSubscriptionListNeeded_onChange()
{
	document.getElementById("spanSubscriptionExtraParameters").style.display = this.checked ? "block" : "none";
}

function btnAddCustomInfo_onClick(e)
{
	var ciSpanBlueprint = document.getElementById("newEventCustomInfo").cloneNode(true);
	var divCustomInfos = document.getElementById("divCustomInfos");

	ciSpanBlueprint.querySelector(".btnCustomInfoDelete").onclick = btnCustomInfoDelete_onClick;
	divCustomInfos.appendChild(ciSpanBlueprint);
}

function btnCustomInfoDelete_onClick(e)
{
	var divCustomInfos = document.getElementById("divCustomInfos");
	divCustomInfos.removeChild(this.parentNode);
}

function chkAutoCertificate_onChange()
{
	document.getElementById("spanCertificateText").style.display = this.checked ? "block" : "none";
}

function chkEnableSurvey_onChange()
{
	document.getElementById("spanSurveyTemplate").style.display = this.checked ? "block" : "none";
}

function chkEnableTest_onChange()
{
	document.getElementById("spanTestTemplate").style.display = this.checked ? "block" : "none";
}

function btnDeleteEventDate_onClick()
{
	var dateId = this.getAttribute("data-dateId");
	if (dateId) eventDatesChangesReport.delete.push({ id: dateId });
	
	document.getElementById("tableEventDates").querySelector("tbody").removeChild(this.parentNode.parentNode);
}

function btnDeleteAttachment_onClick()
{
	var attachId = this.getAttribute("data-attachId");
	if (attachId) eventAttachmentsChangesReport.delete.push({ id: attachId });
	
	document.getElementById("tableEventAttachments").querySelector("tbody").removeChild(this.parentNode.parentNode);
}

function btnCreateNewDate_onClick()
{
	let trFromBlueprint = document.getElementById("newEventDateTableRow").cloneNode(true);
	let tbody = document.querySelector("#tableEventDates tbody");

	trFromBlueprint.querySelector(".eventDatePresenceListPassword").value = (function()
	{	
		let n = Math.floor(Math.random() * 9999)
		return String(n).padStart(4, "0");
	})();

	trFromBlueprint.id = undefined;

	trFromBlueprint.querySelector(".eventDateDeleteButton").onclick = btnDeleteEventDate_onClick;
	trFromBlueprint.querySelector(".eventDateProfessor_addNew").onclick = linkEventDateProfessor_addNew_onClick;
	trFromBlueprint.querySelector(".eventDateTraitsAdd").onclick = eventDateTraitsAdd_onClick;
	trFromBlueprint.querySelector(".eventDateTraitsSearch").onclick = eventDateTraitsSearch_onClick;
	tbody.appendChild(trFromBlueprint);
	
	setTableCellsHeadNameAttribute();
}

function linkEventDateProfessor_addNew_onClick(e)
{
	e.preventDefault();
	var ulDropdownMenu = this.parentNode.parentNode;
	var hrElement = ulDropdownMenu.querySelector("hr");

	var newLiBlueprint = document.getElementById("newEventDateProfessor").cloneNode(true);
	newLiBlueprint.querySelector(".eventDateProfessor_remove").onclick = linkEventDateProfessor_remove_onClick;
	ulDropdownMenu.insertBefore(newLiBlueprint, hrElement);
}

function linkEventDateProfessor_remove_onClick(e)
{
	e.preventDefault();
	var ulDropdownMenu = this.parentNode.parentNode;
	ulDropdownMenu.removeChild(this.parentNode);
}

//#region Event Dates Traits

var eventDateTraits_currentDropDownMenu = null;
function eventDateTraitsAdd_onClick(e)
{
	let traitId = this.parentNode.querySelector('.eventDateTraitsTraitId').value;
	let ulDropdownMenu = this.parentNode.parentNode;
	eventDateTrait_loadInfos(traitId, ulDropdownMenu);
}

function eventDateTraitsSearch_onClick(e)
{
	eventDateTraits_currentDropDownMenu = this.parentNode.parentNode;
	var popup = window.open(popupURL.replace('{popup}', 'selecttrait'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}

function setTraitIdInput(traitId)
{
	eventDateTrait_loadInfos(traitId, eventDateTraits_currentDropDownMenu);
}

function eventDateTrait_remove_onClick(e)
{
	e.preventDefault();
	let ulDropdownMenu = this.parentNode.parentNode;
	ulDropdownMenu.removeChild(this.parentNode);
}

async function eventDateTrait_loadInfos(traitId, ulDropdownMenu)
{
	let res = await fetch(getTraitsInfosScript + '?id=' + traitId);
	let json = await res.json();

	if (!json.error && json.data)
	{
		let hrElement = ulDropdownMenu.querySelector("hr");
		let newLiBlueprint = document.getElementById("newEventDateTrait").cloneNode(true);
		let traitIcon = newLiBlueprint.querySelector('img');
		let linkRemove = newLiBlueprint.querySelector('a');

		traitIcon.src = traitIconsPath + json.data.id + '.' + json.data.fileExtension;
		traitIcon.alt = traitIcon.title = json.data.name;
		traitIcon.setAttribute('data-traitId', json.data.id);
		linkRemove.onclick = eventDateTrait_remove_onClick;

		ulDropdownMenu.insertBefore(newLiBlueprint, hrElement);
	}
	else
		showBottomScreenMessageBox(BottomScreenMessageBoxType.error, json.error);
}

//#endregion

function btnCreateNewAttachment_onClick()
{
	var table = document.getElementById("tableEventAttachments");
	var tbody = table.querySelector("tbody");
	var newTr = document.createElement("tr");
	
	var newTdFileInput = (function()
	{
		let td = document.createElement("td");
		let input = document.createElement("input");
		input.type = "file";
		input.className = "fileAttachmentFileName";
		input.name = "events:fileAttachmentFileName" + String(performance.now()).replace(".","");
		input.onchange = fileInput_onChange;
		input.required = true;
		td.appendChild(input);
		
		return td;
	})();
	
	var newTdRadInput = (function()
	{
		let td = document.createElement("td");
		let lbl = document.createElement("label");
		let spanlabel = document.createElement("span");
		let input = document.createElement("input");
		input.type = "radio";
		input.name = "events:radAttachmentPosterImage";
		spanlabel.innerText = "Cartaz";
		lbl.appendChild(input);
		lbl.appendChild(spanlabel);
		td.appendChild(lbl);
		
		return td;
	})();
	
	var newTdDeleteButton = (function()
	{
		let td = document.createElement("td");
		let input = document.createElement("input");
		input.type = "button";
		input.value = "×";
		input.className = "btnDeleteAttachment";
		input.onclick = btnDeleteAttachment_onClick;
		input.style.minWidth = "20px";
		td.className = "shrinkCell";
		td.appendChild(input);
		
		return td;
	})();
	
	newTr.appendChild(newTdFileInput);
	newTr.appendChild(newTdRadInput);
	newTr.appendChild(newTdDeleteButton);
	
	tbody.appendChild(newTr);
}

function fileInput_onChange(e)
{
	var tr = this.parentNode.parentNode; //<tr> tag
	var radio = tr.querySelector("input[type='radio']");
	
	radio.value = this.files[0].name;
}

function btnsubmitSubmit_onClick(e)
{
	var form = document.querySelector("form");
	var canSend = true;
	for (let element of form.elements)
	{
		if (element.value === "" && element.hasAttribute('required'))
			canSend = false;
	}
	
	if (document.getElementById("chkSubscriptionListNeeded").checked)
	{
		if (document.getElementById("txtMaxSubscriptionNumber").value === "" || document.getElementById("dateSubscriptionListClosureDate").value === "")
		{
			alert("Defina um número máximo de vagas e uma data de encerramento da lista!");
			canSend = false;
			e.preventDefault();
		}
	}
	
	if (document.getElementById("chkAutoCertificate").checked)
	{
		if (document.getElementById("txtCertificateText").value === "")
		{
			alert("Defina o texto para o certificado!");
			canSend = false;
			e.preventDefault();
		}
		
		if (document.getElementById("txtCertificateBgFile").value === "")
		{
			alert("Defina a imagem de fundo do certificado!");
			canSend = false;
			e.preventDefault();
		}
	}
	
	if (document.getElementById("tableEventDates").querySelectorAll("tbody tr").length < 1)
	{ 
		canSend = false;
		alert("Crie pelo menos uma data para o evento!");
		e.preventDefault();
	}
	
	if (checkForRepeatedFileNames())
	{
		canSend = false;
		alert("Há arquivos de mesmo nome entre os anexos. Remova os arquivos repetidos.");
		e.preventDefault();
	}

	if (validateWorkPlanAttachments && !validateWorkPlanAttachments())
	{
		canSend = false;
		e.preventDefault();
	}
	
	if (canSend)
	{
		generateChangesReports();
		generateCustomInfosJson();
		
		if (window.generateChecklistJson)
            document.getElementById("eventchecklistsJson").value = JSON.stringify(generateChecklistJson());
	}
}

function checkForRepeatedFileNames()
{
	var tbody = document.getElementById("tableEventAttachments");
	
	var spansFileNames = tbody.querySelectorAll("span.existentFileName");
	var inputs = tbody.querySelectorAll("input[type='file']");
	
	var fileNamesList = [];
	var foundRepeated = false;
	
	spansFileNames.forEach( span => fileNamesList.push(span.innerText) );
	inputs.forEach( input => fileNamesList.push(input.files[0].name) );
	
	fileNamesList.forEach( fileName =>
	{
		foundRepeated ||= fileNamesList.filter( el => el === fileName ).length > 1;
	});
	
	return foundRepeated;
}

function generateCustomInfosJson()
{
	var outputArray = [];
	var hidCustomInfosInput = document.getElementById("eventCustomInfosJson");

	var divCustomInfos = document.getElementById("divCustomInfos");
	divCustomInfos.querySelectorAll('.spanCustomInfo').forEach( span =>
	{
		let label = span.querySelector(".txtCustomInfoLabel").value;
		let value = span.querySelector(".txtCustomInfoValue").value;

		outputArray.push( { label: label, value: value } );
	});

	hidCustomInfosInput.value = JSON.stringify(outputArray);
}

function generateChangesReports()
{
	var eventDatesChangesReportInput = document.getElementById("eventDatesChangesReport");
	var eventAttachmentsChangesReportInput = document.getElementById("eventAttachmentsChangesReport");
	
	eventDatesChangesReport.update = [];
	eventDatesChangesReport.create = [];
	
	//Event dates
	for (let tr of document.getElementById("tableEventDates").querySelectorAll("tbody tr"))
	{
		if (tr.getAttribute("data-dateId"))
		{
			let updateReg = {};
			updateReg.id = Number(tr.getAttribute("data-dateId"));
			updateReg.date = tr.querySelector("input[type='date']").value;
			updateReg.beginTime = tr.querySelector("input.eventDateTimeBegin").value;
			updateReg.endTime = tr.querySelector("input.eventDateTimeEnd").value;
			updateReg.name = tr.querySelector("input.eventDateName").value;
			updateReg.professors = [...tr.querySelectorAll("select.eventDateProfessor")].map( select => Number(select.value) );
			updateReg.presenceListNeeded = tr.querySelector("input.eventDatePresenceListEnabled").checked ? 1 : 0;
			updateReg.presenceListPassword = tr.querySelector("input.eventDatePresenceListPassword").value;
			updateReg.locationId = tr.querySelector("select.eventDateLocationId").value || null;
			updateReg.locationInfosJson = JSON.stringify(
				{
					url: tr.querySelector("input.eventDateLocationURL").value,
					infos: tr.querySelector("input.eventDateLocationInfos").value
				});
			updateReg.checklistAction = tr.querySelector("select.eventDateChecklistActions").value;
			updateReg.traits = Array.from(tr.querySelectorAll("img.eventDateTrait")).map( img => Number(img.getAttribute('data-traitId')) );

			eventDatesChangesReport.update.push(updateReg);
		}
		else
		{
			let createReg = {};
			createReg.date = tr.querySelector("input[type='date']").value;
			createReg.beginTime = tr.querySelector("input.eventDateTimeBegin").value;
			createReg.endTime = tr.querySelector("input.eventDateTimeEnd").value;
			createReg.name = tr.querySelector("input.eventDateName").value;
			createReg.professors = [...tr.querySelectorAll("select.eventDateProfessor")].map( select => Number(select.value) );
			createReg.presenceListNeeded = tr.querySelector("input.eventDatePresenceListEnabled").checked ? 1 : 0;
			createReg.presenceListPassword = tr.querySelector("input.eventDatePresenceListPassword").value;
			createReg.locationId = tr.querySelector("select.eventDateLocationId").value || null;
			createReg.locationInfosJson = JSON.stringify(
				{
					url: tr.querySelector("input.eventDateLocationURL").value,
					infos: tr.querySelector("input.eventDateLocationInfos").value
				});
			createReg.checklistAction = tr.querySelector("select.eventDateChecklistActions").value;
			createReg.traits = Array.from(tr.querySelectorAll("img.eventDateTrait")).map( img => Number(img.getAttribute('data-traitId')) );
			
			eventDatesChangesReport.create.push(createReg);
		}
	}
	
	//Event attachments
	for (let tr of document.getElementById("tableEventAttachments").querySelectorAll("tr"))
	{
		if (!tr.getAttribute("data-attachId"))
		{
			let createReg = {};
			createReg.fileInputElementName = tr.querySelector("input.fileAttachmentFileName").name;
			
			eventAttachmentsChangesReport.create.push(createReg);
		}
	}
	
	eventDatesChangesReportInput.value = JSON.stringify(eventDatesChangesReport);
	eventAttachmentsChangesReportInput.value = JSON.stringify(eventAttachmentsChangesReport);
}

window.onload = function()
{
	document.getElementById("chkSubscriptionListNeeded").onchange = chkSubscriptionListNeeded_onChange;
	document.getElementById("chkAutoCertificate").onchange = chkAutoCertificate_onChange;
	document.getElementById("chkEnableSurvey").onchange = chkEnableSurvey_onChange;
	document.getElementById("btnCreateNewDate").onclick = btnCreateNewDate_onClick;
	document.getElementById("btnCreateNewAttachment").onclick = btnCreateNewAttachment_onClick;
	document.getElementById("btnsubmitSubmit").onclick = btnsubmitSubmit_onClick;
	document.getElementById("btnAddCustomInfo").onclick = btnAddCustomInfo_onClick;
	document.getElementById("chkEnableTest").onclick = chkEnableTest_onChange;
	
	document.querySelectorAll(".btnCustomInfoDelete").forEach( item =>
	{
		item.onclick = btnCustomInfoDelete_onClick;
	});

	document.querySelectorAll(".eventDateDeleteButton").forEach( item =>
	{
		item.onclick = btnDeleteEventDate_onClick;
	});
	
	document.querySelectorAll(".btnDeleteAttachment").forEach( item =>
	{
		item.onclick = btnDeleteAttachment_onClick;
	});	

	document.querySelectorAll(".eventDateProfessor_addNew").forEach( item =>
	{
		item.onclick = linkEventDateProfessor_addNew_onClick;
	});

	document.querySelectorAll(".eventDateProfessor_remove").forEach( item =>
	{
		item.onclick = linkEventDateProfessor_remove_onClick;
	});

	document.querySelectorAll(".eventDateTrait_remove").forEach( item =>
	{
		item.onclick = eventDateTrait_remove_onClick;
	});

	document.querySelectorAll(".eventDateTraitsAdd").forEach( item =>
	{
		item.onclick = eventDateTraitsAdd_onClick;
	});

	document.querySelectorAll(".eventDateTraitsSearch").forEach( item =>
	{
		item.onclick = eventDateTraitsSearch_onClick;
	});
};