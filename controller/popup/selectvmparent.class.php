<?php
require_once "model/database/database.php";
require_once "model/vereadormirim/VmParent.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

class selectvmparentClass extends PopupBasePage
{
	protected $title = "SisEPI - Vereador Mirim: Selecionar Responsável";
	
	protected $moduleName = "VMPAR";
	protected $permissionIdRequired = 1;
	
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = createConnectionAsEditor();
        $getter = new \SisEpi\Model\VereadorMirim\VmParent();
        $getter->setCryptKey(getCryptoKey());

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $parents = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'ID' => fn($p) => $p->id,
                'Nome' => fn($p) => $p->name,
                'E-mail' => fn($p) => $p->email
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($parents, $transformRules));
            $dataGridComponent->RudButtonsFunctionParamName = 'ID';
            $dataGridComponent->selectButtonOnClick = "btnSelectVmParent_onClick(event, {param})";
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }
		
		$this->dataGridComponent = $dataGridComponent;
		$this->paginatorComponent = $paginatorComponent;
	}
	
	function render()
	{		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
}