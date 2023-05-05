<?php
namespace SisEpi\Model\Professors;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataObjectProperty;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;
use SisEpi\Model\Professors\ProfessorInformeRendimentosAttachment;
use mysqli;
use SisEpi\Model\Professors\Uploads\ProfessorInformeRendimentosUpload;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../exceptions.php';

class Professor extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [ 
            'id' => new DataProperty('profId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING, true),
            'telephone' => new DataProperty('txtTelephone', null, DataProperty::MYSQL_STRING, true),
            'schoolingLevel' => new DataProperty('radSchoolingLevel', null, DataProperty::MYSQL_STRING, true),
            'topicsOfInterest' => new DataProperty('txtTopicsOfInterest', null, DataProperty::MYSQL_STRING, true),
            'lattesLink' => new DataProperty('txtLattesLink', null, DataProperty::MYSQL_STRING, true),
            'collectInss' => new DataProperty('radCollectInss', null, DataProperty::MYSQL_INT, false),
            'personalDocsJson' => new DataObjectProperty((object)
            [
                'rg' => new DataProperty('txtRGNumber', null, DataProperty::MYSQL_STRING),
                'rgIssuingAgency' => new DataProperty('txtRGIssuingAgency', null, DataProperty::MYSQL_STRING),
                'cpf' => new DataProperty('txtCPFNumber', null, DataProperty::MYSQL_STRING),
                'pis_pasep' => new DataProperty('txtPIS_PASEP', null, DataProperty::MYSQL_STRING)
            ], true),
            'homeAddressJson' => new DataObjectProperty((object)
            [
                'street' => new DataProperty('txtHomeAddressStreet', null, DataProperty::MYSQL_STRING),
                'number' => new DataProperty('txtHomeAddressNumber', null, DataProperty::MYSQL_STRING),
                'complement' => new DataProperty('txtHomeAddressComplement', null, DataProperty::MYSQL_STRING),
                'neighborhood' => new DataProperty('txtHomeAddressNeighborhood', null, DataProperty::MYSQL_STRING),
                'city' => new DataProperty('txtHomeAddressCity', null, DataProperty::MYSQL_STRING),
                'state' => new DataProperty('txtHomeAddressState', null, DataProperty::MYSQL_STRING)
            ], true),
            'miniResumeJson' => new DataObjectProperty((object)
            [
                'education' => new DataProperty('txtResumeEducation', null, DataProperty::MYSQL_STRING),
                'experience' => new DataProperty('txtResumeExperience', null, DataProperty::MYSQL_STRING),
                'additionalInformation' => new DataProperty('txtResumeAdditionalInformation', null, DataProperty::MYSQL_STRING)
            ], true),
            'bankDataJson' => new DataObjectProperty((object)
            [
                'bankName' => new DataProperty('txtBankDataBankName', null, DataProperty::MYSQL_STRING),
                'agency' => new DataProperty('txtBankDataAgency', null, DataProperty::MYSQL_STRING),
                'account' => new DataProperty('txtBankDataAccount', null, DataProperty::MYSQL_STRING),
                'pix' => new DataProperty('txtBankDataPix', null, DataProperty::MYSQL_STRING)
            ], true),
            'agreesWithConsentForm' => new DataProperty('chkAgreesWithConsentForm', 0, DataProperty::MYSQL_INT),
            'consentForm' => new DataProperty('hidConsentFormVersion', null, DataProperty::MYSQL_STRING),
            'registrationDate' => new DataProperty('hidRegistrationDate', null, DataProperty::MYSQL_STRING, false)
        ];
    }

    protected string $databaseTable = 'professors';
    protected string $formFieldPrefixName = 'professors';
    protected array $primaryKeys = ['id'];

    public array $informeRendimentosAttachments = [];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Professor();
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
            $selector->addWhereClause(" convert(aes_decrypt(name, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(email, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(topicsOfInterest, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addValues("sss", ["%{$searchKeywords}%", "%{$searchKeywords}%", "%{$searchKeywords}%"]);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('topicsOfInterest'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" convert(aes_decrypt(name, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(email, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(topicsOfInterest, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addValues("sss", ["%{$searchKeywords}%", "%{$searchKeywords}%", "%{$searchKeywords}%"]);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'registrationDate':
            default: $selector->setOrderBy('registrationDate DESC'); break;
        }

        $selector->setLimit(' ?,? ');
        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);
        
        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getSingleBasic(\mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));

        $selector->setTable($this->databaseTable);

        $selector->addWhereClause(" {$this->databaseTable}.id = ? ");
        $selector->addValue('i', $this->properties->id->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Docente não encontrado', $this->databaseTable);
    }

    public function fetchInformesRendimentos(\mysqli $conn)
    {
        $getter = new ProfessorInformeRendimentosAttachment();
        $getter->professorId = $this->properties->id->getValue();
        $this->informeRendimentosAttachments = $getter->getAllFromProfessor($conn);
    }

    public function getAllBasic(\mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->setTable('professors');
        $selector->setOrderBy('name');
        return $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    public function getAllForExport(\mysqli $conn, $orderBy, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('email'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('telephone'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('schoolingLevel'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('topicsOfInterest'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('lattesLink'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('collectInss'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('personalDocsJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('homeAddressJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('miniResumeJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('bankDataJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('agreesWithConsentForm'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('consentForm'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" convert(aes_decrypt(name, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(email, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addWhereClause(" OR convert(aes_decrypt(topicsOfInterest, '{$this->encryptionKey}') using 'UTF8MB4') LIKE ? ");
            $selector->addValues("sss", ["%{$searchKeywords}%", "%{$searchKeywords}%", "%{$searchKeywords}%"]);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'registrationDate':
            default: $selector->setOrderBy('registrationDate DESC'); break;
        }
        
        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
  
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);
        
        return $output;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        ProfessorInformeRendimentosUpload::cleanIrFolder($this->properties->id->getValue());
        ProfessorInformeRendimentosUpload::checkForEmptyIrDir($this->properties->id->getValue());

        return $deleteResult;
    }
}