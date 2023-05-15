<?php
namespace SisEpi\Model\Professors;

use Exception;
use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../exceptions.php';

class ProfessorOTP extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidOtpId', null, DataProperty::MYSQL_INT),
            'professorId' => new DataProperty('hidProfessorId', null, DataProperty::MYSQL_INT),
            'oneTimePassword' => new DataProperty('txtNewPassword', null, DataProperty::MYSQL_STRING),
            'expiryDateTime' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'professorsotps';
    protected string $formFieldPrefixName = 'professorsotps';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getSingle(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector()
        ->addJoin("INNER JOIN professors ON professors.id = {$this->databaseTable}.professorId ")
        ->addSelectColumn("AES_DECRYPT(professors.name, '{$this->encryptionKey}') AS professorName ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("OTP de docente não localizada.", $this->databaseTable);
    }

    public function hashPassword()
    {
        $this->properties->oneTimePassword->setValue(password_hash($this->properties->oneTimePassword->getValue(), PASSWORD_DEFAULT));
    }

    public function combineExpiryDateAndTime()
    {
        if (!isset($this->otherProperties->dateExpiry, $this->otherProperties->timeExpiry))
            throw new Exception("Data e tempo de expiração não disponíveis ou não informados.");

        $this->properties->expiryDateTime->setValue
        (
            $this->otherProperties->dateExpiry . ' ' . 
            $this->otherProperties->timeExpiry
        );
    }

    public function getAllFromProfessor(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable)
        ->addWhereClause($this->getWhereQueryColumnName("professorId") . ' = ? ')
        ->addValue('i', $this->properties->professorId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    }
}