<?php

require_once "checkLogin.php";
require_once "../includes/Data/namespace.php";
require_once "../model/database/database.php";
require_once "../model/vereadormirim/Legislature.php";

header('Content-Type: application/json; charset=utf-8');

$legId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$conn = createConnectionAsEditor();
try
{
    $getter = new Model\VereadorMirim\Legislature();
    $getter->id = $legId;

    $output['data'] = $getter->getSingleDataRow($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();