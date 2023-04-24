<?php
namespace SisEpi\Model\Enums;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';

class Enum extends DataEntity
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
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['type', 'id']; 
    protected array $setPrimaryKeysValue = [ 'type' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAllFromType(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("*")
        ->setTable($this->databaseTable)
        ->addWhereClause(" type = ? ")
        ->addValue('s', $this->properties->type->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }
}