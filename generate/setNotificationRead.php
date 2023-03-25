<?php

require_once "checkLogin.php";
require_once "../includes/Data/namespace.php";
require_once "../vendor/autoload.php";
require_once "../model/Database/database.php";
require_once "../includes/URL/URLGenerator.php";

header('Content-Type: application/json; charset=utf-8');

$conn = createConnectionAsEditor();
$output = [];
try
{
    $getter = new \SisEpi\Model\Notifications\SentNotification();
    $getter->userId = $_SESSION['userid'];
    $getter->id = $_GET['id'];
    $notification = $getter->getSingle($conn);
    $notification->isRead = $_GET['read'] == 1 ? 1 : 0;
    $affectedRows = $notification->save($conn);

    $output['data'] = [];
    
    if ($affectedRows < 1)
        throw new Exception('Status não alterado.');

    $output['data']['unread'] = $getter->getUnreadCount($conn);
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);