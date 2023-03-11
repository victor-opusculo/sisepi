<?php
require_once "../model/database/students.database.php";

header('Content-Type: application/json; charset=utf-8');

$email = $_GET["email"] ?? "";
$dataRow = getLastSubscriptionByEmail($email);

$subscriptionData = json_decode($dataRow['subscriptionDataJson'] ?? 'null');

$outputObject = [];

if ($dataRow !== null && $subscriptionData !== null)
{
	$outputObject['name'] = $dataRow['name'];
	$outputObject['questions'] = $subscriptionData->questions;
}

echo json_encode($outputObject);
exit();