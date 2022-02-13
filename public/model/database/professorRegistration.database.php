<?php

require_once("database.php");


function insertProfessorData($postData)
{	
	$conn = createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	if (checkIfProfessorIsRegistered($postData['txtEmail'], $conn))
	{
		$conn->close();
		throw new Exception("Você já está cadastrado em nosso banco de docentes.");
	}
	
	$affectedRows = 0;
	if($stmt = $conn->prepare("insert into professors (`id`, `name`, `email`, `telephone`, `schoolingLevel`, `topicsOfInterest`, `lattesLink`, `agreesWithConsentForm`, `consentForm`,`registrationDate`) VALUES (NULL, aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), 
	?, ?, now());"))
	{
		$stmt->bind_param("ssssssis", $postData["txtName"], $postData["txtEmail"], $postData["txtTelephone"], $postData["radSchoolingLevel"], $postData["txtTopicsOfInterest"], $postData["txtLattesLink"], $postData["chkAgreesWithConsentForm"] , $postData["txtConsentForm"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	else
	{
		$conn->close();
		throw new Exception("Erro ao inserir dados no banco de dados.");
	}
	
	$conn->close();
	
	return $affectedRows > 0;
}

function checkIfProfessorIsRegistered($email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();
	
	$isRegistered = null;
	$query = "select count(id) from professors where email = aes_encrypt(?, '$__cryptoKey')";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$isRegistered = (int)$result->fetch_row()[0] > 0;
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $isRegistered;
}