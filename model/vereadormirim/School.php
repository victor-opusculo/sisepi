<?php

namespace SisEpi\Model\VereadorMirim;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataObjectProperty;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';

class School extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('schoolId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtSchoolName', null, DataProperty::MYSQL_STRING),
            'numberOfVotingStudents' => new DataProperty('numVotingStudents', 0, DataProperty::MYSQL_INT),
            'email' => new DataProperty('txtSchoolEmail', '', DataProperty::MYSQL_STRING, true),
            'schoolDataJson' => new DataObjectProperty( (object)
            [
                'address' => new DataObjectProperty( (object)
                [
                    'street' => new DataProperty('txtAddressStreet'),
                    'number' => new DataProperty('txtAddressNumber'),
                    'neighborhood' => new DataProperty('txtAddressNeighborhood'),
                    'cep' => new DataProperty('txtAddressCep'),
                    'city' => new DataProperty('txtAddressCity'),
                    'stateUf' => new DataProperty('txtAddressStateUf')
                ]),
                'telephone' => new DataProperty('txtSchoolTelephone')
            ], true),
            'directorName' => new DataProperty('txtDirectorName', '', DataProperty::MYSQL_STRING, true),
            'directorDataJson' => new DataObjectProperty( (object)
            [
                'sex' => new DataProperty('radDirectorSex'),
                'cellphone' => new DataProperty('txtDirectorCellphone')
            ], true),
            'coordinatorName' => new DataProperty('txtCoordinatorName', '', DataProperty::MYSQL_STRING, true),
            'coordinatorDataJson' => new DataObjectProperty( (object)
            [
                'sex' => new DataProperty('radCoordinatorSex'),
                'cellphone' => new DataProperty('txtCoordinatorCellphone')
            ], true),
            'registrationDate' => new DataProperty('hidRegistrationDate', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'vereadormirimschools';
    protected string $formFieldPrefixName = 'vmschools';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('count(*)');
        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (name) AGAINST (?) ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName('directorName') . " USING 'utf8mb4' ) LIKE CONCAT('%', ?, '%') ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName('coordinatorName') . " USING 'utf8mb4' ) LIKE CONCAT('%', ?, '%') ");
            $selector->addValues('sss', [ $searchKeywords, $searchKeywords, $searchKeywords ]);
        }

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count;
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('directorName'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (name) AGAINST (?) ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName('directorName') . " USING 'utf8mb4' ) LIKE CONCAT('%', ?, '%') ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName('coordinatorName') . " USING 'utf8mb4' ) LIKE CONCAT('%', ?, '%') ");
            $selector->addValues('sss', [ $searchKeywords, $searchKeywords, $searchKeywords ]);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'email': $selector->setOrderBy('email ASC'); break;
            case 'registrationDate': default: $selector->setOrderBy('registrationDate DESC'); break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage] );

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);

        return $output;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->properties->registrationDate->setValue(date('Y-m-d H:i:s'));
        return 0;
    }
}