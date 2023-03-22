<?php
namespace SisEpi\Model\VereadorMirim;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../database/vereadormirimparties.uploadFiles.php';

class Party extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('partyId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Partido sem nome', DataProperty::MYSQL_STRING),
            'acronym' => new DataProperty('txtAcronym', '', DataProperty::MYSQL_STRING),
            'number' => new DataProperty('numPartyNumber', null, DataProperty::MYSQL_INT),
            'moreInfos' => new DataProperty('txtMoreInfos', null, DataProperty::MYSQL_STRING),
            'logoFileExtension' => new DataProperty('hidLogoFileExtension', null, DataProperty::MYSQL_STRING)
        ];
    }

    public string $fileUploadFieldName = 'filePartyLogo';
    protected string $databaseTable = 'vereadormirimparties';
    protected string $formFieldPrefixName = 'vmparties';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Party();
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
            $selector->addWhereClause(" MATCH (name, acronym) AGAINST (?) ");
            $selector->addValue('s', $searchKeywords);
        }

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count;
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('name');
        $selector->addSelectColumn('acronym');
        $selector->addSelectColumn('number');

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (name, acronym) AGAINST (?) ");
            $selector->addValue('s', $searchKeywords);
        }

        switch ($orderBy)
        {
            case 'acronym': $selector->setOrderBy('acronym ASC'); break;
            case 'name': default: $selector->setOrderBy('name ASC'); break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage] );

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
        {
            $new = new Party();
            $new->fillPropertiesFromDataRow($dr);
            $output[] = $new;
        }

        return $output;
    }

    public function getAllBasic(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('name');
        $selector->addSelectColumn('acronym');
        $selector->addSelectColumn('number');
        $selector->setTable($this->databaseTable);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        Parties\Upload\checkForUploadError($this->postFiles[$this->fileUploadFieldName], null, Parties\Upload\partiesMaxFileSize, null);
        $this->properties->logoFileExtension->setValue(pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION));
        return 1;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        $extension = pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION);
        if (Parties\Upload\uploadFile($insertResult['newId'], $this->postFiles, $this->fileUploadFieldName, $extension))
            $insertResult['affectedRows']++;

        return $insertResult;
    }

    public function beforeDatabaseUpdate(mysqli $conn): int
    {
        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            Parties\Upload\checkForUploadError($this->postFiles[$this->fileUploadFieldName], $this->properties->id->getValue(), Parties\Upload\partiesMaxFileSize, null);
            $this->properties->logoFileExtension->setValue(pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION));
            return 1;
        }
        return 0;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        if (is_uploaded_file($this->postFiles[$this->fileUploadFieldName]['tmp_name']))
        {
            Parties\Upload\deleteFile("{$this->id}.{$this->logoFileExtension}");
            $extension = pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION);
            if (Parties\Upload\uploadFile($this->properties->id->getValue(), $this->postFiles, $this->fileUploadFieldName, $extension))
                $updateResult['affectedRows']++;
        }
        return $updateResult;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        if (Parties\Upload\deleteFile("{$this->id}.{$this->logoFileExtension}"))
            $deleteResult['affectedRows']++;

        return $deleteResult;
    }
}