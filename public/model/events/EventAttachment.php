<?php
//Public 
namespace SisEpi\Public\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;

require_once __DIR__ . '/../../../model/DataEntity.php';

class EventAttachment extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'fileName' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventattachments';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventAttachment();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function save(mysqli $conn)
    { }

    public function delete(mysqli $conn)
    { }
}