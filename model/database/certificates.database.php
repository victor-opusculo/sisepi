<?php
//Private
require_once("database.php");


function getEventInfos($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
    $query = "SELECT events.id, events.name, MIN(eventdates.date) AS beginDate, MAX(eventdates.date) as endDate, enums.value as 'typeName', subscriptionListNeeded 
    FROM `events` 
    INNER JOIN eventdates ON eventdates.eventId = events.id 
    inner JOIN enums on enums.type = 'EVENT' and enums.id = events.typeId
    where events.id = ?
    GROUP BY events.id, events.name";

	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
        if ($results->num_rows > 0)
		    $dataRow = $results->fetch_assoc();
        $results->close();
	}

	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function _getCertificates_dbStatement($query, $eventId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $dataRows = null;
    if ($stmt = $conn->prepare($query))
    {
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        if ($results->num_rows > 0)
            $dataRows = $results->fetch_all(MYSQLI_ASSOC);

        $results->close();
    }
    if (!$optConnection) $conn->close();

    return $dataRows;
}

function getCertificates($eventId, $isSubscriptionEnabled, $optConnection = null)
{
    $__cryptoKey = getCryptoKey();

    $query = $isSubscriptionEnabled === true ?
    (
        "SELECT aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name, 
        aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson, 
        aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email,
        certificates.id,
        certificates.dateTime
        from certificates
        inner join subscriptionstudentsnew on subscriptionstudentsnew.email = certificates.email and subscriptionstudentsnew.eventId = certificates.eventId
        where certificates.eventId = ?
        order by name asc"
    )
    :
    (
        "SELECT aes_decrypt(presencerecords.name, '$__cryptoKey') as name,
        aes_decrypt(presencerecords.email, '$__cryptoKey') as email,
        certificates.id,
        certificates.dateTime
        from certificates
        inner join presencerecords on presencerecords.email = certificates.email and presencerecords.eventId = certificates.eventId
        where certificates.eventId = ?
        group by email
        order by name asc"
    );

    return _getCertificates_dbStatement($query, $eventId, $optConnection);
}

function getCertificatesCount($eventId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $count = 0;

    $query = "SELECT count(*) from certificates where eventId = ?";
    if ($stmt = $conn->prepare($query))
    {
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
    }

    if (!$optConnection) $conn->close();
    return $count;
}

function getAvailableCertificatesCount($eventId, $optConnection = null)
{
    require_once("generalsettings.database.php");

	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$stmt1 = $conn->prepare("select subscriptionListNeeded, certificateText from events where id = ?");
	$stmt1->bind_param("i", $eventId);
	$stmt1->execute();
    $result1_row = $stmt1->get_result()->fetch_row();
	$subscriptionEnabled = (bool)$result1_row[0];
    $certificateEnabled = !empty($result1_row[1]);
	$stmt1->close();

    if (!$certificateEnabled)
    {
        if (!$optConnection) $conn->close();
        return 0;
    }

	$query = "";
	if ($subscriptionEnabled)
		$query = "select presencerecords.subscriptionId, subscriptionstudentsnew.name, subscriptionstudentsnew.email, floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
inner join subscriptionstudentsnew on subscriptionstudentsnew.id = presencerecords.subscriptionId
where presencerecords.eventId = ?
group by presencerecords.subscriptionId";
	else
		$query = "select presencerecords.name, presencerecords.email, floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
where presencerecords.eventId = ? and subscriptionId is null
group by presencerecords.email";
	
	$dataRows = [];
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $eventId, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
            while ($dr = $result->fetch_assoc())
			    $dataRows[] = $dr;
        $result->close();
	}

	$minPercentageForApproval = (int)readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn);
	if (!$optConnection) $conn->close();
	
	return count(array_filter($dataRows, fn($dr) => $dr['presencePercent'] >= $minPercentageForApproval));
}