

window.addEventListener("load", function(e)
{
	document.getElementById("btnsubmitChangeUserData").onclick = function(e)
	{
		let pass1 = document.getElementById("txtNewPassword").value;
		let pass2 = document.getElementById("txtNewPassword2").value;
		
		if (pass1 !== pass2)
		{
			e.preventDefault();
			alert("A nova senha não coincide com a confirmação!");
			
			document.getElementById("txtNewPassword2").focus();
		}
	};
	
});