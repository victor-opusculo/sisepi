<?php

namespace SisEpi\Model\Notifications;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use Exception;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/SentNotification.php';

class Notification extends DataEntity
{
    public function __construct(array $parameters = [])
    {
        $this->properties = (object)
        [
            'module' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'defaultIconFilePath' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];

        foreach ($parameters as $k => $v)
            $this->$k = $v;
    }

    public const CONDITIONS_COMPONENT_NAME = null;

    protected string $databaseTable = 'notifications';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = [ 'module', 'id' ];
    protected array $setPrimaryKeysValue = [ 'module', 'id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAllNotifications(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn(" * ");
        $selector->setTable($this->databaseTable);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map(fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    protected function prePush(mysqli $conn) : array
    { 
        throw new Exception('Instância de SentNotification não criada.');
    }

    public function push(mysqli $conn) : int
    {
        $affectedRows = 0;
        try
        {
            [ $sent, $affectedRows ] = $this->prePush($conn);

            require_once __DIR__ . '/../Database/user.settings.database.php';
            require_once __DIR__ . '/UserNotificationSubscription.php';

            $userList = getUsersList($conn);
            $usersIdtoPush = [];

            $checker = new UserNotificationSubscription();
            foreach ($userList as $user)
                if ($checker->isUserSubscribed($conn, $user['id'], $this))
                    $usersIdtoPush[] = $user['id'];

            $affectedRows += $this->savePush($conn, $usersIdtoPush, $sent);
        }
        catch (Exception $e)
        { }
        
        return $affectedRows;
    }

    protected function savePush(mysqli $conn, array $userIds, SentNotification $sent) : int
    {
        $affectedRows = 0;
        foreach ($userIds as $uid)
        {
            $sent->userId = $uid;
            $affectedRows += $sent->save($conn)['affectedRows'];
        }
        return $affectedRows;
    }

    public function checkConditions(?array $conditions) : bool
    {
        return true;
    }

    public function delete(mysqli $conn)
    {
        throw new Exception('Não é possível excluir notificações via PHP.');
    }
}