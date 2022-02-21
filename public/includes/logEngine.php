<?php
//Public

define('LOGS_PATH', __DIR__ . "/../../log");

function writeLog($actionMessage)
{
	if (!empty($actionMessage))
	{
		$userName = "Visitante";
	
		$logData =
		[
			date("d/m/Y H:i:s"),
			"IP: " . $_SERVER['REMOTE_ADDR'],
			"Usuário: " . $userName,
			$actionMessage
		];
		
		$logStringEntry = implode(" | ", $logData) . PHP_EOL;
		
		file_put_contents(LOGS_PATH . "/sisepi_" . date("M-Y") . ".log", $logStringEntry, FILE_APPEND);
	}
}

function writeErrorLog($actionMessage)
{
	if (!empty($actionMessage))
		writeLog("*** ERRO *** " . $actionMessage);
}