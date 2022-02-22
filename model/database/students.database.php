<?php
require_once("database.php");

function getEventSubscriptionListInfos($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("select id as eventId, name, subscriptionListNeeded, subscriptionListClosureDate, maxSubscriptionNumber from events where id = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$dataRow = $results->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getSubscriptionsCount($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	if($stmt = $conn->prepare("select count(*) from subscriptionstudents where eventid = ?"))
	{
		
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
	$totalRecords = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function getSubscriptionList($eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, eventId, 
	aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(socialName, '$__cryptoKey') as socialName, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(telephone, '$__cryptoKey') as telephone, 
	birthDate, 
	aes_decrypt(gender, '$__cryptoKey') as gender, aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, aes_decrypt(occupation, '$__cryptoKey') as occupation, aes_decrypt(accessibilityFeatureNeeded, '$__cryptoKey') as accessibilityFeatureNeeded,
	agreesWithConsentForm, consentForm, subscriptionDate from subscriptionstudents where eventId = ? order by subscriptionDate asc"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$dataRows = [];
		while ($row = $results->fetch_assoc())
			array_push($dataRows, $row);
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSingleSubscription($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("select id, eventId, 
	aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(socialName, '$__cryptoKey') as socialName, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(telephone, '$__cryptoKey') as telephone, 
	birthDate, 
	aes_decrypt(gender, '$__cryptoKey') as gender, aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, aes_decrypt(occupation, '$__cryptoKey') as occupation, aes_decrypt(nationality, '$__cryptoKey') as nationality, aes_decrypt(race, '$__cryptoKey') as race, aes_decrypt(stateUf, '$__cryptoKey') as stateUf,
	aes_decrypt(accessibilityFeatureNeeded, '$__cryptoKey') as accessibilityFeatureNeeded,
	agreesWithConsentForm, consentForm, subscriptionDate from subscriptionstudents where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		if ($results->num_rows > 0)
			$dataRow = $results->fetch_assoc();
		else
		{
			if (!$optConnection) $conn->close();
			throw new Exception("Inscrição não localizada.");
		}
		
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getAllSubscriptions($eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRows = null;
	if($stmt = $conn->prepare("select 
	aes_decrypt(name, '$__cryptoKey') as 'Nome', aes_decrypt(socialName, '$__cryptoKey') as 'Nome social', aes_decrypt(email, '$__cryptoKey') as 'e-mail', aes_decrypt(telephone, '$__cryptoKey') as 'Telefone', 
	birthDate as 'Data de nascimento', 
	aes_decrypt(gender, '$__cryptoKey') as 'Gênero', aes_decrypt(schoolingLevel, '$__cryptoKey') as 'Escolaridade', aes_decrypt(occupation, '$__cryptoKey') as 'Ocupação', aes_decrypt(nationality, '$__cryptoKey') as 'Nacionalidade', aes_decrypt(race, '$__cryptoKey') as 'Etnia', aes_decrypt(stateUf, '$__cryptoKey') as 'Estado',
	aes_decrypt(accessibilityFeatureNeeded, '$__cryptoKey') as 'Acessibilidade requerida',
	if(agreesWithConsentForm = 1, 'Sim', 'Não') as 'Concorda com o termo de consentimento', 
	consentForm as 'Termo de consentimento', 
	subscriptionDate as 'Data de inscrição'
	from subscriptionstudents where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		if ($results->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $results->fetch_assoc())
				$dataRows[] = $row;
		}	
		$results->close();	
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function doesSubscriptionExists($email, $eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$exists = false;
	$query = "SELECT count(id) FROM subscriptionstudents WHERE email = aes_encrypt(lower(?), '$__cryptoKey') AND eventId = ?";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("si", $email, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$exists = (bool)$result->fetch_row()[0];
	}

	if (!$optConnection) $conn->close();
	return $exists;
}

function createSubscription($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	if (doesSubscriptionExists($postData['txtEmail'], $postData['eventId'], $conn))
		throw new Exception("Inscrição já existente para este evento");

	$agreesWithConsentForm = !empty($postData['chkAgreesWithConsentForm']) ? 1 : 0;

	$affectedRows = 0;
	$newId = null;
	$query = "INSERT INTO subscriptionstudents (eventId, name, socialName, email, agreesWithConsentForm, consentForm, subscriptionDate) 
	VALUES (?, aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(lower(?), '$__cryptoKey'), ?, ?, NOW())";

	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("isssis", $postData['eventId'], $postData['txtName'], $postData['txtSocialName'], $postData['txtEmail'], $agreesWithConsentForm, $postData['hidConsentForm']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$newId = $conn->insert_id;
		$stmt->close();
	}

	if (!$optConnection) $conn->close();

	return [ 'newId' => $newId, 'isCreated' => $affectedRows > 0 ];
}

function updateSubscription($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	$query = "UPDATE subscriptionstudents SET name = aes_encrypt(?, '$__cryptoKey'), socialName = aes_encrypt(?, '$__cryptoKey') WHERE id = ?";
	
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ssi", $postData["txtName"], $postData["txtSocialName"], $postData["subscriptionId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function deleteSubscription($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from subscriptionstudents where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$success = $stmt->affected_rows > 0;
		$stmt->close();
	}
	
	deletePresenceRecordFromSubscriptionId($id, $conn);
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

include_once("generalsettings.database.php");

function getPresenceAppointment($eventId, $approvedOnly = false, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "";
	if ($approvedOnly)
	{
		$query = "select * from (select presencerecords.subscriptionId, aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name , aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email, floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
inner join subscriptionstudents on subscriptionstudents.id = presencerecords.subscriptionId
where presencerecords.eventId = ?
group by presencerecords.subscriptionId) as presencesTable where presencePercent >= " . readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn) . ";";
	}
	else
	{
		$query = "select subscriptionstudents.id as subscriptionId, aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name , aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email, floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from subscriptionstudents
left join presencerecords on presencerecords.subscriptionId = subscriptionstudents.id
where subscriptionstudents.eventId = ?
group by subscriptionstudents.id;";
	}
	
	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $eventId, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getPresenceAppointmentNoSubs($eventId, $approvedOnly = false, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "";
	if ($approvedOnly)
	{
		$query = "select * from (select aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email,  floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
where presencerecords.eventId = ? and subscriptionId is null
group by email) as presencesTable where presencePercent >= " . readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn) . ";";
	}
	else
	{
		$query = "select aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email,  floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
where presencerecords.eventId = ? and subscriptionId is null
group by email;";
	}

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $eventId, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getEventBasicInfos($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare(
	"SELECT events.id, events.name, events.subscriptionListNeeded, events.subscriptionListClosureDate, events.maxSubscriptionNumber, events.allowLateSubscriptions, events.posterImageAttachmentFileName, eventdates.date, enums.value AS 'typeName'
FROM `events`
INNER JOIN eventdates ON eventdates.eventId = events.id
RIGHT JOIN enums ON enums.type = 'EVENT' AND enums.id = events.typeId
WHERE events.id = ?
GROUP BY events.id, events.name"))
	{
		
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

//Get event date with professor name
function getEventDate($eventDateId , $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.* , aes_decrypt(professors.name, '$__cryptoKey') as 'professorName', (CONCAT( DATE, ' ', beginTime ) < NOW() AND DATE_ADD( CONCAT( DATE, ' ', endTime ) , INTERVAL 30 MINUTE) > NOW()) AS isPresenceListOpen 
	FROM eventdates 
	LEFT JOIN professors ON (eventdates.professorId = professors.id) 
	WHERE eventdates.id = ? "))
	{
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRow = $result->fetch_assoc();
		}
		
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getPresenceList($eventDateId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "
SELECT presencerecords.id, aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name, aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email
FROM presencerecords
INNER JOIN subscriptionstudents ON subscriptionstudents.id = presencerecords.subscriptionId
WHERE eventDateId = ?";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getPresenceListNoSubs($eventDateId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "
SELECT presencerecords.id, aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email
FROM presencerecords
WHERE eventDateId = ?
AND subscriptionId IS NULL";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSinglePresenceRecord($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("SELECT presencerecords.id, presencerecords.eventId, presencerecords.eventDateId, presencerecords.subscriptionId , aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name, aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email
FROM presencerecords
LEFT JOIN subscriptionstudents ON subscriptionstudents.id = presencerecords.subscriptionId
WHERE presencerecords.id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			 $dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getSinglePresenceRecordNoSubs($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("SELECT id, eventId, eventDateId, subscriptionId, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(name, '$__cryptoKey') as name
FROM presencerecords
WHERE id = ? and subscriptionId is null"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			 $dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function editSinglePresenceRecordNoSubs($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	
	if($stmt = $conn->prepare("UPDATE presencerecords SET name = aes_encrypt(?, '$__cryptoKey'), email = aes_encrypt(LOWER(?), '$__cryptoKey')
WHERE id = ?"))
	{
		$stmt->bind_param("ssi", $postData["txtName"], $postData["txtEmail"], $postData["presenceId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function deletePresenceRecord($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from presencerecords where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$success = ($stmt->affected_rows === 1);
		$stmt->close(); 
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function deletePresenceRecordFromSubscriptionId($subsId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from presencerecords where subscriptionId = ?"))
	{
		$stmt->bind_param("i", $subsId);
		$stmt->execute();
		$success = ($stmt->affected_rows >= 1);
		$stmt->close(); 
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function markPresence($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "insert into presencerecords (eventId, eventDateId, subscriptionId) values (?, ?, ?)";
	
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iii", $postData["eventId"], $postData["eventDateId"], $postData["selSubscriptionId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function markPresenceNoSubs($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "insert into presencerecords (eventId, eventDateId, email, name) values (?, ?, aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'))";
	
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iiss", $postData["eventId"], $postData["eventDateId"], $postData["txtEmail"], $postData["txtName"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function checkIfPresenceIsSigned($eventDateId, $subscriptionId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	if($stmt = $conn->prepare("select count(*) from presencerecords where (eventDateId = ? and subscriptionId = ? )"))
	{
		$stmt->bind_param("ii", $eventDateId, $subscriptionId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $count > 0;
}

function checkIfPresenceIsSignedNoSubs($eventDateId, $email, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	if($stmt = $conn->prepare("select count(*) from presencerecords where (eventDateId = ? and email = aes_encrypt(lower(?), '$__cryptoKey') )"))
	{
		$stmt->bind_param("is", $eventDateId, $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $count > 0;
}