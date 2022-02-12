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

function chkAutoCertificate_onChange()
{
	document.getElementById("spanCertificateText").style.display = this.checked ? "block" : "none";
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
	var table = document.getElementById("tableEventDates");
	var tbody = table.querySelector("tbody");
	
	var newTr = document.createElement("tr");
	
	var newTdDateInput = document.createElement("td");
	var newDateInput = document.createElement("input");
	newDateInput.type = "date";
	newDateInput.className = "eventDateDate";
	newDateInput.required = true;
	newTdDateInput.appendChild(newDateInput);
	
	var newTdTimeInput = document.createElement("td")
	var newTimeInput1 = document.createElement("input");
	var newTimeInput2 = document.createElement("input");
	newTimeInput1.type = newTimeInput2.type = "time";
	newTimeInput1.required = newTimeInput2.required = true;
	newTimeInput1.step = newTimeInput2.step = 1;
	newTimeInput1.className = "eventDateTimeBegin";
	newTimeInput2.className = "eventDateTimeEnd";
	newTdTimeInput.appendChild(newTimeInput1);
	newTdTimeInput.appendChild(newTimeInput2);
	
	var newTdDateName = document.createElement("td");
	var newDateNameInput = document.createElement("input");
	newDateNameInput.type = "text";
	newDateNameInput.className = "eventDateName";
	newDateNameInput.size = 20;
	newDateNameInput.maxLength = 120;
	newTdDateName.appendChild(newDateNameInput);
	
	var newTdProfessor = document.createElement("td");
	var newProfessorSelect = document.createElement("select");
	newProfessorSelect.className = "eventDateProfessor";
	newProfessorSelect.style.width = "200px";
	for (let prof of professorsList)
	{
		let selectOption = document.createElement("option");
		selectOption.value = prof.id;
		selectOption.innerText = prof.name;
		newProfessorSelect.appendChild(selectOption);
	}
	newTdProfessor.appendChild(newProfessorSelect);
	
	var newTdCheckPresenceList = (function()
	{
		let td = document.createElement("td");
		let label = document.createElement("label");
		let input = document.createElement("input");
		input.type = "checkbox";
		input.checked = true;
		input.className = "eventDatePresenceListEnabled";
		input.value = "1";
		label.appendChild(input);
		label.appendChild((function() 
		{ 
			let lbl = document.createElement("span"); 
			lbl.innerText = "Habilitar"; 
			return lbl; 
		})());
		
		
		let definePasswordLink = document.createElement("a");
		definePasswordLink.innerText = "Senha"
		definePasswordLink.href = "#";
		definePasswordLink.className = "setPresenceListPassword";
		definePasswordLink.onclick = linkDefinePresenceListPassword_onClick;
		
		let passInput = document.createElement("input");
		passInput.type = "hidden";
		passInput.className = "eventDatePresenceListPassword";
		passInput.value = (function()
		{
			let n1, n2, n3, n4;
			n1 = Math.floor(Math.random() * 9.5);
			n2 = Math.floor(Math.random() * 9.5);
			n3 = Math.floor(Math.random() * 9.5);
			n4 = Math.floor(Math.random() * 9.5);
			
			return String(n1) + String(n2) + String(n3) + String(n4);
		})();
		
		td.appendChild(label);
		td.append(" (");
		td.appendChild(definePasswordLink);
		td.append(")");
		td.appendChild(passInput);
		return td;
	})();
	
	var newTdDeleteButton = (function()
	{
		let td = document.createElement("td");
		let input = document.createElement("input");
		input.type = "button";
		input.className = "eventDateDeleteButton";
		input.value = "X";
		input.style.minWidth = "20px";
		input.onclick = btnDeleteEventDate_onClick;
		td.appendChild(input);
		
		return td;
	})();
	
	newTr.appendChild(newTdDateInput);
	newTr.appendChild(newTdTimeInput);
	newTr.appendChild(newTdDateName);
	newTr.appendChild(newTdProfessor);
	newTr.appendChild(newTdCheckPresenceList);
	newTr.appendChild(newTdDeleteButton);
	
	tbody.appendChild(newTr);
	
	setTableCellsHeadNameAttribute();
}

function linkDefinePresenceListPassword_onClick(e)
{
	e.preventDefault();
	var td = this.parentNode;
	var hiddenInput = td.querySelector(".eventDatePresenceListPassword");
	
	var newPassword = prompt("Defina a senha", hiddenInput.value);
	
	hiddenInput.value = newPassword || hiddenInput.value;
}


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
		input.name = "fileAttachmentFileName" + String(performance.now()).replace(".","");
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
		input.name = "radAttachmentPosterImage";
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
		input.value = "X";
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
	
	if (canSend) generateChangesReports();
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
			updateReg.professorId = tr.querySelector("select.eventDateProfessor").value;
			updateReg.presenceListEnabled = tr.querySelector("input.eventDatePresenceListEnabled").checked ? 1 : 0;
			updateReg.presenceListPassword = tr.querySelector("input.eventDatePresenceListPassword").value;
			
			eventDatesChangesReport.update.push(updateReg);
		}
		else
		{
			let createReg = {};
			createReg.date = tr.querySelector("input[type='date']").value;
			createReg.beginTime = tr.querySelector("input.eventDateTimeBegin").value;
			createReg.endTime = tr.querySelector("input.eventDateTimeEnd").value;
			createReg.name = tr.querySelector("input.eventDateName").value;
			createReg.professorId = tr.querySelector("select.eventDateProfessor").value;
			createReg.presenceListEnabled = tr.querySelector("input.eventDatePresenceListEnabled").checked ? 1 : 0;
			createReg.presenceListPassword = tr.querySelector("input.eventDatePresenceListPassword").value;
			
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
	document.getElementById("btnCreateNewDate").onclick = btnCreateNewDate_onClick;
	document.getElementById("btnCreateNewAttachment").onclick = btnCreateNewAttachment_onClick;
	document.getElementById("btnsubmitSubmit").onclick = btnsubmitSubmit_onClick;
	
	document.querySelectorAll(".eventDateDeleteButton").forEach( item =>
	{
		item.onclick = btnDeleteEventDate_onClick;
	});
	
	document.querySelectorAll(".btnDeleteAttachment").forEach( item =>
	{
		item.onclick = btnDeleteAttachment_onClick;
	});
	
	document.querySelectorAll(".setPresenceListPassword").forEach( item =>
	{
		item.onclick = linkDefinePresenceListPassword_onClick;
	});
	
};