<?php

require_once("database.php");

if (!function_exists('formatProfessorNameCase'))
{
	function formatProfessorNameCase($fullName)
	{
		return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
	}
}

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
	$newId = null;

	if($stmt = $conn->prepare("insert into professors (`id`, `name`, `email`, `telephone`, `schoolingLevel`, `topicsOfInterest`, `lattesLink`, `agreesWithConsentForm`, `consentForm`,`registrationDate`) VALUES (NULL, aes_encrypt(?, '$__cryptoKey'), aes_encrypt(lower(?),'$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), 
	?, ?, now());"))
	{
		$stmt->bind_param("ssssssis", formatProfessorNameCase($postData["txtName"]), $postData["txtEmail"], $postData["txtTelephone"], $postData["radSchoolingLevel"], $postData["txtTopicsOfInterest"], $postData["txtLattesLink"], $postData["chkAgreesWithConsentForm"] , $postData["hidConsentFormTermId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$newId = $conn->insert_id;
		$stmt->close();
	}
	else
	{
		$conn->close();
		throw new Exception("Erro ao inserir dados no banco de dados.");
	}
	
	$conn->close();
	
	return [ "newId" => $newId, "isCreated" => $affectedRows > 0 ];
}

function checkIfProfessorIsRegistered($email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();
	
	$isRegistered = null;
	$query = "select count(id) from professors where email = aes_encrypt(lower(?), '$__cryptoKey')";
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

function getProfessorBasicInfosByEmail($email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();

	$query = "select id, aes_decrypt(name, '$__cryptoKey') as name from professors where email = aes_encrypt(lower(?), '$__cryptoKey')";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$ret = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $ret;
}

function getProfessorForLoginAuthentication($professorId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();

	$query = "select id, aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(email, '$__cryptoKey') as email from professors where id = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $professorId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$ret = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $ret;
}

function insertProfessorOTP($oneTimePassword, $professorId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "INSERT into professorsotps (professorId, oneTimePassword, expiryDateTime) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE)) ";
	$stmt = $conn->prepare($query);
	$hash = password_hash($oneTimePassword, PASSWORD_DEFAULT);
	$stmt->bind_param("is", $professorId, $hash);
	$stmt->execute(); 
	$affectedRows = $stmt->affected_rows;
	$newId = $conn->insert_id;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return [ 'isCreated' => $affectedRows > 0, 'newId' => $newId ];
}

function verifyProfessorOTP($professorOtpId, $givenPassword, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT oneTimePassword, professorId From professorsotps WHERE id = ? AND expiryDateTime >= NOW()";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $professorOtpId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$passed = false;
	$professorId = null;
	if ($result->num_rows > 0)
	{
		$dataRow = $result->fetch_assoc();
		$passed = password_verify($givenPassword, $dataRow['oneTimePassword']);
		if ($passed)
		{
			$professorId = $dataRow['professorId'];
			invalidateProfessorOTP($dataRow['professorId'], $conn);
		}
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception("Senha expirada! Tente gerar uma nova.");
	}
	$result->close();

	if (!$optConnection) $conn->close();
	return [ 'passed' => $passed, 'professorId' => $professorId ];
}

function invalidateProfessorOTP($professorId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "DELETE From professorsotps WHERE professorId = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $professorId);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function invalidateExpiredOTPs($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "DELETE From professorsotps WHERE expiryDateTime < Now()";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function authenticateProfessorCertificate($code, $issueDateTime, ?mysqli $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT professorcertificates.*, ws.eventId, events.name as 'eventName', ws.participationEventDataJson, aes_decrypt(prof.name, '$__cryptoKey') as professorName
	FROM professorcertificates
	LEFT JOIN professorworksheets AS ws ON ws.id = professorcertificates.workSheetId
	LEFT JOIN professors AS prof ON prof.id = ws.professorId
	LEFT JOIN events ON events.id = ws.eventId 
	WHERE professorcertificates.id = ? AND professorcertificates.dateTime = ? ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('is', $code, $issueDateTime);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRow;
}

function authenticateProfessorSignature($code, $signDateTime, ?mysqli $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT professorworkdocsignatures.*, ws.participationEventDataJson, aes_decrypt(prof.name, '$__cryptoKey') as professorName, jtem.templateJson as docTemplateJson
	FROM professorworkdocsignatures
	LEFT JOIN professorworksheets AS ws ON ws.id = professorworkdocsignatures.workSheetId
	LEFT JOIN professors AS prof ON prof.id = professorworkdocsignatures.professorId
	LEFT JOIN jsontemplates AS jtem ON jtem.type = 'professorworkdoc' AND jtem.id = ws.professorDocTemplateId
	WHERE professorworkdocsignatures.id = ? AND professorworkdocsignatures.signatureDateTime = ? ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('is', $code, $signDateTime);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRow;
}