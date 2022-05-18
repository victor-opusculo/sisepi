<?php

require("../includes/tfpdf/tfpdf.php");
require("../includes/common.php");
require("../includes/logEngine.php");
require("../model/database/certificate.database.php");
require("../model/database/generalsettings.database.php");

define('AUTH_ADDRESS', "http://" . $_SERVER["HTTP_HOST"] . URL\URLGenerator::generateSystemURL("events", "authcertificate"));

class PDF extends tFPDF
{
	private $event;
	private $eventDates;
	private $studentData;
	private $authInfos;
	
	public function SetData($event, $eventDates, $studentData, $authInfos)
	{
		$this->event = $event;
		$this->eventDates = $eventDates;
		$this->studentData = $studentData;
		$this->authInfos = $authInfos;
		//$this->addFakeData();
		$this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);
	}
	
	public function DrawFrontPage()
	{
		$this->AddPage();

		$this->Image("certificates/" . $this->event["certificateBgFile"], 0, 0, 297, 210, "JPG"); //Face page, background image
		$this->Image("certificates/cmilogo.png", 5, 186, 40, null, "PNG"); //CMI logo image

		if (!empty($this->studentData["socialName"]))
			$this->setY(70);
		else
			$this->setY(75);
		
		$this->setX(42.5);
		$this->SetFont('freesans','B',24);
		$this->MultiCell(212,13,$this->studentData["name"], 0, "C"); //Student name
		
		if (!empty($this->studentData["socialName"]))
		{
			$this->SetFont('freesans','I',16);
			$this->setX(42.5);
			$this->MultiCell(212,13,"Nome social: " . $this->studentData["socialName"], 0, "C"); //Social name, if needed
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
		$this->Image("certificates/certbackbottom.png", 0, 160, 297, 0, "PNG"); //Back logos
		//$this->addFakeData();
		$this->drawDatesTable();
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
	
	/*private function addFakeData()
	{
		for ($i = 0; $i < 45; $i++)
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
		
		function truncateTextForDatesTable($self, $columnWidth, $text)
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
		}

		$beginX = $this->GetX();
		foreach($this->eventDates as $row)
		{
			$this->Cell($w[0], 6, truncateTextForDatesTable($this, $w[0], $row["name"]), 1);
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
		//Closing line
		$this->Cell(array_sum($w), 6, ('Carga horária total: ' . round(timeStampToHours($this->event["hours"]), 1) . "h"), 1, 0, "C");
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
}

$conn = createConnectionAsEditor();
$eventDataRow = getSingleEvent($_GET["eventId"], $conn);
$eventDatesDataRows = null;
$studentDataRow = null;
define('minPercentageForApproval', (int)readSetting("STUDENTS_MIN_PRESENCE_PERCENT", $conn));

if ($eventDataRow["id"])
	$eventDatesDataRows = getEventDates($_GET["eventId"], $conn);
else
{
	$conn->close();
	die("Evento não encontrado!");
}

if (!$eventDataRow["certificateText"])
{
	$conn->close();
	die("Este evento não fornece certificados automaticamente.");
}

if (!isEventOver($_GET["eventId"], $conn))
{
	$conn->close();
	die("Este evento ainda não terminou.");
}

$studentDataRow = getStudentData($_GET["eventId"], $eventDataRow["subscriptionListNeeded"], $_GET["email"], $conn);

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

$pdf = new PDF("L", "mm", "A4");
$pdf->SetData($eventDataRow, $eventDatesDataRows, $studentDataRow, [ "code" => $certId, "issueDateTime" => $issueDateTime ] );
$pdf->DrawFrontPage();
$pdf->DrawBackPage();
$pdf->Output();

writeLog("Certificado gerado. id: $certId. Evento id: $eventDataRow[id]. E-mail: $_GET[email]");