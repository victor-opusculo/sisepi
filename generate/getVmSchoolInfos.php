<?php

require_once "checkLogin.php";
require_once "../includes/Data/namespace.php";
require_once "../vendor/autoload.php";

header('Content-Type: application/json; charset=utf-8');

$schoolId = isset($_GET["id"]) && \SisEpi\Model\Database\Connection::isId(($_GET["id"])) ? $_GET["id"] : null;
$output = [];

$conn = \SisEpi\Model\Database\Connection::create();
try
{
    $getter = new \SisEpi\Model\VereadorMirim\School();
    $getter->id = $schoolId;
    $getter->setCryptKey(\SisEpi\Model\Database\Connection::getCryptoKey());

    $output['data'] = $getter->getSingle($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();