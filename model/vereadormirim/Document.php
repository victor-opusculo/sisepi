<?php
namespace Model\VereadorMirim;

use DataEntity;
use DataProperty;
use Exception;
use Model\Exceptions\DatabaseEntityNotFound;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/Student.php';

class Document extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('docId', null, DataProperty::MYSQL_INT),
            'vmStudentId' => new DataProperty('hidVmStudentId', null, DataProperty::MYSQL_INT),
            'documentTemplateId' => new DataProperty('selTemplateId', null, DataProperty::MYSQL_INT),
            'documentData' => new DataProperty('hidDocData', '{}', DataProperty::MYSQL_STRING, true),
            'signatureDate' => new DataProperty('dateSignature', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'vereadormirimdocuments';
    protected string $formFieldPrefixName = 'vmdocuments';
    protected array $primaryKeys = ['id'];

    public array $signatures = [];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Document();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getSingle(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector->addSelectColumn("jsontemplates.templateJson AS templateJson ");
        $selector->addSelectColumn("jsontemplates.name AS templateName ");
        $selector->addSelectColumn("aes_decrypt(vereadormirimstudents.name, '{$this->encryptionKey}') AS studentName ");
        $selector->addJoin(" INNER JOIN jsontemplates ON jsontemplates.type = 'vmdocument' AND jsontemplates.id = {$this->databaseTable}.documentTemplateId ");
        $selector->addJoin(" INNER JOIN vereadormirimstudents ON vereadormirimstudents.id = {$this->databaseTable}.vmStudentId ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new \Model\Exceptions\DatabaseEntityNotFound("Documento de vereador mirim não encontrado!", $this->databaseTable);
    }

    public function getAllFromStudent(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector->addSelectColumn('jsontemplates.name AS templateName ');
        $selector->addJoin(" LEFT JOIN jsontemplates ON jsontemplates.type = 'vmdocument' AND jsontemplates.id = {$this->databaseTable}.documentTemplateId ");
        $selector->clearValues();
        $selector->clearWhereClauses();
        $selector->addWhereClause(" {$this->databaseTable}.vmStudentId = ? ");
        $selector->addValue('i', $this->properties->vmStudentId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
        return $output;
    }

    public function fetchSignatures(mysqli $conn)
    {
        require_once __DIR__ . '/DocumentSignature.php';
        $getter = new \Model\VereadorMirim\DocumentSignature();
        $getter->vmDocumentId = $this->properties->id->getValue();
        $this->signatures = $getter->getAllFromDocument($conn);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $documentData = new class
        {
            public $student = null;
            public $parent = null;
        };

        $vmStudent = null;
        try
        {
            $vmStudentGetter = new \Model\VereadorMirim\Student();
            $vmStudentGetter->id = $this->properties->vmStudentId->getValue();
            $vmStudentGetter->setCryptKey($this->encryptionKey);
            $vmStudent = $vmStudentGetter->getSingle($conn);
            $documentData->student = $vmStudent;
        }
        catch (DatabaseEntityNotFound $e)
        {
            writeErrorLog('Tentativa de criar documento de vereador mirim sem cadastro válido de estudante.');
            throw $e;
        }
        catch (Exception $e) { throw $e; }

        try
        {
            $vmParentGetter = new \Model\VereadorMirim\VmParent();
            $vmParentGetter->id = $vmStudent->vmParentId;
            $vmParentGetter->setCryptKey($this->encryptionKey);
            $vmParent = $vmParentGetter->getSingle($conn);
            $documentData->parent = $vmParent;
        }
        catch (DatabaseEntityNotFound $e)
        {
            $documentData->parent = null;
            writeLog('Criando documento de vereador mirim sem cadastro válido de pai/responsável.');
        }
        catch (Exception $e) { throw $e; }

        $this->properties->documentData->setValue(json_encode($documentData));

        return 0;
    }
}