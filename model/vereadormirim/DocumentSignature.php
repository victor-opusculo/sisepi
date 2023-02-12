<?php
namespace Model\VereadorMirim;

use DataEntity;
use DataProperty;
use Exception;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/Student.php';
require_once __DIR__ . '/VmParent.php';

class DocumentSignature extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('docSignature', null, DataProperty::MYSQL_INT),
            'vmDocumentId' => new DataProperty('vmDocId', null, DataProperty::MYSQL_INT),
            'vmStudentId' => new DataProperty('vmStudentId', null, DataProperty::MYSQL_INT),
            'vmParentId' => new DataProperty('vmParentId', null, DataProperty::MYSQL_INT),
            'docSignatureId' => new DataProperty('docSignatureId', null, DataProperty::MYSQL_INT),
            'signatureDateTime' => new DataProperty('signatureDateTime', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'vereadormirimdocsignatures';
    protected string $formFieldPrefixName = 'vmdocsigantures';
    protected array $primaryKeys = ['id'];

    public Student $studentSigner;
    public VmParent $parentSigner;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new DocumentSignature();
        $new->fillPropertiesFromDataRow($dataRow);
        $new->setCryptKey($this->encryptionKey);
        return $new;
    }

    public function fetchSigner(mysqli $conn)
    {
        if (!empty($this->properties->vmStudentId->getValue()))
        {
            $stuGetter = new Student();
            $stuGetter->id = $this->properties->vmStudentId->getValue();
            $stuGetter->setCryptKey($this->encryptionKey);
            $this->studentSigner = $stuGetter->getSingle($conn);
        }
        else if (!empty($this->properties->vmParentId->getValue()))
        {
            $parGetter = new VmParent();
            $parGetter->id = $this->properties->vmParentId->getValue();
            $parGetter->setCryptKey($this->encryptionKey);
            $this->parentSigner = $parGetter->getSingle($conn);
        }
    }

    public function getSignerName() : ?string
    {
        return isset($this->studentSigner) ? $this->studentSigner->name : (isset($this->parentSigner) ? $this->parentSigner->name : null);
    }

    public function getSignerTypePtBr() : ?string
    {
        return isset($this->studentSigner) ? "Vereador Mirim/Candidato" : (isset($this->parentSigner) ? "Pai/Responsável" : null);
    }

    public function getAllFromDocument(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.*");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause(" {$this->databaseTable}.vmDocumentId = ? ");
        $selector->addValue('i', $this->properties->vmDocumentId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
        return $output;
    }

    public function verifyIfIsAlreadySigned(mysqli $conn) : bool
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("count(*)");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.vmDocumentId = ? ");
        $selector->addWhereClause(" AND {$this->databaseTable}.vmStudentId = ? ");
        $selector->addWhereClause(" AND {$this->databaseTable}.vmParentId = ? ");
        $selector->addWhereClause(" AND {$this->databaseTable}.docSignatureId = ? ");
        $selector->addValues("iiii", [ $this->vmDocumentId, $this->vmStudentId, $this->vmParentId, $this->docSignatureId ]);
        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);

        return (int)$count > 0;
    }

    public function authSignature(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector->addSelectColumn(" jsontemplates.templateJson AS documentTemplateJson");
        $selector->addSelectColumn(" jsontemplates.name AS documentTemplateName");
        $selector->addSelectColumn(" aes_decrypt(vmstu.name, '{$this->encryptionKey}') AS vmDocOwnerStudentName");
        $selector->addSelectColumn(" vmstu.id AS vmDocOwnerStudentId ");
        $selector->addJoin(" INNER JOIN vereadormirimdocuments AS vmdoc ON vmdoc.id = {$this->databaseTable}.vmDocumentId ");
        $selector->addJoin(" INNER JOIN jsontemplates ON jsontemplates.type = 'vmdocument' AND jsontemplates.id = vmdoc.documentTemplateId ");
        $selector->addJoin(" INNER JOIN vereadormirimstudents AS vmstu ON vmstu.id = vmdoc.vmStudentId ");
        $selector->clearWhereClauses();
        $selector->clearValues();
        $selector->addWhereClause("{$this->databaseTable}.id = ? ");
        $selector->addWhereClause(" AND {$this->databaseTable}.signatureDateTime = ? ");
        $selector->addValues('is', [ $this->properties->id->getValue(), $this->properties->signatureDateTime->getValue() ]);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new \Model\Exceptions\FailedSignatureAuthentication("Assinatura inválida ou não existente", $this->databaseTable);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn(" (NOW() >= vereadormirimdocuments.signatureDate) AS canSign ");
        $selector->setTable('vereadormirimdocuments');
        $selector->addWhereClause("vereadormirimdocuments.id = ?");
        $selector->addValue('i', $this->properties->vmDocumentId->getValue());
        $canSign = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);

        if (!(bool)$canSign)
            throw new Exception('Tentativa de assinar documentação antes da data.');
        
        $this->properties->signatureDateTime->setValue(date('Y-m-d H:i:s'));
        
        return 0;
    }
}