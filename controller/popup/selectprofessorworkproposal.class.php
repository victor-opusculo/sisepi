<?php
require_once "model/professors/ProfessorWorkProposal.php";
require_once "model/database/database.php";
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class selectprofessorworkproposalClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar plano de aula";
	
	protected $moduleName = "PROFE";
	protected $permissionIdRequired = 5;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		try
		{
			$getter = new \SisEpi\Model\Professors\ProfessorWorkProposal();
			$getter->setCryptKey(getCryptoKey());
			$this->paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
			
			$partialWp = $getter->getMultiplePartially($conn, $_GET['q'] ?? '', $_GET['orderBy'] ?? '', $this->paginatorComponent->pageNum, $this->paginatorComponent->numResultsOnPage);

			$approvedIcon = new DataGridIcon("pics/check.png", "Aprovado"); $approvedIcon->textAfterIcon = "Aprovado";
			$rejectedIcon = new DataGridIcon("pics/wrong.png", "Rejeitado"); $rejectedIcon->textAfterIcon = "Rejeitado";
			$pendingIcon = new DataGridIcon("pics/delay.png", "Pendente"); $pendingIcon->textAfterIcon = "Pendente";
			$transformDataRowsRules =
			[
				'ID' => fn($wp) => $wp->id,
				'Tema' => fn($wp) => truncateText($wp->name, 60),
				'Docente dono' => fn($wp) => truncateText($wp->getOtherProperties()->ownerProfessorName, 30),
				'Data de envio' => fn($wp) => date_create($wp->registrationDate)->format('d/m/Y H:i:s'),
				'Status' => fn($wp) => is_null($wp->isApproved) ? $pendingIcon : ((bool)$wp->isApproved ? $approvedIcon : $rejectedIcon)
			];
			$this->dataRows = Data\transformDataRows($partialWp, $transformDataRowsRules);
			
			$this->dataGridComponent = new DataGridComponent($this->dataRows);
			$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
			$this->dataGridComponent->selectButtonOnClick = "btnSelectWorkProposal_onClick(event, {param})";
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