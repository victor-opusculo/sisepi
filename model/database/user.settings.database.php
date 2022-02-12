<?php

require_once("database.php");

function getUser($userName, $passwordGiven, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("select * from users where name = ?"))
	{
		$stmt->bind_param("s", $userName);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			if (password_verify($passwordGiven, $row["passwordHash"]))
				$dataRow = $row;
		}
	}

	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

//Check if user name and password are valid
function testUserPassword($userName, $passwordGiven)
{
	$conn = createConnectionAsEditor();
	
	$count = 0;
	if($stmt = $conn->prepare("select name, passwordHash from users where name = ?"))
	{
		$stmt->bind_param("ss", $userName);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			if (password_verify($passwordGiven, $row["passwordHash"]))
				$count = 1;
		}
	}

	$conn->close();
	return $count > 0;
}

//Check if user name exists, if so, return it
function testUserName($userName)
{
	$conn = createConnectionAsEditor();
	$gottenUserName = null;
	
	if($stmt = $conn->prepare("select name from users where name = ?"))
	{
		$stmt->bind_param("s", $userName);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$gottenUserName = $row["name"];
		}
		
	}
	$conn->close();
	return $gottenUserName;
}

function updateUserName($oldUserName, $newUserName)
{
	//update user name if needed
	if (strcasecmp($oldUserName, $newUserName) !== 0)
	{
		$conn = createConnectionAsEditor();
		
		$doesNewUserNameAlreadyExist = false;
		$nameChanged = false;
		
		if($stmt = $conn->prepare("select name from users where name = ?"))
		{
			$stmt->bind_param("s", $newUserName);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			$doesNewUserNameAlreadyExist = ($result->num_rows > 0);
		}
		
		if (!$doesNewUserNameAlreadyExist)
			if($stmt = $conn->prepare("update users set name = ? where name = ?"))
			{
				$stmt->bind_param("ss", $newUserName, $oldUserName);
				$stmt->execute();
				$stmt->close();
				$nameChanged = true;
			}
	
		$conn->close();
		
		
		
		if ($doesNewUserNameAlreadyExist) throw new Exception("Nome de usuário já existente.");
		
		if ($nameChanged) return true;
		else return false;
	}
}

function updateUserPassword($currentUserName, $currentPassword, $newPassword)
{
	if ($newPassword !== "")
	{
		$conn = createConnectionAsEditor();
		
		$isOldPasswordCorrect = false;
		$isPasswordChanged = false;
		
		if($stmt = $conn->prepare("select passwordHash from users where name = ?"))
		{
			$stmt->bind_param("s", $currentUserName);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if ($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$isOldPasswordCorrect = password_verify($currentPassword, $row["passwordHash"]);
			}
		}
		
		//update password if needed
		if ($isOldPasswordCorrect)
			if($stmt = $conn->prepare("update users set passwordHash = ? where name = ?"))
			{
				$newPassHash = password_hash($newPassword, PASSWORD_DEFAULT);
				$stmt->bind_param("ss", $newPassHash, $currentUserName);
				$stmt->execute();
				$stmt->close();
				
				$isPasswordChanged = true;
			}
		
		
		$conn->close();
		
		if (!$isOldPasswordCorrect) throw new Exception("Senha atual incorreta.");
		if ($isPasswordChanged) return true;
		else return false;
	}
	
}

function getUserPermissions($userId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select * from userpermissions where userId = ?"))
	{
		$stmt->bind_param("i", $userId);
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

function getUsersList($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select * from users"))
	{
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

function getPermissionsList($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select * from permissions"))
	{
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

function updateOtherUserFullData($postData, $optConnection = null)
{
	$userId = $postData["selUser"];
	$permissionsSet = $postData["permission"] ? $postData["permission"] : [];
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	
	//Remove unchecked permissions (or ignore if permission does not exist)
	$deleteUncheckedPermissionsQuery = "DELETE FROM userpermissions WHERE userId = ? AND permMod = ? AND permId = ?";
	$existentUserPermissions = getUserPermissions($userId, $conn);
	if ($existentUserPermissions)
		foreach ($existentUserPermissions as $p)
		{
			if (!in_array($p["permMod"] . "_" . $p["permId"], $permissionsSet))
			{
				if($stmt = $conn->prepare($deleteUncheckedPermissionsQuery))
				{
					$stmt->bind_param("isi", $userId, $p["permMod"], $p["permId"]);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
			}
		}
	
	//Insert new checked permissions (or ignore if permission already exists)
	$insertNewPermissionsQuery = "INSERT INTO userpermissions (`userId`, `permMod`, `permId`) VALUES (?, ?, ?)  ON DUPLICATE KEY UPDATE userId = userId";
	foreach ($permissionsSet as $p)
	{
		$moduleName = substr($p, 0, strrpos($p, '_'));
		$permissionId = substr($p, strrpos($p, '_') + 1);
		
		if($stmt = $conn->prepare($insertNewPermissionsQuery))
		{
			$stmt->bind_param("isi", $userId, $moduleName, $permissionId);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();
		}
	}
	
	//Update password if needed
	if ($postData["txtManageUsersNewPassword"])
		if($stmt = $conn->prepare("update users set passwordHash = ? where id = ?"))
		{
			//$newPassHash = hash('sha256', $postData["txtManageUsersNewPassword"]);
			$newPassHash = password_hash($postData["txtManageUsersNewPassword"], PASSWORD_DEFAULT);
			$stmt->bind_param("si", $newPassHash, $userId);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();
		}
		
	if (!$optConnection) $conn->close();	
	
	return $affectedRows > 0;
}

function createNewUser($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("insert into users (id, name, passwordHash) values (null, ?, ?)"))
	{
		//$passHash = hash('sha256', $postData["newUserPassword"]);
		$passHash = password_hash($postData["newUserPassword"], PASSWORD_DEFAULT);
		$stmt->bind_param("ss", $postData["newUserName"], $passHash);
		$stmt->execute();
		$success = $stmt->affected_rows === 1;
		$stmt->close();
	}
		
	if (!$optConnection) $conn->close();
	
	return $success;
}

function deleteUser($userId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	if($stmt = $conn->prepare("delete from userpermissions where userId = ?"))
	{
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$stmt->close();
	}
	
	$success = false;
	if($stmt = $conn->prepare("delete from users where id = ?"))
	{
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$success = $stmt->affected_rows === 1;
		$stmt->close();
	}
		
	if (!$optConnection) $conn->close();
	
	return $success;
}