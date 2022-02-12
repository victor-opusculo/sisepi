function lnkDelUser_onClick(e)
{
	e.preventDefault();
			
	if (Number(document.getElementById("selUser").value) === currentUserId)
	{
		alert("Você não pode excluir o próprio usuário enquanto estiver logado. Peça para outro usuário com permissão adequada para excluir sua conta.");
		return;
	}
	
	var confirmDel = confirm("Confirmar a exclusão deste usuário? Esta operação é irreversível!");
	if (confirmDel)
	{
		let frm = (function()
		{
			let form = document.createElement("form");
			form.style.display = "none";
			form.method = "post";
			form.action = postUserSettingsURL;
			
			let hiddenFormName = document.createElement("input");
			hiddenFormName.name = "frmDelUser";
			hiddenFormName.value = 1;
			
			let hiddenUserId = document.createElement("input");
			hiddenUserId.name = "delUserId";
			hiddenUserId.value = document.getElementById("selUser").value;
			
			form.appendChild(hiddenUserId);
			form.appendChild(hiddenFormName);
			
			return form;
		})();
		
		document.body.appendChild(frm);
		frm.submit();
	}
}

function checkSetManUserPermission_onClick_blockChange(e)
{
	this.checked = true;
	alert("Não é possível remover a permissão de gerenciar usuários e permissões da sua própria conta, somente de outras contas.");
}

function lnkNewUser_onClick(e)
{
	e.preventDefault();
	let userName = prompt("Digite o nome do novo usuário");
	if (userName === null) return;
	let password = prompt("Digite a senha do novo usuário");
	if (password === null) return;
	
	if (userName && password)
	{
		let frm = (function()
		{
			let form = document.createElement("form");
			form.style.display = "none";
			form.method = "post";
			form.action = postUserSettingsURL;
			
			let hiddenFormName = document.createElement("input");
			hiddenFormName.name = "frmNewUser";
			hiddenFormName.value = 1;
			
			let hiddenName = document.createElement("input");
			hiddenName.name = "newUserName";
			hiddenName.value = userName;
			
			let hiddenPassword = document.createElement("input");
			hiddenPassword.name = "newUserPassword";
			hiddenPassword.value = password;
			
			form.appendChild(hiddenName);
			form.appendChild(hiddenPassword);
			form.appendChild(hiddenFormName);
			
			return form;
		})();
		
		document.body.appendChild(frm);
		frm.submit();
	}
	else
		alert("Usuário novo não pôde ser criado. Nome ou senha nulos.");
}

window.addEventListener("load", function(e)
{
	document.getElementById("lnkNewUser").onclick = lnkNewUser_onClick;
	document.getElementById("lnkDelUser").onclick = lnkDelUser_onClick;
	
	document.getElementById("selUser").onchange = function(e)
	{
		window.location.href = thisPageURL.replace("{paramname}", "umUserId").replace("{paramvalue}", String(this.value));
	};
});