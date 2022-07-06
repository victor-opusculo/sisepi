
const uploadDocsChangesReport =
{
    create: null,
    update: null,
    delete: []
};

function validateFiles()
{
    var fileInputs = document.getElementById("frmUploadDocs").querySelectorAll("input[type='file']");
    var errorMessages = [];
    var scannedFileNames = [];

    var trs = document.getElementById("frmUploadDocs").querySelectorAll("table tr[data-id]");
    for (let tr of trs)
        scannedFileNames.push(tr.querySelector('.previousUploadFileName').innerText);

    for (let inp of fileInputs)
    {
        if (file = inp.files[0])
        {
            if (file.size > inp.getAttribute("data-maxsize"))
                errorMessages.push("O arquivo \"" + file.name + "\" excede o limite de " + (inp.getAttribute('data-maxsize') / Math.pow(1024, 2))  + " MB!");

            if (scannedFileNames.find( name => name === file.name ))
                errorMessages.push("Há mais de um arquivo com o nome \"" + file.name + "\". Remova um deles e readicione com outro nome.");
            else
                scannedFileNames.push(file.name);
        }
    }

    for (let err of errorMessages)
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err);

    return errorMessages.length === 0;
}

function generateChangesReport()
{
    uploadDocsChangesReport.update = [];
    uploadDocsChangesReport.create = [];

    var tbody = document.getElementById("frmUploadDocs").querySelector("table tbody");
    var trs = tbody.querySelectorAll("tr");

    for (let tr of trs)
    {
        if (tr.getAttribute("data-id"))
        {
            let updateReg = {};
            updateReg.id = tr.getAttribute("data-id");
            updateReg.docType = tr.querySelector('.selDocType').value;
            uploadDocsChangesReport.update.push(updateReg);
        }
        else
        {
            let createReg = {};
            createReg.fileInputElementName = tr.querySelector('input.fileUploadDoc').name;
            createReg.docType = tr.querySelector('.selDocType').value;
            uploadDocsChangesReport.create.push(createReg);
        }
    }
    
    document.getElementById("hidUploadDocsChangesReport").value = JSON.stringify(uploadDocsChangesReport);
}

function btnAddDoc_onClick(e)
{
    var cloned = document.getElementById('elementsTemplates').querySelector('#trNewDocUpload').cloneNode(true);
    cloned.id = undefined;
    cloned.querySelector(".btnDelDoc").onclick = btnDelDoc_onClick;
    cloned.querySelector('input.fileUploadDoc').name = 'inputFilePersonalDoc' + String(performance.now()).replace(".","");

    var tbody = document.getElementById("frmUploadDocs").querySelector("table tbody");
    tbody.appendChild(cloned);

    if (tbody.children.length >= 10)
        document.getElementById("btnAddDoc").disabled = true;
}

function btnDelDoc_onClick(e)
{
    var tbody = document.getElementById("frmUploadDocs").querySelector("table tbody");
    var tr = this.parentNode.parentNode;
    if (tr.getAttribute("data-id"))
        uploadDocsChangesReport.delete.push( { id: tr.getAttribute("data-id") } );

    tbody.removeChild(tr);

    if (tbody.children.length < 10)
        document.getElementById("btnAddDoc").disabled = false;
}

function btnsubmitSubmitProfessorDocs_onClick(e)
{
    var canSend = true;
    canSend &&= validateFiles();

    generateChangesReport();

    if (!canSend)
        e.preventDefault();
}

window.addEventListener('load', function(e)
{
    this.document.getElementById("btnAddDoc").onclick = btnAddDoc_onClick;
    this.document.getElementById("btnsubmitSubmitProfessorDocs").onclick = btnsubmitSubmitProfessorDocs_onClick;
    this.document.getElementById("frmUploadDocs").querySelectorAll("table tbody .btnDelDoc").forEach( item => item.onclick = btnDelDoc_onClick );

});