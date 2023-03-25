<?php
require_once "model/Database/database.php";
require_once "vendor/autoload.php";

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

        $paginatorComponent = null;
        $unreadCount = 0;
        $notificationListComponent = null;
        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\Notifications\SentNotification();
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
        require_once "model/Notifications/Classes/definitions.php";

        $conn = createConnectionAsEditor();
        $notificationsAvailable = null;
        $subscribed = null;
        try
        {
            $getter = new \SisEpi\Model\Notifications\Notification();
            $notificationsAvailable = $getter->getAllNotifications($conn);

            $getter2 = new \SisEpi\Model\Notifications\UserNotificationSubscription();
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
        $this->view_PageData['notificationDefinitions'] = \SisEpi\Model\Notifications\Classes\NotificationsDefinitions::get();
    }

    public function pre_setconditions()
    {
        $this->title = "SisEPI - Notificações: Condições";
		$this->subtitle = "Notificações: Condições";
    }

    public function setconditions()
    {;
        require_once "model/Notifications/Classes/definitions.php";

        $conditionsComponent = null;
        $conn = createConnectionAsEditor();
        try
        {
            [ $mod, $id ] = explode('_', $_GET['id'] ?? '');
            $notificationDefs = \SisEpi\Model\Notifications\Classes\NotificationsDefinitions::get();

            if (empty($notificationDefs[$mod][(string)$id]['conditionsComponentName']))
                throw new Exception('Tipo de notificação não encontrada ou sem condições disponíveis.');

            $identifierName = $notificationDefs[$mod][(string)$id]['conditionsComponentName'];
            $className = "\\SisEpi\\Controller\\Component\\NotificationsConditions\\$identifierName"; 
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
        $notId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\Notifications\SentNotification();
            $getter->userId = $_SESSION['userid'];
            $getter->id = $notId;
            $notification = $getter->getSingle($conn);
            $notification->isRead = 1;
            $notification->save($conn);

            if (http_response_code() == 303) throw new Exception('Possível loop de redirecionamento.');

            $newUrl = URl\URLGenerator::decodeJSONStruct($notification->linkUrlInfos);

            if (!empty($newUrl))
                header('location:' . $newUrl, true, 303);
            else
                throw new Exception('URL de notificação inválida ou não definida.');
        }
        catch (Exception $e)
        {
            header('location:' . URl\URLGenerator::generateSystemURL('homepage'), true, 303);
        }
        finally { $conn->close(); }
    }

    public function pre_delete()
    {
        $this->title = "SisEPI - Notificações: Excluir";
		$this->subtitle = "Notificações: Excluir";
    }

    public function delete()
    {

        $notId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $notification = null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\Notifications\SentNotification();
            $getter->userId = $_SESSION['userid'];
            $getter->id = $notId;
            $notification = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['notificationObj'] = $notification;
    }

    public function pre_sendmessage()
    {
        $this->title = "SisEPI - Notificações: Enviar mensagem";
		$this->subtitle = "Notificações: Enviar mensagem";
    }

    public function sendmessage()
    {
        require_once "model/Database/user.settings.database.php";

        $availableUserList = null;

        $conn = createConnectionAsEditor();
        try
        {
            $notificationModel = new \SisEpi\Model\Notifications\Classes\UserMessageNotification();
            $subscriptionChecker = new \SisEpi\Model\Notifications\UserNotificationSubscription();
            $allUserList = getUsersList($conn);
            $availableUserList = array_filter($allUserList, fn($u) => $subscriptionChecker->isUserSubscribed($conn, (int)$u['id'], $notificationModel) );
            
            if (empty($availableUserList))
                throw new Exception("Não há usuários interessados em receber mensagens de outros usuários.");
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['availableUserList'] = $availableUserList;
    }

    public function pre_test()
    {
    }

    public function test()
    {
        require_once "model/Database/database.php";

        $conn = createConnectionAsEditor();

        $not = new \SisEpi\Model\Notifications\Classes\EventSurveySentNotification([
            'eventId' => 2, 
        'surveyId' => 10, 
        'eventName' => 'Curso ABC',
        'surveyData' => [ 'head' => [ [ 'type' => 'yesNo', 'title' => 'Nível do evento', 'value' => '2' ], [ 'type' => 'textArea', 'title' => 'Sugestões', 'value' => '' ] ] ]
     ]);
        $afrows = $not->push($conn);
        $conn->close();
        var_export($afrows);

    }
}