<?php

namespace Model\VereadorMirim;

use DataEntity;
use DataObjectProperty;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../../includes/Data/namespace.php';
require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../exceptions.php';

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
                'rg' => new DataProperty('txtRg'),
                'rgIssuingAgency' => new DataProperty('txtRgIssuingAgency'),
                'phones' => new DataObjectProperty( (object)
                [
                    'landline' => new DataProperty('txtLandline'),
                    'cellphone' => new DataProperty('txtCellphone')
                ])
            ], true),
            'registrationDate' => new DataProperty('hidRegistrationDate', null, DataProperty::MYSQL_STRING)
        ];

        $this->properties->email->valueTransformer = 'mb_strtolower';
        $this->properties->name->valueTransformer = 'Data\formatPersonNameCase';
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
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'id': default: $selector->setOrderBy('id ASC'); break;
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
        $getter->setCryptKey($this->encryptionKey);
        $students = $getter->getAllFromParent($conn);
        $this->students = $students;
    }

    public function getSingleDataRow(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector();

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $dr;
        else
            throw new \Model\Exceptions\DatabaseEntityNotFound("Responsável não encontrado!", $this->databaseTable);
    }

    public function getSingleByEmail(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector->clearValues();
        $selector->clearWhereClauses();
        $selector->addWhereClause("{$this->databaseTable}.email" . " = aes_encrypt(?, '{$this->encryptionKey}') ");
        $selector->addValue('s', $this->properties->email->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new \Model\Exceptions\DatabaseEntityNotFound("E-mail não encontrado!", $this->databaseTable);
    }

    public function exists(mysqli $conn) : bool
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*)');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause('id = ?');
        $selector->addValue('i', $this->properties->id->getValue());
        return ((int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE)) > 0;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->properties->registrationDate->setValue(date('Y-m-d H:i:s'));
        return 0;
    }
}