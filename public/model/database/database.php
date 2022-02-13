<?php

function getDatabaseConfig()
{
	$configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/sisepi_config.ini");
	putenv("CRYPTO_KEY=" . $configs['crypto']);
	return $configs;
}

function getCryptoKey()
{
	return !empty(getenv("CRYPTO_KEY")) ? getenv("CRYPTO_KEY") : getDatabaseConfig()['crypto'];
}

function createConnectionAsEditor()
{
	$configs = getDatabaseConfig(); 
	
	$serverName = $configs['servername'];
	$userName = $configs['username'];
	$password = $configs['password'];
	$dbname = $configs['dbname'];

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