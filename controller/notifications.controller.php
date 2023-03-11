<?php
require_once "model/database/database.php";

final class notifications extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Notificações";
		$this->subtitle = "Notificações";
    }

    public function home()
    {
        require_once "controller/component/Paginator.class.php";
        require_once "controller/component/NotificationList.class.php";
        require_once "model/notifications/SentNotification.php";

        $paginatorComponent = null;
        $unreadCount = 0;
        $notificationListComponent = null;
        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \Model\Notifications\SentNotification();
            $getter->userId = (int)$_SESSION['userid'] ?? 0;

            $unreadCount = $getter->getUnreadCount($conn);
            $paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
            $notificationsObjects = $getter->getMultiplePartially($conn,    $paginatorComponent->pageNum, 
                                                                            $paginatorComponent->numResultsOnPage, 
                                                                            $_GET['orderBy'] ?? '',
                                                                            $_GET['q'] ?? '');
            $notificationListComponent = new NotificationList([ 'sentNotificationObjects' => $notificationsObjects ]);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['pagComp'] = $paginatorComponent;
        $this->view_PageData['unreadCount'] = $unreadCount;
        $this->view_PageData['notListComp'] = $notificationListComponent;
    }

    public function pre_subscribe()
    {
        $this->title = "SisEPI - Notificações: Inscrever-se";
		$this->subtitle = "Notificações: Inscrever-se";
    }

    public function subscribe()
    {
        require_once "model/notifications/Notification.php";
        require_once "model/notifications/UserNotificationSubscription.php";
        require_once "model/notifications/classes/definitions.php";

        $conn = createConnectionAsEditor();
        $notificationsAvailable = null;
        $subscribed = null;
        try
        {
            $getter = new \Model\Notifications\Notification();
            $notificationsAvailable = $getter->getAllNotifications($conn);

            $getter2 = new \Model\Notifications\UserNotificationSubscription();
            $getter2->userId = $_SESSION['userid'];
            $subscribed = $getter2->getAll($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['notificationsAvailable'] = $notificationsAvailable;
        $this->view_PageData['subscribed'] = $subscribed;
        $this->view_PageData['notificationDefinitions'] = \Model\Notifications\Classes\NotificationsDefinitions::get();
    }

    public function pre_setconditions()
    {
        $this->title = "SisEPI - Notificações: Condições";
		$this->subtitle = "Notificações: Condições";
    }

    public function setconditions()
    {;
        require_once "model/notifications/classes/definitions.php";

        $conditionsComponent = null;
        $conn = createConnectionAsEditor();
        try
        {
            [ $mod, $id ] = explode('_', $_GET['id'] ?? '');
            $notificationDefs = \Model\Notifications\Classes\NotificationsDefinitions::get();

            if (empty($notificationDefs[$mod][(string)$id]['conditionsComponentName']))
                throw new Exception('Tipo de notificação não encontrada ou sem condições disponíveis.');

            $identifierName = $notificationDefs[$mod][(string)$id]['conditionsComponentName'];
            $className = "\\Controller\\Component\\NotificationsConditions\\$identifierName"; 
            require_once "controller/component/notificationsconditions/$identifierName.class.php"; 

            $conditionsComponent = new $className(
            [
                'connection' => $conn,
                'titleName' => $notificationDefs[$mod][(string)$id]['name'],
                'notificationModule' => $mod,
                'notificationId' => (int)$id,
                'userId' => $_SESSION['userid'] ?? 0
            ]);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['condComp'] = $conditionsComponent;
    }

    public function pre_notificationlink() { }

    public function notificationlink()
    {
        require_once "model/notifications/SentNotification.php";

        $notId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \Model\Notifications\SentNotification();
            $getter->userId = $_SESSION['userid'];
            $getter->id = $notId;
            $notification = $getter->getSingle($conn);
            $notification->isRead = 1;
            $notification->save($conn);

            header('location:' . URl\URLGenerator::decodeJSONStruct($notification->linkUrlInfos));
        }
        catch (Exception $e)
        {
            header('location:' . URl\URLGenerator::generateSystemURL('homepage'));
        }
        finally { $conn->close(); }
    }

    public function pre_test()
    {
    }

    public function test()
    {
        require_once "model/database/database.php";
        require_once "model/notifications/classes/ProfessorSignedWorkDocNotification.php";

        $conn = createConnectionAsEditor();
        $not = new \Model\Notifications\Classes\ProfessorSignedWorkDocNotification([
            'workProposalId' => 8, 
        'workSheetId' => 3, 
        'professorId' => 1
     ]);
        $afrows = $not->push($conn);
        $conn->close();
        var_export($afrows);

    }
}