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
	var dateInput = document.querySelector("input[data-identifier='birthDate']");
	if (dateInput && dateInput.value !== "")
		if (ageCalculator(dateInput.value) < 14)
		{
			alert("Aviso: Você tem menos de 14 anos. Somente pessoas com 14 anos ou mais podem se inscrever nos eventos da escola.");
			e.preventDefault();
		}
}

var frmSubs;

function txtEmail_onblur(e)
{
	if (this.value)
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

function changeTextField(questObject)
{
	let input = Array.from(frmSubs.elements).find( inp => inp.getAttribute('data-identifier') === questObject.identifier );

	if (input && questObject.value)
	{
		input.value = questObject.value;
		let span = input.parentNode.parentNode;
		if (span && span.getAttribute('data-collapsible'))
		{
			span.style.display = 'block';
			document.getElementById('chk_' + span.id).checked = true;
			span.querySelectorAll('input, select').forEach( inp => inp.disabled = false );
		}
	}
}

function changeRadioField(questObject)
{
	let inputs = Array.from(frmSubs.elements).filter( inp => inp.getAttribute('data-identifier') === questObject.identifier );

	if (inputs && questObject.value)
	{
		for (let rad of inputs)
			if (rad.value === questObject.value)
				rad.checked = true;
		
		let span = inputs[0].parentNode.parentNode;
		if (span && span.getAttribute('data-collapsible'))
		{
			span.style.display = 'block';
			document.getElementById('chk_' + span.id).checked = true;
			span.querySelectorAll('input, select').forEach( inp => inp.disabled = false );
		}
	}
}

function changeCheckboxField(questObject)
{
	let input = Array.from(frmSubs.elements).find( inp => inp.getAttribute('data-identifier') === questObject.identifier );

	if (input && questObject.value)
	{
		input.checked = Boolean(questObject.value || 0); 

		let span = input.parentNode.parentNode;
		if (span && span.getAttribute('data-collapsible'))
		{
			span.style.display = 'block';
			document.getElementById('chk_' + span.id).checked = true;
			span.querySelectorAll('input, select').forEach( inp => inp.disabled = false );
		}
	}
}

function changeSelectField(questObject)
{
	let input = Array.from(frmSubs.elements).find( inp => inp.getAttribute('data-identifier') === questObject.identifier );

	if (input && questObject.value)
	{
		for (let opt of input.options)
			if (opt.value === questObject.value)
				opt.selected = true;

		let span = input.parentNode.parentNode;
		if (span && span.getAttribute('data-collapsible'))
		{
			span.style.display = 'block';
			document.getElementById('chk_' + span.id).checked = true;
			span.querySelectorAll('input, select').forEach( inp => inp.disabled = false );
		}
	}
}

function changeFormFromJson(jsonObject)
{	
	if (!jsonObject || !jsonObject.questions) return;

	if (jsonObject.name)
		document.getElementById('txtName').value = jsonObject.name;

	for (let quest of jsonObject.questions)
	{
		switch(quest.formInput.type)
		{
			case "radiobuttons":
				changeRadioField(quest);
				break;
			case "checkbox":
				changeCheckboxField(quest);
				break;
			case "combobox":
				changeSelectField(quest);
				break;
			case "text":
			case "date":
			default:
				changeTextField(quest);
				break;
		}
	}
}

window.addEventListener('load', function()
{
	frmSubs = document.getElementById("frmSubs");
	document.getElementById("txtEmail").onblur = txtEmail_onblur;
	
	document.getElementById("btnsubmitSubmitSubscription").onclick = btnsubmitSubmitSubscription_onClick;
});