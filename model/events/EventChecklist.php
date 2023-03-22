<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';

class EventChecklist extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('checklistId', null, DataProperty::MYSQL_INT),
            'checklistJson' => new DataProperty('checklistJson', null, DataProperty::MYSQL_STRING)
        ];        
    }

    protected string $databaseTable = 'eventchecklists';
    protected string $formFieldPrefixName = 'eventchecklists';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventChecklist();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function save(mysqli $conn)
    {
        if (!empty($this->id) && !empty($this->checklistJson))
            return parent::save($conn);
        else
            return ['affectedRows' => 0];
    }
}