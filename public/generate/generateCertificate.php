<?php

//require("../../includes/tfpdf/tfpdf.php");

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Events\EventCompletedTest;

require_once "../../vendor/autoload.php";
require("../includes/common.php");
require("../includes/logEngine.php");
require("../model/Database/certificate.database.php");
require("../model/Database/generalsettings.database.php");

define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . URL\URLGenerator::generateSystemURL("events", "authcertificate"));

class CertPDF extends tFPDF
{
	private $event;
	private $eventDates;
	private $studentData;
	private $authInfos;
	private ?EventCompletedTest $eventTest;
	
	public function SetData($event, $eventDates, $studentData, ?EventCompletedTest $eventTest, $authInfos)
	{
		$this->event = $event;
		$this->eventDates = $eventDates;
		$this->studentData = $studentData;
		$this->authInfos = $authInfos;
		$this->eventTest = $eventTest;
		//$this->addFakeData();
		$this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);
	}
	
	public function DrawFrontPage()
	{
		$this->AddPage();

		$this->Image("../../generate/certificates/" . $this->event["certificateBgFile"], 0, 0, 297, 210, "JPG"); //Face page, background image
		$this->Image("../../generate/certificates/cmilogo.png", 5, 186, 40, null, "PNG"); //CMI logo image

		$subscriptionData = json_decode($this->studentData['subscriptionDataJson'] ?? '');
		$socialName = isset($subscriptionData) ? Data\getSubscriptionInfoFromDataObject($subscriptionData, "socialName") : null;

		if (!empty($socialName))
			$this->setY(70);
		else
			$this->setY(75);
		
		$this->setX(42.5);
		$this->SetFont('freesans','B',24);
		$this->MultiCell(212,13,$this->studentData["name"], 0, "C"); //Student name
		
		if (!empty($socialName))
		{
			$this->SetFont('freesans','I',16);
			$this->setX(42.5);
			$this->MultiCell(212,13,"Nome social: " . $socialName, 0, "C"); //Social name, if needed
		}

		/*$certMainText = 'Participou da Palestra "O cidadão na comunicação pública", on-line, tendo como palestrante a Professora Dr.ª Patrícia Guimarães Gil, promovida pela Câmara Municipal de Itapevi por meio da Escola do Parlamento "Doutor Osmar de Souza", no dia 22 de julho de 2021, às 19h, com carga horária total de 1 hora.';*/
		$this->SetFont('freesans','',13);
		$this->setX(42.5);
		$this->MultiCell(212, 6, $this->event["certificateText"], 0, "C"); //Main text

		$this->Cell(0, 11, "Itapevi, " . $this->formatEndDate($this->getLastEventDate()), 0, 2, "C");
	}
	
	public function DrawBackPage()
	{
		$this->AddPage(); //Back
		$this->Image("../../generate/certificates/certbackbottom.png", 0, 160, 297, 0, "PNG"); //Back logos
		//$this->addFakeData();

		if (count($this->eventDates) <= 49)
			$this->drawDatesTable();
		else if (count($this->eventDates) <= 124)
			$this->drawSimpleDatesTable();
		else
			$this->drawHoursOnly();

		$this->drawAuthenticationInfo();
		
	}
	
	private function getLastEventDate()
	{
		$biggerDate = date_create($this->eventDates[0]["date"]);
		foreach ($this->eventDates as $ed)
		{
			$cdate = date_create($ed["date"]);
			if ($cdate > $biggerDate)
				$biggerDate = $cdate;
		}
		
		return $biggerDate;
	}
	
	private function formatEndDate($dateTime)
	{
		$monthNumber = (int)$dateTime->format("m");
		$monthName = "";
		switch ($monthNumber)
		{
			case 1: $monthName = "janeiro"; break;
			case 2: $monthName = "fevereiro"; break;
			case 3: $monthName = "março"; break;
			case 4: $monthName = "abril"; break;
			case 5: $monthName = "maio"; break;
			case 6: $monthName = "junho"; break;
			case 7: $monthName = "julho"; break;
			case 8: $monthName = "agosto"; break;
			case 9: $monthName = "setembro"; break;
			case 10: $monthName = "outubro"; break;
			case 11: $monthName = "novembro"; break;
			case 12: $monthName = "dezembro"; break;
		}
		
		$dayNumber = (int)$dateTime->format("j") === 1 ? ("1º") : $dateTime->format("j");
		
		return $dayNumber . " de " . ($monthName) . " de " . $dateTime->format("Y");
	}
	
	/*
	private function addFakeData()
	{
		for ($i = 0; $i < 35; $i++)
		{
			$row = 
			[
				"name" => md5(mt_rand(1000, 2000)) . md5("AAAAA"),
				"date" => "00/00/0000",
				"beginTime" => "00:00:00",
				"endTime" => "00:00:00"
			];
			array_push($this->eventDates, $row);
		}
	}*/
	
	private function drawDatesTable()
	{
		$this->SetFont('freesans','B',12);
		
		$header = ["Conteúdo", "Data", "Horário"];
		
		// Column widths
		$w = count($this->eventDates) > 24 ? [80, 27, 30] : [180, 27, 30];
		
		// Header
		for($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		
		// Data
		$this->SetFont('freesans','',12);
		
		$truncateTextForDatesTable = function($self, $columnWidth, $text)
		{
			$maxTextLength = mb_strlen($text);	

			if ($self->GetStringWidth($text) > $columnWidth - 6)
				for ($c = 0; $c < mb_strlen($text); $c++)
					if ($self->GetStringWidth(mb_substr($text, 0, $c + 1)) > $columnWidth - 6)
					{
						$maxTextLength = $c;
						break;
					}

			return truncateText($text, $maxTextLength);
		};

		$beginX = $this->GetX();
		foreach($this->eventDates as $row)
		{
			$this->Cell($w[0], 6, $truncateTextForDatesTable($this, $w[0], $row["name"]), 1);
			$this->Cell($w[1], 6, date_format(date_create($row["date"]), "d/m/Y"), 1);
			$this->Cell($w[2], 6, date_create($row["beginTime"])->format("H:i") . " - " . date_create($row["endTime"])->format("H:i"), 1);	
			
			$this->Ln();
			if ($this->GetY() > $this->GetPageHeight() - 45)
			{
				$this->SetY(10);
				$beginX = 150;
			}
			$this->SetX($beginX);			
		}

		//Test extra hours
		$cth = null;
		if (isset($this->eventTest))
		{
			$cth = $this->eventTest->getClassTimeHours();
			$this->Cell($w[0], 6, "Avaliação", 1);
			$this->Cell($w[1] + $w[2], 6, $cth >= 2 ? $cth . ' horas ' : $cth . ' hora ', 1);
			$this->Ln();

			if ($this->GetY() > $this->GetPageHeight() - 45)
			{
				$this->SetY(10);
				$beginX = 150;
			}
			$this->SetX($beginX);	
		}

		//Closing line
		$this->Cell(array_sum($w), 6, ('Carga horária total: ' . round($this->combineHours(timeStampToHours($this->event["hours"]), $cth), 1) . "h"), 1, 0, "C");
	}

	private function drawSimpleDatesTable()
	{
		$this->SetFont('freesans','B',12);

		$this->Cell(0, 7, 'Datas', 1, 1, "C");

		$this->SetFont('freesans','',12);

		$eventDatesDatesAndTimes = array_map( 
			fn($row) => date_create($row['date'])->format('d/m/Y') . ' ' . date_create($row["beginTime"])->format("H:i") . '-' . date_create($row["endTime"])->format("H:i"),
			$this->eventDates);

		//Test extra hours
		$cth = null;
		if (isset($this->eventTest))
		{
			$cth = $this->eventTest->getClassTimeHours();
			$eventDatesDatesAndTimes[] = " $cth " . ($cth >= 2 ? ' horas' : ' hora ') . ': Avaliação';
		}

		$this->MultiCell(0, 6, implode(' | ', $eventDatesDatesAndTimes), 1);

		//Closing line
		$this->Cell(0, 6, ('Carga horária total: ' . round($this->combineHours(timeStampToHours($this->event["hours"]), $cth), 1) . "h"), 1, 0, "C");
	}

	private function drawHoursOnly()
	{
		$this->SetFont('freesans','',12);

		//Test extra hours
		$cth = null;
		if (isset($this->eventTest))
			$cth = $this->eventTest->getClassTimeHours();

		$this->Cell(0, 6, ('Carga horária total: ' . round($this->combineHours(timeStampToHours($this->event["hours"]), $cth), 1) . "h"), 1, 0, "C");
	}
	
	private function drawAuthenticationInfo()
	{
		$this->SetX(150);
		$this->SetY($this->GetPageHeight() - 42);
		$this->SetFont("freesans", "I", 11);
		
		$code = $this->authInfos["code"];
		$issueDateTime = date_format(date_create($this->authInfos["issueDateTime"]), "d/m/Y H:i:s");
				
		$authText = "Verifique a autenticidade deste certificado em: " . AUTH_ADDRESS . " e informe os seguintes dados: Código $code - Emissão inicial em $issueDateTime.";
		$this->MultiCell(200, 5, $authText, 0, "L");
	}

	private function combineHours(float $base, ?float $test) : float
	{
		if (isset($test))
			return $base + $test;
		else
			return $base;
	}
}

$conn = Connection::create();
$eventDataRow = getSingleEvent($_GET["eventId"], $conn);
$eventDatesDataRows = null;
$studentDataRow = null;
$eventTest = null;
define('minPercentageForApproval', (int)readSetting("STUDENTS_MIN_PRESENCE_PERCENT", $conn));

if ($eventDataRow["id"])
	$eventDatesDataRows = getEventDates($_GET["eventId"], $conn);
else
{
	$conn->close();
	die("Evento não encontrado!");
}

$studentDataRow = getStudentData($_GET["eventId"], $eventDataRow["subscriptionListNeeded"], $_GET["email"], $conn);

try
{
	$eventGetter = new \SisEpi\Model\Events\Event();
	$eventGetter->id = $_GET['eventId'];
	$event = $eventGetter->getSingle($conn);
	\SisEpi\Pub\Model\Events\CheckPreRequisitesForCertificate::tryCertificate($_GET['email'], $conn, $event, true);

	if (Connection::isId($event->testTemplateId))
	{
		$testGetter = new EventCompletedTest();
		
		if ((bool)$event->subscriptionListNeeded)
			$testGetter->subscriptionId = $studentDataRow['subscriptionId']; 
		else
			$testGetter->email = $studentDataRow['email']; 

		$testGetter->eventId = $event->id;
		$testGetter->setCryptKey(Connection::getCryptoKey());
		$eventTest = $testGetter->getSingleFromEventAndEmail_SubsId($conn, (bool)$event->subscriptionListNeeded);
	}
}
catch (Exception $e)
{
	die($e->getMessage());
}

if (!$studentDataRow)
{
	$conn->close();
	die("E-mail não localizado!");
}

if ($studentDataRow["presencePercent"] < minPercentageForApproval)
{
	$conn->close();
	die("Você não atingiu a presença mínima de " . minPercentageForApproval . "% para obter o certificado.");
}

if (!empty($eventDataRow['surveyTemplateId']) && !checkForExistentSurveyAnswer($_GET['eventId'], $studentDataRow['subscriptionId'] ?? null, $_GET['email'], $eventDataRow["subscriptionListNeeded"], $conn))
{
	$conn->close();
	$pageMessages = 'Preencha a pesquisa de satisfação para obter o seu certificado.';
	header('location:' . URL\URLGenerator::generateSystemURL('events2', 'fillsurvey', null, [ 'eventId' => $_GET['eventId'], 'email' => $_GET['email'], 'messages' => $pageMessages, 'backToGenCertificate' => 1 ]), true, 303);
	exit();
}

$certId = null;
$issueDateTime = null;

if ($alreadyIssuedCertificateDataRow = isCertificateAlreadyIssued($eventDataRow["id"], $studentDataRow["email"], $conn))
{
	$certId = $alreadyIssuedCertificateDataRow["id"];
	$issueDateTime = $alreadyIssuedCertificateDataRow["dateTime"];
}
else
{
	$issueDateTime = (string)date("Y-m-d H:i:s");
	$certId = saveCertificateInfos($eventDataRow["id"], $issueDateTime, $studentDataRow["email"], $conn);
}

$conn->close();

$pdf = new CertPDF("L", "mm", "A4");
$pdf->SetData($eventDataRow, $eventDatesDataRows, $studentDataRow, $eventTest, [ "code" => $certId, "issueDateTime" => $issueDateTime ] );
$pdf->DrawFrontPage();
$pdf->DrawBackPage();

header('Content-Type: application/pdf');
header('Content-Disposition: filename="'.$eventDataRow['name'].'.pdf"');

echo $pdf->output('S');

writeLog("Certificado gerado. id: $certId. Evento id: $eventDataRow[id]. E-mail: $_GET[email]");