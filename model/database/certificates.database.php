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

function getCertificates($eventId, $isSubscriptionEnabled, $optConnection = null)
{
    $__cryptoKey = getCryptoKey();

    $query = $isSubscriptionEnabled === true ?
    (
        "SELECT aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name, 
        aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, 
        aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email,
        certificates.id,
        certificates.dateTime
        from certificates
        inner join subscriptionstudents on subscriptionstudents.email = certificates.email and subscriptionstudents.eventId = certificates.eventId
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