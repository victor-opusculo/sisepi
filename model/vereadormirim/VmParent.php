<?php

namespace Model\VereadorMirim;

use DataEntity;
use DataObjectProperty;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class VmParent extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('parentId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Responsável não nomeado', DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING, true),
            'parentDataJson' => new DataObjectProperty( (object)
            [
                'sex' => new DataProperty('radSex'),
                'birthDate' => new DataProperty('dateBirth'),
                'rg' => new DataProperty('txtRG'),
                'rgIssuingAgency' => new DataProperty('txtRgIssAgency'),
                'phones' => new DataObjectProperty( (object)
                [
                    'landline' => new DataProperty('txtLandline'),
                    'cellphone' => new DataProperty('txtCellphone')
                ]),
                'relationship' => new DataProperty('txtRelationship')
            ], true)
        ];
    }

    public ?array $students;

    protected string $databaseTable = 'vereadormirimparents';
    protected string $formFieldPrefixName = 'vmparents';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new VmParent();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*)');
        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause("CONVERT( " . $this->getWhereQueryColumnName('name') . " USING 'utf8mb4') LIKE ? ");
            $selector->addValue('s', "%" . $searchKeywords . "%");

            $selector->addWhereClause("OR CONVERT( " . $this->getWhereQueryColumnName('email') . " USING 'utf8mb4') LIKE ? ");
            $selector->addValue('s', "%" . $searchKeywords . "%");
        }

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count;
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause("CONVERT( " . $this->getWhereQueryColumnName('name') . " USING 'utf8mb4') LIKE ? ");
            $selector->addValue('s', "%" . $searchKeywords . "%");

            $selector->addWhereClause("OR CONVERT( " . $this->getWhereQueryColumnName('email') . " USING 'utf8mb4') LIKE ? ");
            $selector->addValue('s', "%" . $searchKeywords . "%");
        }

        switch ($orderBy)
        {
            case 'email': $selector->setOrderBy('email ASC'); break;
            case 'name': default: $selector->setOrderBy('name ASC'); break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage] );

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function fetchSubEntities(mysqli $conn)
    {
        require_once __DIR__ . '/Student.php';

        $getter = new Student();
        $getter->vmParentId = $this->properties->id->getValue();
        $students = $getter->getAllFromParent($conn);
        $this->students = $students;
    }
}