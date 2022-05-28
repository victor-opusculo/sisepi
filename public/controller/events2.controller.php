<?php
//public
final class events2 extends BaseController
{
	public function pre_searchcertificates()
	{
		$this->title = "SisEPI - Procurar certificados";
		$this->subtitle = "Procurar certificados";
	}
	
	public function searchcertificates()
	{
		require_once("model/database/certificate.database.php");
		require_once("controller/component/DataGrid.class.php");

		$searchResults = null;
		if (!empty($_GET['email']))
		{
			$searchResults = searchCertificates($_GET['email']);
		}

		$dataGridComponent = null;
		$transformDataRowsRules =
		[
			"id" => fn($dr) => $dr['id'],
			"Tipo" => fn($dr) => $dr['eventType'],
			"Evento" => fn($dr) => $dr['name']
		];

		if (!empty($searchResults))
		{
			$dataGridComponent = new DataGridComponent(Data\transformDataRows($searchResults, $transformDataRowsRules));
			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->customButtons['Baixar'] = URL\URLGenerator::generateFileURL("generate/generateCertificate.php", "eventId={eventidparam}&email={emailparam}"); 
			$dataGridComponent->customButtonsParameters['eventidparam'] = 'id';
			$dataGridComponent->customButtonsParameters['emailparam'] = new FixedParameter($_GET['email']);
		}
		else if (!empty($_GET['email']))
			$this->pageMessages[] = "Nenhum certificado encontrado para este e-mail.";
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
	}
	
}