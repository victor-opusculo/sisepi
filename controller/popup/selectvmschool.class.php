<?php
require_once "vendor/autoload.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

use SisEpi\Model\Database\Connection;

class selectvmschoolClass extends PopupBasePage
{
	protected $title = "SisEPI - Vereador Mirim: Selecionar Escola";
	
	protected $moduleName = "VMSCH";
	protected $permissionIdRequired = 1;
	
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = Connection::get();

        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\School();
            $getter->setCryptKey(Connection::getCryptoKey());
            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $schools = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'id' => fn($s) => $s->id,
                'Nome da Escola' => fn($s) => $s->name,
                'Diretor(a)' => fn($s) => $s->directorName,
                'E-mail corporativo' => fn($s) => $s->email,
                'Data de registro' => fn($s) => date_create($s->registrationDate)->format('d/m/Y H:i:s')
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($schools, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->selectButtonOnClick = "btnSelectVmSchool_onClick(event, {param})";
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