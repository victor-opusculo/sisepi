<?php

namespace Model\LibraryCollection;

require_once __DIR__ . '/../DataEntity.php';

use \DataEntity;
use \DataProperty;
use \SqlSelector;

class AcquisitionType extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'type' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'value' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'enums';
    protected array $primaryKeys = ['type', 'id'];
    protected array $setPrimaryKeysValue = ['type'];

    public function getAll(\mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('*');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("type = 'LIBACQTYPE'");
        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        
        $output = [];
        if (!empty($drs))
            foreach ($drs as $dr)
                $output[] = $this->newInstanceFromDataRow($dr);
        
        return $output;
    }

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new AcquisitionType();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    } 
}