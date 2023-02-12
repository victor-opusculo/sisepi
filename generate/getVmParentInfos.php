<?php

require_once "checkLogin.php";
require_once "../includes/Data/namespace.php";
require_once "../model/database/database.php";
require_once "../model/vereadormirim/VmParent.php";

header('Content-Type: application/json; charset=utf-8');

$parId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$conn = createConnectionAsEditor();
try
{
    $getter = new Model\VereadorMirim\VmParent();
    $getter->id = $parId;
    $getter->setCryptKey(getCryptoKey());

    $output['data'] = $getter->getSingleDataRow($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();