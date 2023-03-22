<?php
namespace SisEpi\Model\Notifications;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class UserNotificationSubscription extends DataEntity
{
    public function __construct()
    {
        $currentUserId = $_SESSION['userid'] ?? null;

        $this->properties = (object)
        [
            'userId' => new DataProperty('', $currentUserId, DataProperty::MYSQL_INT),
            'notMod' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'notId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'notConditions' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'usernotifications';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = [ 'userId', 'notMod', 'notId' ];
    protected array $setPrimaryKeysValue = [ 'userId', 'notMod', 'notId' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new UserNotificationSubscription();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAll(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('*');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.userId = ? ");
        $selector->addValue('i', $this->properties->userId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map(fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function isUserSubscribed(mysqli $conn, int $userId, ...$notificationsObjects) : bool
    {
        $this->properties->userId->setValue($userId);
        foreach ($notificationsObjects as $not)
        {
            $this->properties->notMod->setValue($not->module);
            $this->properties->notId->setValue($not->id);

            $gotten = null;
            try { $gotten = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { return false; } catch (\Exception $e) { throw $e; }

            if (!$not->checkConditions($gotten->getConditions())) return false;
        }

        return true;
    }

    public function getConditions() : ?array
    {
        $value = $this->properties->notConditions->getValue();
        return !empty($value) ? json_decode($value, true) : null;
    }

    public function applySubscriptionChanges(mysqli $conn, array $postData, int $userId) : int
    {
        $affectedRows = 0;

        $this->properties->userId->setValue($userId);
        $notificationsSubscribed = $this->getAll($conn);

        if (!isset($postData['subscribedNotifications']))
            $postData['subscribedNotifications'] = [];

        $toInsert = array_filter($postData['subscribedNotifications'], function ($subs) use ($notificationsSubscribed)
        {
            [ $module, $id ] = explode('_', $subs);
            $isAlreadySubscribed = array_reduce($notificationsSubscribed, fn($carry, $asub) => $carry || ($asub->notMod === $module && (int)$asub->notId === (int)$id), false);
            return !$isAlreadySubscribed;
        });

        $toDelete = array_filter($notificationsSubscribed, function($asubs) use ($postData)
        {
            $isStillSubscribed = array_reduce($postData['subscribedNotifications'], function($carry, $subs) use ($asubs)
            {
                [ $module, $id ] = explode('_', $subs);
                return $carry || ($module === $asubs->notMod && (int)$id === (int)$asubs->notId);
            }, false);
            return !$isStillSubscribed;
        });

        foreach ($toInsert as $insertSubs)
        {
            [ $module, $id ] = explode('_', $insertSubs);

            $new = new self();
            $new->fillPropertiesFromDataRow(
                [
                    'notMod' => $module,
                    'notId' => $id,
                    'userId' => $this->properties->userId->getValue()
                ]
            );

            $affectedRows += $new->save($conn)['affectedRows'];
        }

        foreach ($toDelete as $deleteSubs)
        {
            $affectedRows += $deleteSubs->delete($conn)['affectedRows'];
        }

        return $affectedRows;
    }
}