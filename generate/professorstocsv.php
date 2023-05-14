<?php
require_once("checkLogin.php");
require_once("../model/Database/professors.database.php");
require_once "../vendor/autoload.php";
require_once("../includes/Data/namespace.php");

$getter = new \SisEpi\Model\Professors\Professor();
$getter->setCryptKey(getCryptoKey());
$conn = createConnectionAsEditor();
$preData = $getter->getAllForExport($conn, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

$fileName = "EPI-docentes_" . date("d-m-Y_H-i-s") . ".csv";
if (!$preData) die("Não há dados de acordo com o critério atual de pesquisa.");

$transformRules = 
[
	"ID" => fn($dbe) => $dbe->id,
	"Nome" => fn($dbe) => $dbe->name,
	"E-mail" => fn($dbe) => $dbe->email,
	"Telefone" => fn($dbe) => $dbe->telephone,
	"Escolaridade" => fn($dbe) => $dbe->schoolingLevel,
	"Temas de interesse" => fn($dbe) => $dbe->topicsOfInterest,
	"Etnia" => fn($dbe) => $dbe->race,
	"Lattes" => fn($dbe) => $dbe->lattesLink,
	"Recolhe INSS?" => fn($dbe) => isset($dbe->collectInss) ? ((bool)$dbe->collectInss ? 'Sim' : 'Não') : 'Indefinido',
	"RG" => fn($dbe) => $dbe->personalDocsJson->rg . ' ' . $dbe->personalDocsJson->rgIssuingAgency,
	"CPF" => fn($dbe) => $dbe->personalDocsJson->cpf,
	"PIS/PASEP" => fn($dbe) => $dbe->personalDocsJson->pis_pasep,
	"Endereço" => fn($dbe) => $dbe->homeAddressJson->street . ' Nº ' . $dbe->homeAddressJson->number . ($dbe->homeAddressJson->complement ? ' ' . $dbe->homeAddressJson->complement : '') . ' - Bairro: ' . $dbe->homeAddressJson->neighborhood,
	"Cidade/Estado" => fn($dbe) => $dbe->homeAddressJson->city . '/' . $dbe->homeAddressJson->state, 
	"Banco" => fn($dbe) => $dbe->bankDataJson->bankName,
	"Agência" => fn($dbe) => $dbe->bankDataJson->agency,
	"Conta" => fn($dbe) => $dbe->bankDataJson->account,
	"Chave PIX" => fn($dbe) => $dbe->bankDataJson->pix,
	"Concorda com o termo de consentimento" => fn($dbe) => (bool)$dbe->agreesWithConsentForm ? 'Sim' : 'Não',
	"Versão do termo de consentimento" => fn($dbe) => $dbe->consentForm,
	"Data de cadastro" => fn($dbe) => date_create($dbe->registrationDate)->format("d/m/Y H:i:s")
];

$fullData = Data\transformDataRows($preData, $transformRules);

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");
$header = array_keys($fullData[0]);


fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
//fwrite($output, "sep=," . PHP_EOL);

fputcsv($output, $header, ";");

foreach($fullData as $row)
{
	fputcsv($output, $row, ";");
}

fclose($output);