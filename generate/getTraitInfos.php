<?php

require_once("checkLogin.php");
require_once("../includes/Data/namespace.php");
require_once("../model/database/database.php");
require_once "../model/traits/EntityTrait.php";

header('Content-Type: application/json; charset=utf-8');

$traitId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$conn = createConnectionAsEditor();
try
{
    $getter = new \SisEpi\Model\Traits\EntityTrait();
    $getter->id = $traitId;

    $output['data'] = $getter->getSingleDataRow($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();