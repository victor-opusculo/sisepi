<?php

require_once("controller/component/NextEventsWidget.class.php");
require_once("controller/component/NextChecklistsWidget.class.php");
require_once("controller/component/LatestProfessorsWidget.class.php");
require_once("controller/component/LibraryStatisticsWidget.class.php");
require_once("controller/component/LibraryNextDevolutionsWidget.class.php");

final class homepage extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Sistema de Informações da Escola do Parlamento de Itapevi";
		$this->subtitle = "Bem-vindo(a) ao SisEPI!";
	}
	
	public function home()
	{
		$conn = createConnectionAsEditor();
		
		$this->view_PageData['nextEventsWidget'] = new NextEventsWidget($conn);
		$this->view_PageData['latestProfessorsWidget'] = new LatestProfessorsWidget($conn);
		$this->view_PageData['libraryStatisticsWidget'] = new LibraryStatisticsWidget($conn);
		$this->view_PageData['libraryNextDevolutionsWidget'] = new LibraryNextDevolutionsWidget($conn);
		$this->view_PageData['nextChecklistsWidget'] = new NextChecklistsWidget($conn);
		
		$conn->close();
	}

	public function pre_messages()
	{
		$this->title = "SisEPI - " . ($_GET['title'] ?? 'Sistema de Informações da Escola do Parlamento de Itapevi');
		$this->subtitle = $_GET['title'] ?? 'SisEPI';
	}

	public function message() { }
}