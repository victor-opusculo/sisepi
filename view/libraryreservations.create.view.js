const getQueryParams = ( params, url ) => 
{
  let href = url;
  // this is an expression to get query strings
  let regexp = new RegExp( '[?&]' + params + '=([^&#]*)', 'i' );
  let qString = regexp.exec(href);
  return qString ? qString[1] : null;
};

function loadPublication(jsonObj)
{
	var obj = jsonObj;
	
	if (obj.error)
	{
		alert(obj.error);
		document.getElementById("pubTitle").innerText = "";
		document.getElementById("pubAuthor").innerText = "";
		document.getElementById("pubPublisher_edition").innerText = "";
		document.getElementById("pubVolume").innerText = "";
		document.getElementById("pubCopyNumber").innerText = "";
		document.getElementById("pubIsAvailable").innerHTML = "";
	}
	else
	{
		document.getElementById("pubTitle").innerText = obj.data.title;
		document.getElementById("pubAuthor").innerText = obj.data.author;
		document.getElementById("pubPublisher_edition").innerText = obj.data.publisher;
		document.getElementById("pubVolume").innerText = obj.data.volume;
		document.getElementById("pubCopyNumber").innerText = obj.data.copyNumber;
		document.getElementById("pubIsAvailable").innerHTML = obj.data.isAvailable ? `<img src="${fileBasePath}/pics/check.png" alt="Sim"/>` : `<img src="${fileBasePath}/pics/wrong.png" alt="Não"/>`;
	}
}

function loadUser(jsonObj)
{
	var obj = jsonObj;
	
	if (obj.error)
	{
		alert(obj.error);
		document.getElementById("userName").innerText = "";
		document.getElementById("userEmail").innerText = "";
		document.getElementById("userTelephone").innerText = "";
		document.getElementById("userTypeName").innerText = "";
	}
	else
	{
		document.getElementById("userName").innerText = obj.name;
		document.getElementById("userEmail").innerText = obj.email;
		document.getElementById("userTelephone").innerText = obj.telephone;
		document.getElementById("userTypeName").innerText = obj.typeName;
	}
}

function setPublicationIdInput(id)
{
	document.getElementById("frmCreateReservation").elements["numPubId"].value = id;
	btnLoadPublication_onClick();
}

function setUserIdInput(id)
{
	document.getElementById("frmCreateReservation").elements["numUserId"].value = id;
	btnLoadUser_onClick();
}

function preloadPubAndUserFromURLParams()
{
	var pubId = getQueryParams("pubId", window.location.search);
	var userId = getQueryParams("userId", window.location.search);
	
	if (pubId)
		setPublicationIdInput(pubId);
	
	if (userId)
		setUserIdInput(userId);
}

function btnLoadPublication_onClick(e)
{
	var id = document.getElementById("frmCreateReservation").elements["numPubId"].value;
	
	if (id)
	{
		fetch(fileBasePath + "/generate/libLoanGetSinglePublication.php?id=" + id).then( res => res.json() ).then( json => loadPublication(json) );
	}
}

function btnLoadUser_onClick(e)
{
	var id = document.getElementById("frmCreateReservation").elements["numUserId"].value;
	if (id)
		fetch(fileBasePath + "/generate/libLoanGetSingleUser.php?id=" + id).then( res => res.json() ).then( json => loadUser(json) );
}

function btnSearchPublication_onClick(e)
{
	var popup = window.open(popupURL.replace('{popup}', 'libselectpublication'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}

function btnSearchUser_onClick(e)
{
	var popup = window.open(popupURL.replace('{popup}','libselectuser'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
	popup.focus();
}

window.onload = function()
{
	document.getElementById("btnLoadPublication").onclick = btnLoadPublication_onClick;
	document.getElementById("btnLoadUser").onclick = btnLoadUser_onClick;
	document.getElementById("btnSearchPublication").onclick = btnSearchPublication_onClick;
	document.getElementById("btnSearchUser").onclick = btnSearchUser_onClick;
	
	preloadPubAndUserFromURLParams();
};