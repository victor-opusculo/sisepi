<?php
namespace Model\Events;

use DataEntity;
use DataProperty;
use Model\Professors\Professor;
use Model\Traits\EntityTrait;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../database/eventchecklists.database.php';
require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../professors/Professor.php';
require_once __DIR__ . '/../traits/EntityTrait.php';


class EventDate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('id', null, DataProperty::MYSQL_INT),
            'date' => new DataProperty('date', null, DataProperty::MYSQL_STRING),
            'beginTime' => new DataProperty('beginTime', null, DataProperty::MYSQL_STRING),
            'endTime' => new DataProperty('endTime', null, DataProperty::MYSQL_STRING),
            'name' => new DataProperty('name', null, DataProperty::MYSQL_STRING),
            'presenceListNeeded' => new DataProperty('presenceListEnabled', null, DataProperty::MYSQL_INT),
            'presenceListPassword' => new DataProperty('presenceListPassword', null, DataProperty::MYSQL_STRING),
            'locationId' => new DataProperty('locationId', null, DataProperty::MYSQL_INT),
            'locationInfosJson' => new DataProperty('locationInfosJson', null, DataProperty::MYSQL_STRING),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'checklistId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'calendarStyleJson' => new DataProperty('calendarStyleJson', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventdates';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['id'];

    public array $professors = [];
    public array $traits = [];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventDate();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function fetchSubEntities(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('professorId');
        $selector->setTable('eventdatesprofessors');
        $selector->addWhereClause('eventdatesprofessors.eventDateId = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $profIds = $selector->run($conn, SqlSelector::RETURN_ALL_NUM);
        $getter = new Professor();
        $getter->setCryptKey($this->encryptionKey);
        foreach ($profIds as $id)
        {
            $getter->id = $id[0];
            $new = $getter->getSingleBasic($conn);
            $this->professors[] = $new;
        }

        $selector = new SqlSelector();
        $selector->addSelectColumn('traitId');
        $selector->setTable('eventdatestraits');
        $selector->addWhereClause('eventdatestraits.eventDateId = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $traitsIds = $selector->run($conn, SqlSelector::RETURN_ALL_NUM);
        $getter = new EntityTrait();
        foreach ($traitsIds as $id)
        {
            $getter->id = $id[0];
            $new = $getter->getSingle($conn);
            $this->traits[] = $new;
        }
    }

    public function beforeDatabaseInsert(mysqli $conn) : int
    {
        if ((int)$this->otherProperties->checklistAction > 0)
        {
            $checkListNewId = null;
            $jsonTemplate = getEventDateChecklistJson($this->otherProperties->checklistAction, $conn);
            $stmt = $conn->prepare('INSERT into eventchecklists (finalized, checklistJson) values (0, ?) ');
            $stmt->bind_param('s', $jsonTemplate);
            $stmt->execute();
            $checkListNewId = $conn->insert_id;
            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            if ($checkListNewId)
                $this->properties->checklistId->setValue($checkListNewId);

            return $affectedRows;
        }
        return 0;
    }

    public function beforeDatabaseUpdate(mysqli $conn) : int
    {
        $affectedRows = 0;

        $deleteCurrentChecklist = function() use ($conn)
        {
            $affectedRows = 0;
            $query1 = "DELETE from eventchecklists where eventchecklists.id = (Select checklistId from eventdates where id = ?)";
            $stmt = $conn->prepare($query1);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        };

        if ((int)$this->otherProperties->checklistAction > 0)
        {
            $affectedRows += $deleteCurrentChecklist();
            $jsonTemplate = getEventDateChecklistJson($this->otherProperties->checklistAction, $conn);
            $stmt = $conn->prepare('INSERT into eventchecklists (finalized, checklistJson) values (0, ?) ');
            $stmt->bind_param('s', $jsonTemplate);
            $stmt->execute();
            $checkListNewId = $conn->insert_id;
            $affectedRows += $stmt->affected_rows;
            $stmt->close();

            $this->properties->checklistId->setValue($checkListNewId);

            return $affectedRows;
        }
        else if ((int)$this->otherProperties->checklistAction === -1)
        {
            $affectedRows += $deleteCurrentChecklist();

            $query2 = "UPDATE eventdates SET checklistId = NULL where id = ?";
            $stmt = $conn->prepare($query2);
            $stmt->bind_param("i", $this->id); 
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();

            return $affectedRows;
        }
        return 0;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        $this->properties->id->setValue($insertResult['newId']);
        $insertResult['affectedRows'] += $this->updateEventDatesProfessors($conn, $this->otherProperties->professors);
        $insertResult['affectedRows'] += $this->updateEventDatesTraits($conn, $this->otherProperties->traits);
        return $insertResult;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        $updateResult['affectedRows'] += $this->updateEventDatesProfessors($conn, $this->otherProperties->professors);
        $updateResult['affectedRows'] += $this->updateEventDatesTraits($conn, $this->otherProperties->traits);
        return $updateResult;
    }

    public function beforeDatabaseDelete(mysqli $conn): int
    {
        $affectedRows = 0;
        //Delete checklist associated with this event date
        if($stmt = $conn->prepare("SELECT checklistId from eventdates where id = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $checklistId = $result->fetch_row()[0];
            $result->close();
            $affectedRows += !empty($checklistId) ? deleteSingleChecklist($checklistId, $conn) : 0;
        }

        //Delete event date professors
        if($stmt = $conn->prepare("DELETE from eventdatesprofessors where eventDateId = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();
        }

        //Delete event date traits
        if($stmt = $conn->prepare("DELETE from eventdatestraits where eventDateId = ?"))
        {
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();
        }
        return $affectedRows;
    }

    private function updateEventDatesProfessors(mysqli $conn, $professorIdsArray)
    {
        $existentProfessors = [];
        $professorIdsArrayUnique = array_unique($professorIdsArray);
        $affectedRows = 0;
        $thisId = $this->properties->id->getValue();

        $querySelectExistent = "SELECT professorId from eventdatesprofessors where eventDateId = ?";
        $stmt = $conn->prepare($querySelectExistent);
        $stmt->bind_param("i", $thisId);
        $stmt->execute();
        $resultExistent = $stmt->get_result();
        $stmt->close();
        while ($row = $resultExistent->fetch_assoc())
            $existentProfessors[] = $row['professorId'];
        $resultExistent->close();

        foreach ($professorIdsArrayUnique as $updatedId)
        {
            if (array_search($updatedId, $existentProfessors) === false)
            {
                $queryInsertProfessor = "INSERT into eventdatesprofessors (eventDateId, professorId) values (?, ?)";
                $stmt = $conn->prepare($queryInsertProfessor);
                $stmt->bind_param("ii", $this->id, $updatedId);
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
                $stmt->close();
            }
        }

        foreach ($existentProfessors as $existentId)
        {
            if (array_search($existentId, $professorIdsArrayUnique) === false)
            {
                $queryDeleteProfessor = "DELETE from eventdatesprofessors where eventDateId = ? AND professorId = ?";
                $stmt = $conn->prepare($queryDeleteProfessor);
                $stmt->bind_param("ii", $this->id, $existentId);
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
                $stmt->close();
            }
        }
        return $affectedRows;
    }

    private function updateEventDatesTraits(mysqli $conn, $traitsIdsArray)
    {
        $existentTraits = [];
        $traitsIdsArrayUnique = array_unique($traitsIdsArray);
        $affectedRows = 0;
        $thisId = $this->properties->id->getValue();

        $querySelectExistent = "SELECT traitId from eventdatestraits where eventDateId = ?";
        $stmt = $conn->prepare($querySelectExistent);
        $stmt->bind_param("i", $thisId);
        $stmt->execute();
        $resultExistent = $stmt->get_result();
        $stmt->close();
        while ($row = $resultExistent->fetch_assoc())
            $existentTraits[] = $row['traitId'];
        $resultExistent->close();

        foreach ($traitsIdsArrayUnique as $updatedId)
        {
            if (array_search($updatedId, $existentTraits) === false)
            {
                $queryInsertProfessor = "INSERT into eventdatestraits (eventDateId, traitId) values (?, ?)";
                $stmt = $conn->prepare($queryInsertProfessor);
                $stmt->bind_param("ii", $this->id, $updatedId);
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
                $stmt->close();
            }
        }

        foreach ($existentTraits as $existentId)
        {
            if (array_search($existentId, $traitsIdsArrayUnique) === false)
            {
                $queryDeleteProfessor = "DELETE from eventdatestraits where eventDateId = ? AND traitId = ?";
                $stmt = $conn->prepare($queryDeleteProfessor);
                $stmt->bind_param("ii", $this->id, $existentId);
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
                $stmt->close();
            }
        }
        return $affectedRows;
    }
}