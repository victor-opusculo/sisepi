<?php
require_once("checkLogin.php");
require_once "../model/database/database.php";
require_once "../model/professors/ProfessorWorkProposal.php";

header('Content-Type: application/json; charset=utf-8');

$wpId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$conn = createConnectionAsEditor();
try
{
	$getter = new \Model\Professors\ProfessorWorkProposal();
	$getter->id = $wpId;
	$getter->setCryptKey(getCryptoKey());
	$output['data'] = $getter->getSingle($conn);
}
catch (Exception $e)
{
	$output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();