<?php
require_once("database.php");

function readSetting($name, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$returnedValue = "";
	$query = "SELECT value FROM settings WHERE name = ?";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$returnedValue = $result->fetch_row()[0];
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $returnedValue;
}

function readMultipleSettings($namesArray, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$returnedSettings = [];
	$query = "SELECT name, value FROM settings WHERE name = ?";
	if ($stmt = $conn->prepare($query))
	{
		foreach ($namesArray as $name)
		{
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$returnedSettings[$row["name"]] = $row["value"];
			$result->close();
		}
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $returnedSettings;
}

function updateSetting($name, $newValue, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$query = "UPDATE settings SET value = ? WHERE name = ?";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ss", $newValue, $name);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function getTermInfos($id, ?mysqli $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$stmt = $conn->prepare('SELECT * from terms WHERE id = ? ');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $dataRow;
}