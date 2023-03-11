<?php

namespace Model\Professors;

use DataEntity;
use DataObjectProperty;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class ProfessorWorkProposal extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('profWorkProposalId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Proposta não nomeada', DataProperty::MYSQL_STRING),
            'infosFields' => new DataObjectProperty((object)
            [
                'objectives' => new DataProperty('txtInfoObjective', ''),
                'contents' => new DataProperty('txtInfoContents', ''),
                'procedures' => new DataProperty('txtInfoProcedures', ''),
                'resources' => new DataProperty('txtInfoResources', ''),
                'evaluation' => new DataProperty('txtInfoEvaluation', '')
            ]),
            'moreInfos' => new DataProperty('txtMoreInfos', null, DataProperty::MYSQL_STRING),
            'ownerProfessorId' => new DataProperty('selOwnerProfessorId', null, DataProperty::MYSQL_INT),
            'fileExtension' => new DataProperty('fileExtension', null, DataProperty::MYSQL_STRING),
            'isApproved' => new DataProperty('radioApproved', null, DataProperty::MYSQL_INT),
            'feedbackMessage' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'registrationDate' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'professorworkproposals';
    protected string $formFieldPrefixName = 'professorworkproposals';
    protected array $primaryKeys = [ 'id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*) ');
        $selector->setTable($this->databaseTable);
        
        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (professorworkproposals.name, professorworkproposals.moreInfos) AGAINST (?) 
            OR JSON_SEARCH(professorworkproposals.infosFields, 'one', CONCAT('%', ?, '%') ) IS NOT NULL " );
            $selector->addValues('ss', [ $searchKeywords, $searchKeywords ]);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $searchKeywords, $orderBy, $page, $numResultsOnPage) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('name'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('isApproved'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));
        $selector->addSelectColumn("aes_decrypt(professors.name, '{$this->encryptionKey}') as ownerProfessorName ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin(" LEFT JOIN professors ON professors.id = {$this->databaseTable}.ownerProfessorId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (professorworkproposals.name, professorworkproposals.moreInfos) AGAINST (?) 
            OR JSON_SEARCH(professorworkproposals.infosFields, 'one', CONCAT('%', ?, '%') ) IS NOT NULL " );
            $selector->addValues('ss', [ $searchKeywords, $searchKeywords ]);
        }

        switch ($orderBy)
        {
            case 'approved':
                $selector->setOrderBy("{$this->databaseTable}.isApproved ASC"); break;
            case 'name':
                $selector->setOrderBy("{$this->databaseTable}.name ASC"); break;
            case 'date':
            default:
                $selector->setOrderBy("{$this->databaseTable}.registrationDate DESC"); break;
        }

        $selector->setLimit(' ?, ? ');
        $selector->addValues('ii', [ ($page - 1) * $numResultsOnPage, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getSingle(mysqli $conn) : self
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector->addSelectColumn("aes_decrypt(professors.name, '{$this->encryptionKey}') as ownerProfessorName ");
        $selector->addSelectColumn("aes_decrypt(professors.email, '{$this->encryptionKey}') as ownerProfessorEmail ");
        $selector->addJoin(" LEFT JOIN professors ON professors.id = {$this->databaseTable}.ownerProfessorId  ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        return $this->newInstanceFromDataRow($dr);
    }
} 