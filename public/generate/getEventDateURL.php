<?php

require_once("../model/Database/students.database.php");
require_once __DIR__ . "/../../vendor/autoload.php";

header('Content-Type: application/json; charset=utf-8');

$email = $_GET['email'] ?? '';
$eventId = $_GET['eventId'] ?? null;
$eventDateId = $_GET['eventDateId'] ?? null;

$conn = createConnectionAsEditor();

$wasEmailFound = checkIfSubscriptionsContain($eventId, $email, $conn);
$responseJson = [];

if ($wasEmailFound)
{
    $getter = new \SisEpi\Public\Model\Events\EventDate();
    $getter->id = $eventDateId;
    $url = $getter->getDateURL($conn);
    if (!empty($url))
        $responseJson['eventDateURL'] = $url;
    else
    {
        $responseJson['error'] = true;
        $responseJson['message'] = "URL não localizado!";
    }
}
else
{
    $responseJson['error'] = true;
    $responseJson['message'] = "E-mail não localizado entre as inscrições!";
}

$conn->close();

echo json_encode($responseJson);
exit();

