<?php
require_once("database/events.database.php");
require_once("database/generalsettings.database.php");

class Event
{
	public $id;
	public $name;
	public $typeId, $typeName;
	public $subscriptionListNeeded;
	public $subscriptionListClosureDate;
	public $maxSubscriptionNumber;
	public $allowLateSubscriptions;
	public $posterImageAttachmentFileName;
	public $responsibleForTheEvent;
	public $customInfosJson;
	public $moreInfos;
	public $certificateText;
	public $certificateBgFile;
	public $checklistId;

	public $workPlan;
	public $dates;
	public $attachments;
	
	public function __construct($id)
	{
		
		$this->dates = [];
		$this->attachments = [];
		
		if ($id === null) throw new Exception("Erro ao instanciar a classe Event. id nulo.");
		
		if ($id === "new")
			$this->buildEmptyObject();
		else
			$this->buildObject($id);
	}
	
	private function buildEmptyObject()
	{
		$this->id = "";
		$this->name = "";
		$this->typeId = "";
		$this->subscriptionListNeeded = 1;
		$this->subscriptionListClosureDate = "";
		$this->maxSubscriptionNumber = 80;
		$this->allowLateSubscriptions = 1;
		$this->posterImageAttachmentFileName = "";
		$this->responsibleForTheEvent = "";
		$this->customInfosJson = "[]";
		$this->moreInfos = "";
		$this->certificateText = "";
		$this->certificateBgFile = readSetting("STUDENTS_CURRENT_CERTIFICATE_BG_FILE");
		$this->checklistId = null;
		
		$this->workPlan = new EventWorkPlan("new");

		$this->typeName = "";
	}
	
	private function buildObject($id)
	{
		$eventFullData = getFullEvent($id);
		
		if ($eventFullData["event"] === null || $eventFullData["event"]["id"] === null) throw new Exception("ID de evento não localizado.");
		
		$eventBasicData = $eventFullData["event"];
		foreach ($eventBasicData as $column => $value)
		{
			$this->$column = $value;
		}

		$this->workPlan = new EventWorkPlan($eventFullData["eventworkplan"] ?? "new");
				
		$eventDatesData = $eventFullData["eventdates"];
		
		if ($eventDatesData)
			foreach ($eventDatesData as $ed)
			{
				array_push($this->dates, new EventDate($ed));
			}
		
		$eventAttachmentsData = $eventFullData["eventattachments"];
		if ($eventAttachmentsData)
			foreach ($eventAttachmentsData as $ea)
			{
				array_push($this->attachments, new EventAttachment($ea));
			}
		
	}
}

class EventWorkPlan
{
	public $id;
	public $eventId;
	public $programName, $targetAudience, $duration, $resources;
	public $coordinators, $team, $assocTeam;
	public $legalSubstantiation;
	public $eventObjective;
	public $specificObjective;
	public $manualCertificatesInfos;
	public $observations;
	public $eventDescription;

	public function __construct($dataRow)
	{
		if ($dataRow === "new")
			$this->buildEmptyObject();
		else
		{
			foreach ($dataRow as $column => $value)
				$this->$column = $value;
		}
	}

	private function buildEmptyObject()
	{
		$this->programName = "";
		$this->targetAudience = "";
		$this->duration = "";
		$this->resources = "";
		$this->coordinators = "";
		$this->team = "";
		$this->assocTeam = "";
		$this->legalSubstantiation = "";
		$this->eventObjective = "";
		$this->specificObjective = "";
		$this->manualCertificatesInfos = "";
		$this->observations = "";
		$this->eventDescription = "";
	}
}

class EventDate
{
	public $id;
	public $date;
	public $beginTime;
	public $endTime;
	public $name;
	public $professorId, $professorName;
	public $presenceListNeeded;
	public $presenceListPassword;
	public $locationId;
	public $locationInfosJson;
	public $eventId;
	public $checklistId;
	
	public function __construct($dataRow)
	{
		foreach($dataRow as $column => $value)
		{
			$this->$column = $value;
		}
		
	}
}

class EventAttachment
{
	public $id;
	public $eventId;
	public $fileName;
	
	public function __construct($dataRow)
	{
		foreach($dataRow as $column => $value)
		{
			$this->$column = $value;
		}
		
	}
}