<?php

if (!function_exists('getDatabaseConfig'))
{
	function getDatabaseConfig()
	{
		$configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/sisepi_config.ini", true);
		putenv("CRYPTO_KEY=" . $configs['database']['crypto']);
		return $configs['database'];
	}
}

if (!function_exists("getCryptoKey"))
{
	function getCryptoKey()
	{
		return !empty(getenv("CRYPTO_KEY")) ? getenv("CRYPTO_KEY") : getDatabaseConfig()['crypto'];
	}
}

if (!function_exists('createConnectionAsEditor'))
{
	function createConnectionAsEditor()
	{
		$configs = getDatabaseConfig(); 
		
		$serverName = $configs['servername'];
		$userName = $configs['username'];
		$password = $configs['password'];
		$dbname = $configs['dbname'];

		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		$conn = new mysqli($serverName, $userName, $password, $dbname);
		if ($conn->connect_error)
		{
			die("Connection failed! " . $conn->connect_error);
		}
		
		//$conn->query("SET NAMES 'utf8';");
		$conn->set_charset('utf8mb4');
		
		return $conn;
	}
}

if (!function_exists('isId'))
{
	function isId($param)
	{
		return isset($param) && is_numeric($param) && $param > 0;
	}
}