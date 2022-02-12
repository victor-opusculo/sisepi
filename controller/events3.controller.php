<?php
require_once("model/database/certificates.database.php");
require_once("model/GenericObjectFromDataRow.class.php");

final class events3 extends BaseController
{
    public function pre_viewcertificates()
    {
        $this->title = "SisEPI - Ver certificados emitidos";
		$this->subtitle = "Ver certificados emitidos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 11;
    }

    public function viewcertificates()
    {
        require_once("controller/component/DataGrid.class.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;
        $certsDataGrid = new DataGridComponent();
        $eventObj = null;
        $certsDataRows = null;
        $certsCount = 0;
		
		$transformDataRowsRules =
		[
			'id' => fn($row) => $row['id'],
			'Nome' => fn($row) => $row['name'] . ( !empty($row['socialName']) ? ' (' . $row['socialName'] . ')' : '' ),
			'E-mail' => fn($row) => $row['email'],
			'Data de emissão' => fn($row) => date_format(date_create($row['dateTime']), "d/m/Y H:i:s")
		];

        $conn = createConnectionAsEditor();
        try
        {
            $eventObj = new GenericObjectFromDataRow(getEventInfos($eventId, $conn));
            $certsDataRows = getCertificates($eventId, (bool)$eventObj->subscriptionListNeeded, $conn);
            $certsDataGrid->dataRows = Data\transformDataRows($certsDataRows, $transformDataRowsRules);
            $certsCount = isset($certsDataRows) ? count($certsDataRows) : 0;

            $certsDataGrid->columnsToHide[] = "id";
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = "Evento não localizado.";
            $this->pageMessages[] = $e->getMessage();
        }
        finally
        {
            $conn->close();
        }
        
        $this->view_PageData['eventObj'] = $eventObj;
        $this->view_PageData['dgComp'] = $certsDataGrid;
        $this->view_PageData['certsCount'] = $certsCount;
    }
}