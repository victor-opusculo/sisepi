const enumsChangeReport =  {};



function btnDeleteEnumType_onClick(e)
{
	let itemId = this.parentNode.getAttribute("data-id");
	if (itemId)
	{
		let enumName = this.parentNode.parentNode.className;
		enumsChangeReport[enumName].delete.push( { id: itemId } );
	}
	
	this.parentNode.parentNode.removeChild(this.parentNode);
}

function btnAddEnumType_onClick(e)
{
	let ol = this.parentNode.querySelector("ol");
	let newLi = document.createElement("li");
	let input = document.createElement("input");
	let btnDel = document.createElement("input");
	
	input.type = "text";
	input.size = 40;
	
	btnDel.type = "button";
	btnDel.value = "X"
	btnDel.className = "btnDeleteEnumType";
	btnDel.onclick = btnDeleteEnumType_onClick;
	
	newLi.appendChild(input);
	newLi.appendChild(btnDel);
	ol.appendChild(newLi);
}

function btnsubmitEditEnums_onClick(e)
{
	let frm = document.getElementById("frmEditEnums");
	let fieldsets = frm.querySelectorAll("fieldset");
	
	fieldsets.forEach( fieldset => 
	{
		if (fieldset.querySelector("ol"))
		{
			let li = fieldset.querySelectorAll("ol li");
			if (!li.length)
			{
				let legend = fieldset.querySelector("legend").innerText;
				alert("Erro: Crie pelo menos um item no enumerador " + legend);
				e.preventDefault();
			}
		}
	});
	
	generateEnumsChangesReport();
	
	document.getElementById("hiddenJsonChangesReport").value = JSON.stringify(enumsChangeReport);
}

function generateEnumsChangesReport()
{
	let frm = document.getElementById("frmEditEnums");
	let fieldsets = frm.querySelectorAll("fieldset");
	
	fieldsets.forEach( fieldset =>
	{
		if (fieldset.querySelector("ol"))
		{
			let enumName = fieldset.querySelector("ol").className;
			let li = fieldset.querySelectorAll("ol li");
			
			enumsChangeReport[enumName].update = [];
			enumsChangeReport[enumName].create = [];
			
			li.forEach( li =>
			{
				let id = li.getAttribute("data-id");
				let input = li.querySelector("input[type='text']");
				
				if (id)
					enumsChangeReport[enumName].update.push( { id: id, name: input.value } );
				else
					enumsChangeReport[enumName].create.push( { name: input.value } );
			});
		}
		
	});
}

window.addEventListener("load", function(e)
{
	document.querySelectorAll(".btnDeleteEnumType").forEach( btn => btn.onclick = btnDeleteEnumType_onClick );
	document.querySelectorAll(".btnAddEnumType").forEach( btn => btn.onclick = btnAddEnumType_onClick );
	document.getElementById("btnsubmitEditEnums").onclick = btnsubmitEditEnums_onClick;
	
	//Prepare changes report object
	let frm = document.getElementById("frmEditEnums");
	let fieldsets = frm.querySelectorAll("fieldset");
	fieldsets.forEach( fieldset =>
	{
		if (fieldset.querySelector("ol"))
		{
			let enumName = fieldset.querySelector("ol").className;
			
			enumsChangeReport[enumName] = {};
			enumsChangeReport[enumName].create = null;
			enumsChangeReport[enumName].update = null;
			enumsChangeReport[enumName].delete = [];
		}
	});
});