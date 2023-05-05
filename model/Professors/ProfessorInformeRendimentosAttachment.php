<?php

namespace SisEpi\Model\Professors;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use SisEpi\Model\Professors\Uploads\ProfessorInformeRendimentosUpload;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../exceptions.php';

class ProfessorInformeRendimentosAttachment extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidIrAttId', null, DataProperty::MYSQL_INT),
            'year' => new DataProperty('numYear', null, DataProperty::MYSQL_INT),
            'professorId' => new DataProperty('hidProfId', null, DataProperty::MYSQL_INT),
            'fileExtension' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    public string $fileUploadFieldName = 'fileInformeRendimentosFile';
    protected string $databaseTable = 'professors_ir_attachments';
    protected string $formFieldPrefixName = 'professors_ir_attachs';
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
        ->addSelectColumn("AES_DECRYPT(professors.name, '{$this->encryptionKey}') AS professorName ")
        ->addJoin("INNER JOIN professors ON professors.id = {$this->databaseTable}.professorId ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (!empty($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Registro de informe de rendimentos não localizado", $this->databaseTable);
    }

    public function getSingleOfProfessor(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector()
        ->addSelectColumn("AES_DECRYPT(professors.name, '{$this->encryptionKey}') AS professorName ")
        ->addJoin("INNER JOIN professors ON professors.id = {$this->databaseTable}.professorId ")
        ->addWhereClause("AND {$this->databaseTable}.professorId = ? ")
        ->addValue('i', $this->properties->professorId->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (!empty($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Registro de informe de rendimentos não localizado", $this->databaseTable);
    }

    public function exists(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.professorId = ?")
        ->addWhereClause("AND {$this->databaseTable}.year = ?")
        ->addValues('ii', [ $this->properties->professorId->getValue(), $this->properties->year->getValue() ]);

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $count > 0;
    }

    public function getAllFromProfessor(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.professorId = ? ")
        ->addValue('i', $this->properties->professorId->getValue());
        
        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( [$this, 'newInstanceFromDataRow'], $drs );
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        \SisEpi\Model\FileUploadUtils::checkForUploadError( $this->postFiles[$this->fileUploadFieldName], 
                                                            ProfessorInformeRendimentosUpload::MAX_SIZE, 
                                                            [ProfessorInformeRendimentosUpload::class, 'throwException'], 
                                                            [ basename($this->postFiles[$this->fileUploadFieldName]['name']), $this->properties->professorId->getValue() ], 
                                                            ProfessorInformeRendimentosUpload::ALLOWED_TYPES);

        $this->properties->fileExtension->setValue(pathinfo($this->postFiles[$this->fileUploadFieldName]['name'], PATHINFO_EXTENSION));
        return 1;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        if (ProfessorInformeRendimentosUpload::uploadIrFile($this->properties->professorId->getValue(),
                                                            $this->properties->year->getValue(), 
                                                            $this->postFiles, 
                                                            $this->fileUploadFieldName))
            $insertResult['affectedRows']++;

        return $insertResult;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        if (ProfessorInformeRendimentosUpload::deleteIrFile($this->professorId, $this->year, $this->fileExtension))
            $deleteResult['affectedRows']++;

        return $deleteResult;
    }
}