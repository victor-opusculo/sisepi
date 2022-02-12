
const attachmentsChangesReport =
{
	create: null,
	delete: []
};

function fileInput_onChange(e)
{
	var tr = this.parentNode.parentNode;
	var radio = tr.querySelector("input[type='radio']");
	
	radio.value = this.files[0].name;
}

function btnCreateAttachment_onClick(e)
{
	var table = document.getElementById("tblAttachments");
	var tbody = document.querySelector("tbody");
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
		input.name = "radAttachmentMainImage";
		spanlabel.innerText = "Foto principal";
		lbl.appendChild(input);
		lbl.appendChild(spanlabel);
		td.appendChild(lbl);
		
		return td;
	})();
	
	var newTdDeleteButton = (function()
	{
		let td = document.createElement("td");
		let input = document.createElement("button");
		input.type = "button";
		input.innerText = "X";
		input.className = "btnDelAttachment";
		input.onclick = btnDelAttachment_onClick;
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

function btnDelAttachment_onClick(e)
{
	var rowNode = this.parentNode.parentNode;
	
	if (rowNode.getAttribute("data-id")) attachmentsChangesReport.delete.push( { id: rowNode.getAttribute("data-id") } );
	
	document.querySelector("#tblAttachments tbody").removeChild(rowNode);
}

function btnsubmitSubmit_onClick(e)
{
	if (checkForRepeatedFileNames())
	{
		alert("Há arquivos de mesmo nome entre os anexos. Remova os arquivos repetidos.");
		e.preventDefault();
	}
	else
		generateChangesReport();
}

function checkForRepeatedFileNames()
{
	var tbody = document.getElementById("tblAttachments");
	
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

function generateChangesReport()
{
	var attachmentsChangesReportInput = document.getElementById("attachmentsChangesReport");
	
	attachmentsChangesReport.create = [];
	
	//Art attachments
	for (let tr of document.getElementById("tblAttachments").querySelectorAll("tr"))
	{
		if (!tr.getAttribute("data-id"))
		{
			let createReg = {};
			createReg.fileInputElementName = tr.querySelector("input.fileAttachmentFileName").name;
			
			attachmentsChangesReport.create.push(createReg);
		}
	}
	
	attachmentsChangesReportInput.value = JSON.stringify(attachmentsChangesReport);
}

window.onload = function()
{
	document.getElementById("tblAttachments").querySelectorAll(".btnDelAttachment").forEach( btn => btn.onclick = btnDelAttachment_onClick );
	document.getElementById("btnCreateAttachment").onclick = btnCreateAttachment_onClick;
	document.getElementById("btnsubmitSubmit").onclick = btnsubmitSubmit_onClick;
};