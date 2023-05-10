<?php

namespace SisEpi\Model\Professors;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataObjectProperty;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';

class ProfessorWorkSheet extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'professorId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'professorWorkProposalId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'paymentInfosJson' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'professorTypeId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'paymentTableId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'paymentLevelId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'classTime' => new DataProperty('', null, DataProperty::MYSQL_DOUBLE),
            'paymentSubsAllowanceTableId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'paymentSubsAllowanceLevelId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'paymentSubsAllowanceClassTime' => new DataProperty('', null, DataProperty::MYSQL_DOUBLE),
            'participationEventDataJson' => new DataObjectProperty((object)
            [
                'dates' => new DataProperty(''),
                'times' => new DataProperty(''),
                'workTime' => new DataProperty(''),
                'activityName' => new DataProperty('')
            ]),
            'professorCertificateText' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'certificateBgFile' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'professorDocTemplateId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'signatureDate' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'referenceMonth' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'professorworksheets';
    protected string $formFieldPrefixName = 'professorworksheets';
    protected array $primaryKeys = ['id'];

    protected ?object $paymentData;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause(" JSON_EXTRACT(participationEventDataJson, '$.activityName') LIKE CONCAT('%', ?, '%') ")
            ->addValue('s', $searchKeywords); 
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    } 

    public function getMultiplePartially(mysqli $conn, int $page, int $numResultsOnPage, string $orderBy, string $searchKeywords) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName("id"))
        ->addSelectColumn($this->getSelectQueryColumnName("professorId"))
        ->addSelectColumn($this->getSelectQueryColumnName("professorWorkProposalId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentInfosJson"))
        ->addSelectColumn($this->getSelectQueryColumnName("professorTypeId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentTableId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentLevelId"))
        ->addSelectColumn($this->getSelectQueryColumnName("classTime"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceTableId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceLevelId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceClassTime"))
        ->addSelectColumn($this->getSelectQueryColumnName("participationEventDataJson"))
        ->addSelectColumn($this->getSelectQueryColumnName("signatureDate"))
        ->addSelectColumn("JSON_UNQUOTE(JSON_EXTRACT({$this->databaseTable}.participationEventDataJson, '$.activityName')) AS activityName ")
        ->addSelectColumn("AES_DECRYPT(professors.name, '{$this->encryptionKey}') AS professorName ")
        ->addSelectColumn("professorworkproposals.name AS workProposalName ")
        ->setTable($this->databaseTable)
        ->addJoin(" LEFT JOIN professors ON professors.id = {$this->databaseTable}.professorId ")
        ->addJoin(" LEFT JOIN professorworkproposals ON professorworkproposals.id = {$this->databaseTable}.professorWorkProposalId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause(" JSON_EXTRACT(participationEventDataJson, '$.activityName') LIKE CONCAT('%', ?, '%') ")
            ->addValue('s', $searchKeywords); 
        }

        switch ($orderBy)
        {
            case 'name': $selector = $selector->setOrderBy('activityName ASC'); break;
            case 'profName': $selector = $selector->setOrderBy('professorName ASC'); break;
            case 'date': default: $selector = $selector->setOrderBy('signatureDate DESC'); break;
        }

        $selector->setLimit(' ?, ? ');
        $selector->addValues('ii', [ ($page - 1) * $numResultsOnPage, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function fillPropertiesFromDataRow($dataRow)
    {
        parent::fillPropertiesFromDataRow($dataRow);

        $this->paymentData = json_decode($this->properties->paymentInfosJson->getValue() ?? '');
    }

    public function getPaymentValue() : ?float
    {
        if (!empty($this->paymentData))
        {
            $paymentData = $this->paymentData;

            $professorType = $paymentData->professorTypes[$this->properties->professorTypeId->getValue()];

            $paymentLevel = $paymentData->paymentLevelTables[$this->properties->paymentTableId->getValue()]
                ->levels[$this->properties->paymentLevelId->getValue()];
            $classTime = $this->properties->classTime->getValue();

            $subsAllowanceLevel = $paymentData->paymentLevelTables[$this->properties->paymentSubsAllowanceTableId->getValue()]
            ->levels[$this->properties->paymentSubsAllowanceLevelId->getValue()] ?? (object)[ 'classTimeValue' => 0 ];
            $subsAllowanceClassTime = $this->properties->paymentSubsAllowanceClassTime->getValue() ?? 0;

            return 
            ( $professorType->paymentMultiplier * $paymentLevel->classTimeValue * $classTime )
            +
            ( $subsAllowanceLevel->classTimeValue * $subsAllowanceClassTime );
        }
        else
            return null;
    }

    public function getWorkSheetsInPeriod(mysqli $conn, string $begin, string $end) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName("id"))
        ->addSelectColumn($this->getSelectQueryColumnName("professorId"))
        ->addSelectColumn($this->getSelectQueryColumnName("eventId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentInfosJson"))
        ->addSelectColumn($this->getSelectQueryColumnName("professorTypeId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentTableId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentLevelId"))
        ->addSelectColumn($this->getSelectQueryColumnName("classTime"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceTableId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceLevelId"))
        ->addSelectColumn($this->getSelectQueryColumnName("paymentSubsAllowanceClassTime"))
        ->addSelectColumn($this->getSelectQueryColumnName("signatureDate"))
        ->addSelectColumn($this->getSelectQueryColumnName("referenceMonth"))
        ->addSelectColumn("events.name AS eventName")
        ->addSelectColumn("AES_DECRYPT(professors.name, '{$this->encryptionKey}') AS professorName")
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId")
        ->addJoin("INNER JOIN professors ON professors.id = {$this->databaseTable}.professorId")
        ->setTable($this->databaseTable)
        ->addWhereClause( "(" . $this->getWhereQueryColumnName("signatureDate") . " >= ? ")
        ->addWhereClause( " OR " . $this->getWhereQueryColumnName("referenceMonth") . " >= ? )")
        ->addWhereClause( " AND (" . $this->getWhereQueryColumnName("signatureDate") . " <= ? ")
        ->addWhereClause( " OR " . $this->getWhereQueryColumnName("referenceMonth") . " <= ? )")
        ->addValues('ssss', [ $begin, $begin, $end, $end ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    } 
}