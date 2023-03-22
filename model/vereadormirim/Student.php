<?php

namespace SisEpi\Model\VereadorMirim;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataObjectProperty;
use SisEpi\Model\DataProperty;
use Exception;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use SisEpi\Model\SqlSelector;
use mysqli;

require_once __DIR__ . '/../vereadormirim/Legislature.php';
require_once __DIR__ . '/../vereadormirim/VmParent.php';
require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../database/vereadormirimstudents.uploadFiles.php';

class Student extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('studentId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Nome não definido', DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', 'E-mail não definido', DataProperty::MYSQL_STRING, true),
            'studentDataJson' => new DataObjectProperty( (object)
            [
                'sex' => new DataProperty('radSex', 'Indefinido', DataProperty::MYSQL_STRING),
                'birthDate' => new DataProperty('dateBirth', null, DataProperty::MYSQL_STRING),
                'schoolYear' => new DataProperty('txtSchoolYear', null, DataProperty::MYSQL_STRING),
                'schoolPeriod' => new DataProperty('radSchoolPeriod', null, DataProperty::MYSQL_STRING),
                'rg' => new DataProperty('txtRg', null, DataProperty::MYSQL_STRING),
                'rgIssuingAgency' => new DataProperty('txtRgIssuingAgency', null, DataProperty::MYSQL_STRING),
                'phones' => new DataObjectProperty( (object)
                [
                    'landline' => new DataProperty('txtLandline', null, DataProperty::MYSQL_STRING),
                    'cellphone' => new DataProperty('txtCellphone', null, DataProperty::MYSQL_STRING),
                    'whatsapp' => new DataProperty('txtWhatsapp', null, DataProperty::MYSQL_STRING)
                ]),
                'homeAddress' => new DataObjectProperty( (object)
                [
                    'street' => new DataProperty('txtAddrStreet', null, DataProperty::MYSQL_STRING),
                    'number' => new DataProperty('txtAddrNumber', null, DataProperty::MYSQL_STRING),
                    'complement' => new DataProperty('txtAddrComplement', null, DataProperty::MYSQL_STRING),
                    'neighborhood' => new DataProperty('txtAddrNeighborhood', null, DataProperty::MYSQL_STRING),
                    'cep' => new DataProperty('txtCep', null, DataProperty::MYSQL_STRING),
                    'city' => new DataProperty('txtCity', null, DataProperty::MYSQL_STRING),
                    'stateUf' => new DataProperty('txtStateUf', null, DataProperty::MYSQL_STRING),
                ]),
                'accessibilityRequired' => new DataProperty('txtAccesibilityRequired', null, DataProperty::MYSQL_STRING)
            ], true),
            'partyId' => new DataProperty('selParty', null, DataProperty::MYSQL_INT),
            'vmParentId' => new DataProperty('numParentId', null, DataProperty::MYSQL_INT),
            'vmParentRelationship' => new DataProperty('txtParentRelationship', null, DataProperty::MYSQL_STRING),
            'vmLegislatureId' => new DataProperty('numLegislatureId', null, DataProperty::MYSQL_INT),
            'registrationDate' => new DataProperty('hidRegistrationDate', null, DataProperty::MYSQL_STRING),
            'photoFileExtension' => new DataProperty('hidPhotoFileExtension', null, DataProperty::MYSQL_STRING),
            'isActive' => new DataProperty('chkIsActive', 0, DataProperty::MYSQL_INT),
            'isElected' => new DataProperty('chkIsElected', 0, DataProperty::MYSQL_INT)
        ];

        $this->properties->email->valueTransformer = 'mb_strtolower';
        $this->properties->name->valueTransformer = '\Data\formatPersonNameCase';
    }

    public string $fileUploadFieldName = 'fileVmStudentPhoto';
    protected string $databaseTable = 'vereadormirimstudents';
    protected string $formFieldPrefixName = 'vmstudents';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Student();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }


    private function generateSqlSelector() : SqlSelector
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.id");
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('studentDataJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('partyId'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('vmParentId'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('vmParentRelationship'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('vmLegislatureId'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('photoFileExtension'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('isActive'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('isElected'));

        $selector->addSelectColumn('vereadormirimparties.name AS partyName ');
        $selector->addSelectColumn('vereadormirimlegislatures.name AS legislatureName ');
        $selector->addSelectColumn("aes_decrypt(vereadormirimparents.name, '{$this->encryptionKey}') AS parentName ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin("LEFT JOIN vereadormirimparties ON vereadormirimparties.id = {$this->databaseTable}.partyId ");
        $selector->addJoin("LEFT JOIN vereadormirimlegislatures ON vereadormirimlegislatures.id = {$this->databaseTable}.vmLegislatureId ");
        $selector->addJoin("LEFT JOIN vereadormirimparents ON vereadormirimparents.id = {$this->databaseTable}.vmParentId ");

        return $selector;
    }

    public function getSingle(mysqli $conn) : Student
    {
        $selector = $this->generateSqlSelector();
        $selector->addWhereClause($this->getWhereQueryColumnName('id') . ' = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound('Vereador mirim não encontrado', $this->databaseTable);
    }

    public function getAllFromParent(mysqli $conn) : array
    {
        $selector = $this->generateSqlSelector();
        $selector->addWhereClause($this->getWhereQueryColumnName('vmParentId') . ' = ? ' );
        $selector->addValue('i', $this->properties->vmParentId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];

        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function getAllElectedFromLegislature(mysqli $conn) : array
    {
        $selector = $this->generateSqlSelector();
        $selector->addWhereClause($this->getWhereQueryColumnName('vmLegislatureId') . ' = ? ');
        $selector->addWhereClause( ' AND ' . $this->getWhereQueryColumnName('isElected') . ' = 1 ');
        $selector->addWhereClause( ' AND ' . $this->getWhereQueryColumnName('isActive') . ' = 1 ');
        $selector->addValue('i', $this->properties->vmLegislatureId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getAllCandidatesFromLegislature(mysqli $conn) : array
    {
        $selector = $this->generateSqlSelector();
        $selector->addWhereClause($this->getWhereQueryColumnName('vmLegislatureId') . ' = ? ');
        $selector->addValue('i', $this->properties->vmLegislatureId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
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

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords, $legislatureId) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('isActive'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('isElected'));
        $selector->addSelectColumn('vml.name AS legislatureName');

        $selector->setTable($this->databaseTable);

        $selector->addJoin("INNER JOIN vereadormirimlegislatures AS vml ON vml.id = {$this->databaseTable}.vmLegislatureId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" (CONVERT( " . $this->getWhereQueryColumnName('name') . " USING 'utf8mb4') LIKE ? ");
            $selector->addValue('s', "%" . $searchKeywords . "%");

            $selector->addWhereClause("OR CONVERT( " . $this->getWhereQueryColumnName('email') . " USING 'utf8mb4') LIKE ? )");
            $selector->addValue('s', "%" . $searchKeywords . "%");
        }

        if (!empty($legislatureId))
        {
            $begin = "";
            if ($selector->hasWhereClauses()) $begin = " AND ";

            $selector->addWhereClause($begin . $this->getWhereQueryColumnName('vmLegislatureId') . ' = ? ');
            $selector->addValue('i', $legislatureId);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'id': $selector->setOrderBy('id DESC'); break;
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

        
        $legGetter = new Legislature();
        $legGetter->id = $this->properties->vmLegislatureId->getValue();
        $existsLegislature = $legGetter->exists($conn);
        if (!$existsLegislature)
            throw new DatabaseEntityNotFound("ID de legislatura informado não existente.", $this->databaseTable);

        if (!empty($this->properties->vmParentId->getValue()))
        {
            $parGetter = new VmParent();
            $parGetter->id = $this->properties->vmParentId->getValue();
            $existsParent = $parGetter->exists($conn);
            if (!$existsParent)
                throw new DatabaseEntityNotFound("ID de pai/responsável informado não existente.", $this->databaseTable);
        }

        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            Students\Upload\checkForUploadError($this->postFiles[$this->fileUploadFieldName], null, Students\Upload\studentsMaxFileSize, null);
            $this->properties->photoFileExtension->setValue(pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION));
            return 1;
        }
        return 0;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            $extension = pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION);
            if (Students\Upload\uploadFile($insertResult['newId'], $this->postFiles, $this->fileUploadFieldName, $extension))
                $insertResult['affectedRows']++;
        }

        return $insertResult;
    }

    public function beforeDatabaseUpdate(mysqli $conn): int
    {
        $legGetter = new Legislature();
        $legGetter->id = $this->properties->vmLegislatureId->getValue();
        $existsLegislature = $legGetter->exists($conn);
        if (!$existsLegislature)
            throw new DatabaseEntityNotFound("ID de legislatura informado não existente.", $this->databaseTable);
           
        if (!empty($this->properties->vmParentId->getValue()))
        {
            $parGetter = new VmParent();
            $parGetter->id = $this->properties->vmParentId->getValue();
            $existsParent = $parGetter->exists($conn);
            if (!$existsParent)
                throw new DatabaseEntityNotFound("ID de pai/responsável informado não existente.", $this->databaseTable);
        }

        $affectedRows = 0;

        if (!empty($this->otherProperties->chkRemovePhoto))
        {
            $this->properties->photoFileExtension->setValue(null);
            $affectedRows++;
        }

        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            Students\Upload\checkForUploadError($this->postFiles[$this->fileUploadFieldName], $this->properties->id->getValue(), Students\Upload\studentsMaxFileSize, null);
            $this->properties->photoFileExtension->setValue(pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION));
            $affectedRows++;
        }
        return $affectedRows;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        if (empty($this->properties->photoFileExtension->getValue()))
        {
            Students\Upload\deleteAllFiles($this->properties->id->getValue());
            $updateResult['affectedRows']++;
        }

        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            Students\Upload\deleteAllFiles($this->id);
            $extension = pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION);
            if (Students\Upload\uploadFile($this->properties->id->getValue(), $this->postFiles, $this->fileUploadFieldName, $extension))
                $updateResult['affectedRows']++;
        }
        return $updateResult;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        if (Students\Upload\deleteAllFiles($this->id))
            $deleteResult['affectedRows']++;

        return $deleteResult;
    }
}