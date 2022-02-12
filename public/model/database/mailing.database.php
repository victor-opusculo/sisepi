<?php
require_once("database.php");
require_once("crypto.php");

function checkIfMailingContains($email, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	if ($stmt = $results = $conn->prepare("select count(email) from mailing where email = aes_encrypt(lower(?), '$__cryptoKey')"))
	{
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$count = $stmt->get_result()->fetch_row()[0];
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $count > 0;
}

function createMailingSubscription($email, $name, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("insert into mailing (email, name) values 
	(aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey')) "))
	{		
		$stmt->bind_param("ss", $email, $name);
		$stmt->execute();
		$success = $stmt->affected_rows === 1;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function deleteMailingSubscription($email, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from mailing where email = aes_encrypt(lower(?), '$__cryptoKey')"))
	{		
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$success = $stmt->affected_rows >= 1;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}