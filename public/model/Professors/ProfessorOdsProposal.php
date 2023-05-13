<?php

namespace SisEpi\Pub\Model\Professors;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ProfessorOdsProposal extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidOdsProposalId', null, DataProperty::MYSQL_INT),
            'professorWorkProposalId' => new DataProperty('hidProfWorkProposalId', null, DataProperty::MYSQL_INT),
            'odsGoals' => new DataProperty('goalsCodes', '[]', DataProperty::MYSQL_STRING),
            'odsRelationId' => new DataProperty('', null, DataProperty::MYSQL_INT)
        ];

        $this->properties->odsGoals->valueTransformer = function($value)
        {
            if (is_string($value)) return $value;
            else if (is_array($value)) return json_encode($value);
            return '[]';
        };
    }

    protected string $databaseTable = 'professorodsproposals';
    protected string $formFieldPrefixName = 'professorodsproposals';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getSingleOfWorkProposalIfExists(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearWhereClauses()
        ->clearValues()
        ->addWhereClause($this->getWhereQueryColumnName("professorWorkProposalId") . " = ? ")
        ->addValue('i', $this->properties->professorWorkProposalId->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if ($dr)
            return $this->newInstanceFromDataRow($dr);
        else
            return null;
    }

    public function checkRelationshipWithWorkProposal(mysqli $conn) : bool
    {
        if (empty($this->properties->id->getValue())) return true;

        $selector = (new SqlSelector)
        ->addSelectColumn("COUNT(*)")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.id = ? ")
        ->addWhereClause("AND {$this->databaseTable}.professorWorkProposalId = ? ")
        ->addValue('i', $this->properties->id->getValue())
        ->addValue('i', $this->properties->professorWorkProposalId->getValue());

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE) > 0;
    }
}