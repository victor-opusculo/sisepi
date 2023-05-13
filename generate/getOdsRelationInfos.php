<?php

require_once "checkLogin.php";
require_once "../vendor/autoload.php";

header('Content-Type: application/json; charset=utf-8');

$odsRelationId = isset($_GET["id"]) && \SisEpi\Model\Database\Connection::isId(($_GET["id"])) ? $_GET["id"] : null;
$output = [];

$conn = \SisEpi\Model\Database\Connection::create();
try
{
    $getter = new \SisEpi\Model\Ods\OdsRelation();
    $getter->id = $odsRelationId;

    $output['data'] = $getter->getSingle($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();