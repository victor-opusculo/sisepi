<?php
//Public
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