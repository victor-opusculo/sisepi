<?php

function createConnectionAsEditor()
{
	$serverName = "127.0.0.1";
	$userName = "sisepi_admin";
	$password = "Lt8Xn.NE05YyzAh(";
	$dbname = "sisepi";

	$conn = new mysqli($serverName, $userName, $password, $dbname);
	if ($conn->connect_error)
	{
		die("Connection failed! " . $conn->connect_error);
	}
	
	$conn->query("SET NAMES 'utf8';");
	
	return $conn;
}

function isId($param)
{
	return isset($param) && is_numeric($param) && $param > 0;
}