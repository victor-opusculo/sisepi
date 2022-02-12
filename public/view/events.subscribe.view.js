function ageCalculator(input) 
{  
    var userinput = input;  
    var dob = new Date(userinput);  

	//calculate month difference from current date in time  
	var month_diff = Date.now() - dob.getTime();  
	  
	//convert the calculated difference in date format  
	var age_dt = new Date(month_diff);   
	  
	//extract year from date      
	var year = age_dt.getUTCFullYear();  
	  
	//now calculate the age of the user  
	var age = Math.abs(year - 1970);  
	  
	//display the calculated age  
	return age;  
  
}  

function btnsubmitSubmitSubscription_onClick(e)
{
	var dateInput = document.getElementById("dateBirthDate");
	if (dateInput.value !== "")
		if (ageCalculator(dateInput.value) < 14)
		{
			alert("Aviso: Você tem menos de 14 anos. Somente pessoas com 14 anos ou mais podem se inscrever nos eventos da escola.");
			e.preventDefault();
		}
}

var frmSubs;

function txtEmail_onblur(e)
{
	getJson(this.value).then( json => changeFormFromJson(json), err => alert(err.message) );
}
		
async function getJson(email)
{
	var res = await fetch(getLastSubscriptionScriptURL.replace("{email}", email));
	if (res.ok)
	{
		var json = await res.json();
		return json;
	}
	else
		throw new Error("Erro ao obter os dados de cadastro. Erro HTTP: " + res.status);
}

function changeTextField(fieldObject)
{
	if (frmSubs.elements[fieldObject.field])
	{
		frmSubs.elements[fieldObject.field].value = fieldObject.value;
	}
}

function changeRadioField(fieldObject)
{
	if (frmSubs.elements[fieldObject.field])
	{
		for (let rad of frmSubs.elements[fieldObject.field])
			if (rad.value === fieldObject.value)
				rad.checked = true;
	}
}

function changeCheckboxField(fieldObject)
{
	if (frmSubs.elements[fieldObject.field])
	{
		frmSubs.elements[fieldObject.field].checked = Boolean(fieldObject.value); 
		if (frmSubs.elements[fieldObject.field].onchange)
			frmSubs.elements[fieldObject.field].onchange();
	}
}

function changeSelectField(fieldObject)
{
	if (frmSubs.elements[fieldObject.field])
	{
		for (let opt of frmSubs.elements[fieldObject.field].options)
			if (opt.value === fieldObject.value)
				opt.selected = true;
	}
}

function changeFormFromJson(jsonObject)
{	
	for (let field of jsonObject.fields)
	{
		switch(field.type)
		{
			case "radio":
				changeRadioField(field);
				break;
			case "check":
				changeCheckboxField(field);
				break;
			case "select":
				changeSelectField(field);
				break;
			case "text":
			case "date":
			default:
				changeTextField(field);
				break;
		}
	}
}

window.onload = function()
{
	frmSubs = document.getElementById("frmSubs");
	document.getElementById("txtEmail").onblur = txtEmail_onblur;
	
	document.getElementById("btnsubmitSubmitSubscription").onclick = btnsubmitSubmitSubscription_onClick;
	
	document.getElementById("chkUseSocialName").onchange = function()
	{
		document.getElementById("socialNameFrame").style.display = this.checked ? "block" : "none";
	}
};