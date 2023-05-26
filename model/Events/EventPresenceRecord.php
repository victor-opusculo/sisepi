<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . "/../../vendor/autoload.php";

class EventPresenceRecord extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventDateId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'subscriptionId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'email' => new DataProperty('', null, DataProperty::MYSQL_STRING, true),
            'name' => new DataProperty('', null, DataProperty::MYSQL_STRING, true)
        ];
    }

    protected string $databaseTable = 'presencerecords';
    protected string $formFieldPrefixName = 'presencerecords';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $dataRow;
    }

    public function getPresencePercent(mysqli $conn, ?bool $eventRequiresSubscriptionList = null) : ?int
    {
        $subscriptionListEnabled = $eventRequiresSubscriptionList;
        if (!isset($subscriptionListEnabled))
        {
            $eventGetter = new Event();
            $eventGetter->id = $this->properties->eventId->getValue();
            $event = $eventGetter->getSingle($conn);
            $subscriptionListEnabled = (bool)$event->subscriptionListNeeded;
        }

        $querySl = "SELECT floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
        from presencerecords
        inner join subscriptionstudentsnew on subscriptionstudentsnew.id = presencerecords.subscriptionId
        where presencerecords.eventId = ? and subscriptionstudentsnew.email = aes_encrypt(lower(?), '{$this->encryptionKey}')
        group by presencerecords.subscriptionId";

        $queryNsl = "SELECT floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
        from presencerecords
        where presencerecords.eventId = ? and subscriptionId is null and presencerecords.email = aes_encrypt(lower(?), '{$this->encryptionKey}')
        group by presencerecords.email";

        $eventId = $this->properties->eventId->getValue();
        $email = $this->properties->email->getValue();

        $stmt = $conn->prepare($subscriptionListEnabled ? $querySl : $queryNsl);
        $stmt->bind_param('iis', $eventId, $eventId, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $dataRow = $result->fetch_assoc();
        $result->close();
        $stmt->close();
        
        return $dataRow['presencePercent'] ?? null;
    }

    public function getNameFromEmail(mysqli $conn) : string
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName("name"))
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.email = AES_ENCRYPT(lower(?), '{$this->encryptionKey}')")
        ->setOrderBy("{$this->databaseTable}.id DESC ")
        ->addValue('s', $this->properties->email->getValue());

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }
}