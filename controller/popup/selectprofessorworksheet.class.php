<?php

use SisEpi\Model\Database\Connection;

require_once "vendor/autoload.php";
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class selectprofessorworksheetClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar ficha de trabalho";
	
	protected $moduleName = "PROFE";
	protected $permissionIdRequired = 13;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = Connection::get();
		
		try
		{
			$getter = new \SisEpi\Model\Professors\ProfessorWorkSheet();
			$getter->setCryptKey(Connection::getCryptoKey());
			$this->paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
			
			$partialWs = $getter->getMultiplePartially($conn, $this->paginatorComponent->pageNum, $this->paginatorComponent->numResultsOnPage, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

			$transformDataRowsRules =
			[
				'ID' => fn($ws) => $ws->id,
				'Plano de aula' => fn($ws) => truncateText($ws->getOtherProperties()->workProposalName, 40),
				'Atividade' => fn($ws) => truncateText($ws->getOtherProperties()->activityName, 40),
				'Docente' => fn($ws) => truncateText($ws->getOtherProperties()->professorName, 40),
				'Data de assinatura' => fn($ws) => date_create($ws->signatureDate)->format('d/m/Y'),
				'Valor de empenho' => fn($ws) => formatDecimalToCurrency($ws->getPaymentValue() ?? 0)
			];
			$this->dataRows = Data\transformDataRows($partialWs, $transformDataRowsRules);
			
			$this->dataGridComponent = new DataGridComponent($this->dataRows);
			$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
			$this->dataGridComponent->selectButtonOnClick = "btnSelectWorkSheet_onClick(event, {param})";
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
	}
	
	function render()
	{		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
	
}