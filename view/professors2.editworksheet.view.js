function selPaymentLevel_onChange(e)
{
    var [table, level] = this.value.split(':');

    document.getElementById('hidPaymentTable').value = table;
    document.getElementById('hidPaymentLevel').value = level;
}

function selSubsAllowancePaymentLevel_onChange(e)
{
    var [table, level] = this.value.split(':');

    document.getElementById('hidSubsAllowanceTable').value = table;
    document.getElementById('hidSubsAllowanceLevel').value = level;
}

function chkUseProfessorCertificate_onChange(e)
{
    document.getElementById('divCertificateText').style.display = this.checked ? 'block' : 'none';
}

function selReferenceMonth_onChange(e)
{
    let year = document.getElementById('numReferenceYear').value;
    document.getElementById('hidReferenceMonth').value = year + '-' + String(this.value).padStart(2, '0') + '-01';
}

function numReferenceYear_onChange(e)
{
    let month = document.getElementById('selReferenceMonth').value;
    document.getElementById('hidReferenceMonth').value = this.value + '-' + String(month).padStart(2, '0') + '-01';
}

function chkUseSubsAllowance_onChange(e, isPageLoading)
{
    document.getElementById('fsSubsAllowance').style.display = this.checked ? 'block' : 'none';

    if (!isPageLoading)
        if (this.checked)
        {
            document.getElementById('hidSubsAllowanceTable').value = 0;
            document.getElementById('hidSubsAllowanceLevel').value = 0;
            document.getElementById('selSubsAllowancePaymentLevel').selectedIndex = 0;
            document.getElementById('numSubsAllowanceClassTime').value = 1;
        }
        else
        {
            document.getElementById('hidSubsAllowanceTable').value = null;
            document.getElementById('hidSubsAllowanceLevel').value = null;
            document.getElementById('numSubsAllowanceClassTime').value = null;
        }
}

function radCollectInssYes_onInput(e)
{
    document.querySelectorAll('.dateInssPeriod').forEach( dateInput => dateInput.required = true );
    document.getElementById('fsInssDeclaration').style.display = 'block';
    if (usedDiscounts.inss) delete usedDiscounts.inss;
}

function radCollectInssNo_onInput(e)
{
    document.querySelectorAll('.dateInssPeriod').forEach( dateInput => dateInput.required = false );
    document.getElementById('fsInssDeclaration').style.display = 'none';
    usedDiscounts.inss = { label: "INSS", discount: Number(availableDiscounts.inss || 0) };
}


//#region Load event infos
function btnLoadEvent_onClick(e)
{
    setEventIdInput(document.getElementById('numEventId').value);
}

function setEventIdInput(eventId)
{
    if (eventId)
        fetch(getEventInfosScriptURL + '?id=' + eventId).then( res => res.json() ).then( json => applyEventInfos(json) );
}

function applyEventInfos(responseObj)
{
    if (!responseObj.error)
    {
        document.getElementById('numEventId').value = responseObj.data.id;
        if (!document.getElementById('txtActivityName').value)
            document.getElementById('txtActivityName').value = responseObj.data.name;
        document.getElementById('lblEventName').innerText = responseObj.data.name;
        document.getElementById('lblEventType').innerText = responseObj.data.typeName;
        document.getElementById('lblEventMode').innerText = responseObj.data.locTypes;
        document.getElementById('dateInssPeriodBegin').value = responseObj.data.minDate;
        document.getElementById('dateInssPeriodEnd').value = responseObj.data.maxDate;
    }
    else
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, responseObj.error);
}

function btnSearchEvent_onClick(e)
{
	var popup = window.open(popupURL.replace('{popup}', 'selectevent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}
//#endregion

//#region Load professor infos
function btnLoadProfessor_onClick(e)
{
    setProfessorIdInput(document.getElementById('numProfessorId').value);
}

function setProfessorIdInput(profId)
{
    if (profId)
        fetch(getProfessorInfosScriptURL + '?id=' + profId).then( res => res.json() ).then( json => applyProfessorInfos(json) );
}

function applyProfessorInfos(responseObj)
{
    if (!responseObj.error)
    {
        document.getElementById('numProfessorId').value = responseObj.data.id;
        document.getElementById('lblProfessorName').innerText = responseObj.data.name;
        document.getElementById('lblProfessorEmail').innerText = responseObj.data.email;
        document.getElementById('lblProfessorSchoolingLevel').innerText = responseObj.data.schoolingLevel;
        document.getElementById('lblProfessorCollectInssInfo').innerText = (function(collectInss)
        {
            if (Boolean(collectInss))
                return '(Docente contribui com o INSS)';
            else
                return '(Docente não contribui com o INSS)';
        })(responseObj.data.collectInss);

        let radInssYes = document.getElementById('radCollectInssYes');
        let radInssNo = document.getElementById('radCollectInssNo');
        if (!radInssYes.checked && !radInssNo.checked)
        {
            radInssYes.checked = Boolean(responseObj.data.collectInss);
            if (radInssYes.checked) radCollectInssYes_onInput();
    
            radInssNo.checked = !Boolean(responseObj.data.collectInss);
            if (radInssNo.checked) radCollectInssNo_onInput();
        }
    }
    else
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, responseObj.error);
}

function btnSearchProfessor_onClick(e)
{
	var popup = window.open(popupURL.replace('{popup}', 'selectprofessor'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}
//#endregion

function frmEditWorkSheet_onSubmit()
{
    document.getElementById('hidDiscounts').value = JSON.stringify(usedDiscounts);
}

window.addEventListener('load', function(e)
{
    this.document.getElementById('selPaymentLevel').onchange = selPaymentLevel_onChange;
    this.document.getElementById('chkUseSubsAllowance').onchange = chkUseSubsAllowance_onChange;
    this.document.getElementById('selSubsAllowancePaymentLevel').onchange = selSubsAllowancePaymentLevel_onChange;
    this.document.getElementById('btnLoadEvent').onclick = btnLoadEvent_onClick;
    this.document.getElementById('btnSearchEvent').onclick = btnSearchEvent_onClick;
    this.document.getElementById('btnLoadProfessor').onclick = btnLoadProfessor_onClick;
    this.document.getElementById('btnSearchProfessor').onclick = btnSearchProfessor_onClick;
    this.document.getElementById('chkUseProfessorCertificate').onchange = chkUseProfessorCertificate_onChange;
    this.document.getElementById('selReferenceMonth').onchange = selReferenceMonth_onChange;
    this.document.getElementById('numReferenceYear').onchange = numReferenceYear_onChange;
    this.document.getElementById('radCollectInssYes').oninput = radCollectInssYes_onInput;
    this.document.getElementById('radCollectInssNo').oninput = radCollectInssNo_onInput;
    this.document.getElementById('frmEditWorkSheet').onsubmit = frmEditWorkSheet_onSubmit;

    btnLoadProfessor_onClick();
    btnLoadEvent_onClick();
    chkUseProfessorCertificate_onChange.apply(this.document.getElementById('chkUseProfessorCertificate'));
    chkUseSubsAllowance_onChange.apply(this.document.getElementById('chkUseSubsAllowance'), [null, true]);

    usedDiscounts.issqn = { label: "ISSQN", discount: Number(availableDiscounts.issqn || 0) }; //Sempre usar este desconto
});