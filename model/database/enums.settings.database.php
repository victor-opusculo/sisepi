<?php
require_once("database.php");

function getEnum($enumName, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, value as name from enums where type = ?"))
	{
		$stmt->bind_param("s", $enumName);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				$dataRows[] = $row;
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function applyEnumChangesReport($JsonChangesReport, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$fullData = json_decode($JsonChangesReport);
	
	$affectedRows = 0;
	foreach ($fullData as $enum => $report)
	{
		if ($report->create)
		{
			$report2 = $report->create;
			foreach ($report2 as $createReg)
			{
				if($stmt = $conn->prepare("insert into enums (type, value) values (?, ?)"))
				{
					$stmt->bind_param("ss", $enum, $createReg->name);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
			}
		}
			
			
		if ($report->update)
		{
			
			$report2 = $report->update;
			foreach ($report2 as $updateReg)
			{
				if($stmt = $conn->prepare("update enums set value = ? where type = ? and id = ?"))
				{
					$stmt->bind_param("ssi", $updateReg->name, $enum, $updateReg->id);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
			}
		}
			
		if ($report->delete)
		{
			$report2 = $report->delete;
			foreach ($report2 as $deleteReg)
			{
				if($stmt = $conn->prepare("delete from enums where type= ? and id = ?"))
				{
					$stmt->bind_param("si", $enum, $deleteReg->id);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
			}
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function getEnumValues($enumName, mysqli $optConnection = null) : array
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$values = [];
	if($stmt = $conn->prepare("select value from enums where type = ?"))
	{
		$stmt->bind_param("s", $enumName);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			while ($dr = $result->fetch_row())
				$values[] = $dr[0];
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $values;
}