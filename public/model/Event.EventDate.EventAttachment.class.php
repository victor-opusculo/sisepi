<?php
require_once("model/database/events.database.php");
//Public

class Event
{
	public $id;
	public $name;
	public $typeId, $typeName;
	public $subscriptionListNeeded;
	public $subscriptionListOpeningDate;
	public $subscriptionListClosureDate;
	public $maxSubscriptionNumber;
	public $allowLateSubscriptions;
	public $posterImageAttachmentFileName;
	public $responsibleForTheEvent;
	public $customInfosJson;
	public $moreInfos;
	public $certificateText;
	public $certificateBgFile;
	public $hours;
	public $currentSubscriptionNumber;
	
	public $dates;
	
	public function __construct($id)
	{
		
		$this->dates = [];
		$this->attachments = [];
		
		if ($id === null) throw new Exception("Erro ao instanciar a classe Event. id nulo.");
		
		$this->buildObject($id);
	}

	
	private function buildObject($id)
	{
		$eventFullData = getFullEvent($id);
		
		if ($eventFullData["event"]["id"] === null) throw new Exception("ID de evento não localizado.");
		
		$eventBasicData = $eventFullData["event"];
		foreach ($eventBasicData as $column => $value)
		{
			$this->$column = $value;
		}
		
		$this->currentSubscriptionNumber = $eventFullData["currentSubscriptionNumber"];
		
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

class EventDate
{
	public $id;
	public $date;
	public $beginTime;
	public $endTime;
	public $name;
	public $presenceListNeeded;
	public $eventId;
	public $isPresenceListOpen;
	public $locationInfosJson;
	public $locationName;
	
	public $professorsNames;

	public function __construct($dataRow)
	{
		foreach($dataRow as $column => $value)
		{
			$this->$column = $value;
		}		
	}
}

class EventDateProfessor
{
	public function __construct($dataRow)
	{
		foreach($dataRow as $column => $value)
			$this->$column = $value;
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