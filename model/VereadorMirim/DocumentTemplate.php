<?php

namespace SisEpi\Model\VereadorMirim;

use SisEpi\Model\SqlSelector;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';

class DocumentTemplate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'type' => new DataProperty('', 'vmdocument', DataProperty::MYSQL_STRING),
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'templateJson' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];

        $this->properties->type->setValue('vmdocument');
    }

    protected string $databaseTable = 'jsontemplates';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['type', 'id'];
    protected array $setPrimaryKeysValue = ['type'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new DocumentTemplate();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAll(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.*");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.type = '{$this->type}'");

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];

        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }
}