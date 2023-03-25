<?php
//Public 

namespace SisEpi\Pub\Model\Professors;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataObjectProperty;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../model/exceptions.php';

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
            'consentForm' => new DataProperty('hidConsentFormVersion', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'professors';
    protected string $formFieldPrefixName = 'professors';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Professor();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
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

    public function getAllBasic(\mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->setTable('professors');
        $selector->setOrderBy('name');
        return $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    public function save(mysqli $conn)
    { }

    public function delete(mysqli $conn)
    { }
}