<?php

require_once("database.php");


function getEventsList($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$result = $conn->query("select id, name from events order by name");
	while ($row = $result->fetch_assoc())
		array_push($dataRows, $row);
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSingleEmail($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
left join events on events.id = mailing.eventId
where mailing.id = ?";

	$dataRow = null;

	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function deleteEmail($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from mailing where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$success = $stmt->affected_rows === 1;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function getMailingCount($eventId, $_searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$searchKeywords = (isset($_searchKeywords) && strlen($_searchKeywords) > 3) ? $_searchKeywords : "";
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	$query = "";
	if (!$eventId && !$searchKeywords)
		$query = "SELECT count(distinct email) from mailing";
	else if ($eventId && !$searchKeywords)
		$query = "SELECT count(*) from mailing where eventId = ?";
	else if (!$eventId && $searchKeywords)
		$query = "SELECT count(*) from mailing where (convert(aes_decrypt(mailing.email, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(mailing.name, '$__cryptoKey') using 'UTF8MB4') like ?)";
	else if ($eventId && $searchKeywords)
		$query = "SELECT count(*) from mailing where (eventId = ?) and (convert(aes_decrypt(mailing.email, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(mailing.name, '$__cryptoKey') using 'UTF8MB4') like ?)";
		
	
	if($stmt = $conn->prepare($query))
	{
		$searchParam = "%" . $searchKeywords . "%";
		
		if ($eventId && !$searchKeywords)
			$stmt->bind_param("i", $eventId);
		else if (!$eventId && $searchKeywords)
			$stmt->bind_param("ss", $searchParam, $searchParam);
		else if ($eventId && $searchKeywords)
			$stmt->bind_param("iss", $eventId, $searchParam, $searchParam);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count;
}


function getMailingPartially($page, $numResultsOnPage, $__orderBy, $eventId, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "email" : $__orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "";
	
	$buildQuery = function() use ($__cryptoKey, $_orderBy, $eventId, $searchKeywords)
	{
		$outputInfos = [ "query" => "", "event" => false, "search" => false ];
		
		$base = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as demail, aes_decrypt(mailing.name, '$__cryptoKey') as dname, events.name as eventName
from mailing
left join events on events.id = mailing.eventId ";

		$where = (isset($eventId) && is_numeric($eventId) || strlen($searchKeywords) > 3) ? "where " : "";
		$whereEvent = "";
		if (isset($eventId) && is_numeric($eventId))
		{
			$whereEvent = "(eventId = ?) ";
			$outputInfos["event"] = true;
		}
		
		if ($whereEvent) $where .= $whereEvent;
		
		$whereSearch = "";
		if (strlen($searchKeywords) > 3)
		{
			$whereSearch = "(convert(aes_decrypt(mailing.email, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(mailing.name, '$__cryptoKey') using 'UTF8MB4') like ?) ";
			$outputInfos["search"] = true;
			
			if ($whereEvent) $where .= " and ";
		}
		
		if ($whereSearch) $where .= $whereSearch;
		
		$groupBy = "";
		if ($whereEvent === "" && $whereSearch === "")
			$groupBy = "group by demail ";
		
		$orderBy = "";
		if ($_orderBy === "email")
			$orderBy = "order by convert(demail using 'UTF8MB4') ";
		else
			$orderBy = "order by convert(dname using 'UTF8MB4') ";
		
		$limit = "limit ?, ?";
		
		$outputInfos["query"] = $base . $where . $groupBy . $orderBy . $limit;
		
		return $outputInfos;
	};
	
	$infos = $buildQuery();
	$query = $infos["query"];
		
	if($stmt = $conn->prepare($query))
	{
		$calc_page = ($page - 1) * $numResultsOnPage;
		$searchParam = "%" . $searchKeywords . "%";
		
		if (!$infos["event"] && !$infos["search"])
			$stmt->bind_param("ii", $calc_page, $numResultsOnPage);
		else if ($infos["event"] && !$infos["search"])
			$stmt->bind_param("iii", $eventId, $calc_page, $numResultsOnPage);
		else if (!$infos["event"] && $infos["search"])
			$stmt->bind_param("ssii", $searchParam, $searchParam, $calc_page, $numResultsOnPage);
		else if ($infos["event"] && $infos["search"])
			$stmt->bind_param("issii", $eventId, $searchParam, $searchParam, $calc_page, $numResultsOnPage);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
			{
				$dataRows[] = $row;
			}
		}
	}
		
	if (!$optConnection) $conn->close();
	
	return $dataRows;

}

function getFullMailing($__orderBy, $eventId, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "email" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$buildQuery = function() use ($__cryptoKey, $_orderBy, $eventId, $searchKeywords)
	{
		$outputInfos = [ "query" => "", "event" => false, "search" => false ];
		
		$base = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
left join events on events.id = mailing.eventId ";

		$where = (isset($eventId) && is_numeric($eventId) || strlen($searchKeywords) > 3) ? "where " : "";
		$whereEvent = "";
		if (isset($eventId) && is_numeric($eventId))
		{
			$whereEvent = "(eventId = ?) ";
			$outputInfos["event"] = true;
		}
		
		if ($whereEvent) $where .= $whereEvent;
		
		$whereSearch = "";
		if (strlen($searchKeywords) > 3)
		{
			$whereSearch = "(convert(aes_decrypt(mailing.email, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(mailing.name, '$__cryptoKey') using 'UTF8MB4') like ?) ";
			$outputInfos["search"] = true;
			
			if ($whereEvent) $where .= " and ";
		}
		
		if ($whereSearch) $where .= $whereSearch;
		
		$groupBy = "";
		if ($whereEvent === "" && $whereSearch === "")
			$groupBy = "group by email ";
		
		$orderBy = "";
		if ($_orderBy === "email")
			$orderBy = "order by email ";
		else
			$orderBy = "order by name ";
		
		$outputInfos["query"] = $base . $where . $groupBy . $orderBy;
		
		return $outputInfos;
	};
	
	$infos = $buildQuery();
	$query = $infos["query"];
		
	if($stmt = $conn->prepare($query))
	{
		$searchParam = "%" . $searchKeywords . "%";
		
		if ($infos["event"] && !$infos["search"])
			$stmt->bind_param("i", $eventId);
		else if (!$infos["event"] && $infos["search"])
			$stmt->bind_param("ss", $searchParam, $searchParam);
		else if ($infos["event"] && $infos["search"])
			$stmt->bind_param("iss", $eventId, $searchParam, $searchParam);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
			{
				$dataRows[] = $row;
			}
		}
	}
	
	/*if (isset($eventId) && is_numeric($eventId))
	{
		if ($orderBy === "email")
			$query = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
inner join events on events.id = mailing.eventId
where eventId = ? 
order by email";
		else
			$query = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
inner join events on events.id = mailing.eventId
where eventId = ? order by name";
		
		if($stmt = $conn->prepare($query))
		{
			
			$stmt->bind_param("i", $eventId);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if ($result->num_rows > 0)
			{
				$dataRows = [];
				while ($row = $result->fetch_assoc())
				{
					array_push($dataRows, $row);
				}
			}
		}
	}
	else
	{
		if ($orderBy === "email")
			$query = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
left join events on events.id = mailing.eventId
group by email
order by email";
		else
			$query = "SELECT mailing.id, aes_decrypt(mailing.email, '$__cryptoKey') as email, aes_decrypt(mailing.name, '$__cryptoKey') as name, events.name as eventName
from mailing
left join events on events.id = mailing.eventId
group by email order by name";
		
		if($stmt = $conn->prepare($query))
		{
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if ($result->num_rows > 0)
			{
				$dataRows = [];
				while ($row = $result->fetch_assoc())
				{
					array_push($dataRows, $row);
				}
			}
		}
	}*/
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}