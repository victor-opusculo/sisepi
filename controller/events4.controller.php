<?php

use SisEpi\Model\Database\Connection;

require_once "vendor/autoload.php";

final class events4 extends BaseController
{
    public function pre_viewcompletedtest()
    {
        $this->title = "SisEPI - Ver avaliação";
		$this->subtitle = "Ver avaliação";
		
		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 5;
    }

    public function viewcompletedtest()
    {
        $testId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : null;

        $conn = Connection::get();
        $test = null;
        try
        {
            $testGetter = new \SisEpi\Model\Events\EventCompletedTest();
            $testGetter->id = $testId;
            $testGetter->setCryptKey(Connection::getCryptoKey());
            $test = $testGetter->getSingle($conn);
            $test->setCryptKey(Connection::getCryptoKey());
            $test->fetchStudentNameAndEmail($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['testObj'] = $test;
    }

    public function pre_deletesingletest()
    {
        $this->title = "SisEPI - Excluir avaliação";
		$this->subtitle = "Excluir avaliação";
		
		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 6;
    }

    public function deletesingletest()
    {
        $this->viewcompletedtest();
    }
}