const eventWorkPlanAttachmentsChangesReport =
{
    delete: [],
    create: null
};

function workPlanAttsCheckForRepeatedFileNames()
{
	var tbody = document.getElementById("tbodyWpAttachments");
	
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

function btnAddWorkPlanAttachment_onClick(e)
{
    var tbody = document.getElementById('tbodyWpAttachments');
    var newAtt = document.getElementById('newWpAttachmentTemplate').cloneNode(true);

    var fileInput = newAtt.querySelector('.fileAttachmentFileName');
    fileInput.name = "eventworkplans:fileAttachmentFileName" + String(performance.now()).replace(".","");
    fileInput.required = true;

    newAtt.querySelector('.workPlanAttachmentDeleteButton').onclick = workPlanAttachmentDeleteButton_onClick;

    tbody.appendChild(newAtt);
}

function workPlanAttachmentDeleteButton_onClick(e)
{
    var tr = this.parentNode.parentNode;
    if (tr.getAttribute('data-wpattid'))
        eventWorkPlanAttachmentsChangesReport.delete.push( { id: tr.getAttribute("data-wpattid") } );
    
    var tbody = document.getElementById('tbodyWpAttachments');
    
    tbody.removeChild(tr);
}

function generateWpAttachmentsChangesReport()
{
    eventWorkPlanAttachmentsChangesReport.create = [];

    var trs = document.querySelectorAll('#tbodyWpAttachments tr');
    for (const tr of trs)
    {
        if (!tr.getAttribute('data-wpattid'))
        {
            let createReg = {};
            createReg.fileInputElementName = tr.querySelector("input.fileAttachmentFileName").name;
            eventWorkPlanAttachmentsChangesReport.create.push(createReg);
        }
    }

    document.getElementById("eventWorkPlanAttachmentsChangesReport").value = JSON.stringify(eventWorkPlanAttachmentsChangesReport);
}

function validateWorkPlanAttachments()
{
    if (workPlanAttsCheckForRepeatedFileNames())
    {
        alert("Há arquivos de mesmo nome entre os anexos privados do plano de trabalho. Remova os arquivos repetidos.");
        return false;
    }

    generateWpAttachmentsChangesReport();
    return true;
}

window.addEventListener('load', function(e)
{
    this.document.getElementById('btnAddWorkPlanAttachment').onclick = btnAddWorkPlanAttachment_onClick;
    this.document.querySelectorAll('.workPlanAttachmentDeleteButton').forEach( btn => btn.onclick = workPlanAttachmentDeleteButton_onClick );
});
