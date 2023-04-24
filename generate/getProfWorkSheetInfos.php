<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../vendor/autoload.php";

header('Content-Type: application/json; charset=utf-8');

$workSheetId = isset($_GET["id"]) && Connection::isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$conn = Connection::create();
try
{
	$getter = new \SisEpi\Model\Professors\ProfessorWorkSheet();
	$getter->setCryptKey(Connection::getCryptoKey());
	$getter->id = $workSheetId;

	$output['data'] = $getter->getSingle($conn);
	$output['data']->paymentValue = $output['data']->getPaymentValue() ?? 0;
}
catch (Exception $e)
{
	$output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();