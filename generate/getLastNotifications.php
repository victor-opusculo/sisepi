<?php
require_once "checkLogin.php";
require_once "../includes/Data/namespace.php";
require_once "../model/notifications/SentNotification.php";
require_once "../model/database/database.php";
require_once "../includes/URL/URLGenerator.php";

header('Content-Type: application/json; charset=utf-8');

$conn = createConnectionAsEditor();
$output = [ 'data' => [] ];
try
{
    $getter = new \SisEpi\Model\Notifications\SentNotification();
    $getter->userId = $_SESSION['userid'];
    $output['data']['unread'] = $getter->getUnreadCount($conn);
    $output['data']['lastNotifications'] = $getter->getMultiplePartially($conn, 1, 10, 'date', '');

    foreach ($output['data']['lastNotifications'] as $notObj)
    {
        $notObj->dateTime = date_create($notObj->dateTime)->format('d/m/Y H:i:s');
        $notObj->linkUrlInfos = URL\URLGenerator::generateSystemURL('notifications', 'notificationlink', $notObj->id);
    }
}
catch (Exception $e)
{
    $output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);