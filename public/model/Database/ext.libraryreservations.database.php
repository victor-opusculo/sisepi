<?php
//Public
require_once("database.php");

define ("NumOfDaysAReservationIsValidAfterPubReturn", "2");

function getPublicationAvailableDatetime($pubId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "select if(expectedReturnDatetime > returnDatetime, expectedReturnDatetime, returnDatetime) as availableDatetime from 
					 (SELECT max(libraryborrowedpublications.expectedReturnDatetime) as expectedReturnDatetime, MAX(libraryborrowedpublications.returnDatetime) as returnDatetime 
					 from libraryborrowedpublications WHERE libraryborrowedpublications.publicationId = ?) as bpubsDatetimes";
	$availableDatetime = "";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $pubId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$availableDatetime = $result->fetch_row()[0];
	}
	
	return $availableDatetime;
}

function getReservationLastInvalidatedDatetime($pubId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT MAX(invalidatedDatetime) FROM libraryreservations WHERE publicationId = ?";
	$availableDatetime = "";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $pubId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$availableDatetime = $result->fetch_row()[0];
	}
	
	return $availableDatetime;
}

function invalidatePendingAndOldReservations($pubId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$query = "SELECT * FROM libraryreservations WHERE publicationId = ? AND borrowedPubId is null AND invalidatedDatetime is null ORDER BY reservationDatetime ASC";
	$dataRows = [];
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $pubId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
			while ($row = $result->fetch_assoc())
				$dataRows[] = $row;
	}
	
	if (count($dataRows) > 0)
	{
		$lastBorrowedPubAvailableDatetimeString = getPublicationAvailableDatetime($pubId, $conn);
		$lastBorrowedPubAvailableDatetime = isset($lastBorrowedPubAvailableDatetimeString) ? new DateTime($lastBorrowedPubAvailableDatetimeString) : new DateTime($dataRows[0]["reservationDatetime"]);
			
		$baseDatetime = null;
		if ($lastReservationInvalidatedDatetime = getReservationLastInvalidatedDatetime($pubId, $conn))
		{
			$lastReservationInvalidatedDatetime = new DateTime($lastReservationInvalidatedDatetime);
			$baseDatetime = $lastBorrowedPubAvailableDatetime > $lastReservationInvalidatedDatetime ? $lastBorrowedPubAvailableDatetime : $lastReservationInvalidatedDatetime;
		}
		else
			$baseDatetime = $lastBorrowedPubAvailableDatetime;
			
		$baseDatetime = $baseDatetime->format("Y-m-d H:i:s");
		
		$reservationsExpiryDatesTable = [];
		$currentDatetime = $baseDatetime;
		foreach ($dataRows as $dr)
		{
			$dt = new DateTime($currentDatetime);
			$dt->add(DateInterval::createFromDateString(NumOfDaysAReservationIsValidAfterPubReturn . " weekdays"));
			$reservationsExpiryDatesTable[$dr["id"]] = $currentDatetime = $dt->format("Y-m-d H:i:s");
		}
					
		foreach ($reservationsExpiryDatesTable as $k => $v)
		{
			$dtRes = new DateTime($v);
			$dtNow = new DateTime(date("Y-m-d H:i:s"));
			
			if ($dtNow > $dtRes)
				$affectedRows += invalidateReservation($k, $dtRes, $conn);
		}
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function invalidateReservation($reservationId, $invalidatedDatetime, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "UPDATE libraryreservations SET invalidatedDatetime = ? WHERE id = ?";
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$idt = $invalidatedDatetime->format("Y-m-d H:i:s");
		$stmt->bind_param("si", $idt, $reservationId);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows;
}