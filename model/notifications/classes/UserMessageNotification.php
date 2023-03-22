<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';
require_once __DIR__ . '/../../database/user.settings.database.php';

final class UserMessageNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'USERMSG';
        $this->id = 1;
        $this->name = 'Mensagens: Mensagem de texto enviada por outro usuário do sistema para você';
        $this->defaultIconFilePath = '/pics/notifications/email-by-dinosoftlabs.png';
    }

    public const CONDITIONS_COMPONENT_NAME = null;

    protected int $senderUserId;
    protected array $destinationUserIds;
    protected array $userList;
    protected string $messageText;
    protected string $url;

    protected function prePush(mysqli $conn) : array
    {   
        $this->userList = getUsersList($conn);

        $senderUserArr = array_filter($this->userList, fn($u) => $u['id'] == $this->senderUserId);
        $senderUser = array_shift($senderUserArr); 

        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "$senderUser[name] te enviou uma mensagem";
        $sent->description = $this->messageText;
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::useDirectURL($this->url);

        return [ $sent, 0 ];
    }

    public function push(mysqli $conn) : int
    {
        $affectedRows = 0;
        try
        {
            [ $sent, $affectedRows ] = $this->prePush($conn);

            require_once __DIR__ . '/../UserNotificationSubscription.php';

            $userList = $this->userList;
            $usersIdtoPush = [];

            $checker = new \SisEpi\Model\Notifications\UserNotificationSubscription();
            foreach ($userList as $user)
                if ($checker->isUserSubscribed($conn, $user['id'], $this) && in_array($user['id'], $this->destinationUserIds))
                    $usersIdtoPush[] = $user['id'];

            $affectedRows += $this->savePush($conn, $usersIdtoPush, $sent);
        }
        catch (\Exception $e)
        { }
        
        return $affectedRows;
    }

    public function checkConditions(?array $conditions): bool
    {
        return true;
    }
}