<?php

final class settings extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Configurações";
		$this->subtitle = "Configurações";
	}
	
	public function home()
	{
		require_once("model/Database/user.settings.database.php");
		require_once("model/Database/enums.settings.database.php");
		require_once("model/Database/generalsettings.database.php");

		$manageUsersSelectedUserId = isset($_GET['umUserId']) && is_numeric($_GET['umUserId']) ? $_GET['umUserId'] : $_SESSION["userid"];
				
		$conn = createConnectionAsEditor();
		
		$currSelectedUserPermissions = getUserPermissions($manageUsersSelectedUserId, $conn);
		
		$userList = getUsersList($conn);
		$permissionList = getPermissionsList($conn);
		
		$enums = [];
		$enums["EVENT"] = getEnum("EVENT", $conn);
		$enums["GENDER"] = getEnum("GENDER", $conn);
		$enums["OCCUPATION"] = getEnum("OCCUPATION", $conn);
		$enums["SCHOOLING"] = getEnum("SCHOOLING", $conn);
		$enums["RACE"] = getEnum("RACE", $conn);
		$enums["NATION"] = getEnum("NATION", $conn);
		$enums["UF"] = getEnum("UF", $conn);
		$enums["LIBACQTYPE"] = getEnum("LIBACQTYPE", $conn);
		
		$generalSettings = readAllSettings($conn);
		$termsList = getTermsList($conn);
		
		$conn->close();
		
		$pageData =
		[
			"userName" => $_SESSION['username'],
			"userList" => $userList,
			"permissionList" => $permissionList,
			"currSelectedUserId" => $manageUsersSelectedUserId,
			"currSelectedUserPermissions" => $currSelectedUserPermissions,
			"enums" => $enums,
			"generalSettings" => $generalSettings,
			"termsList" => $termsList
		];
		
		$this->view_PageData['pageData'] = $pageData;
	}

	public function pre_editeventlocations()
	{
		$this->title = "SisEPI - Configurações: Editar locais de eventos";
		$this->subtitle = "Editar locais de eventos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 13;
	}

	public function editeventlocations()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		require_once("model/Database/eventlocations.database.php");

		$allLocationsDrs = array_map( function($dr)
		{
			$obj = new GenericObjectFromDataRow($dr);
			$obj->calendarInfoBoxStyleJson = json_decode($obj->calendarInfoBoxStyleJson);
			return $obj;
			
		}, getAllLocations());
		$locationTypes = [ 'physical' => 'Físico/presencial', 'online' => 'On-line' ];

		$this->view_PageData['locationsDataRows'] = $allLocationsDrs;
		$this->view_PageData['locationTypes'] = $locationTypes;
	}
}

function checkPermissionInList($permissionsDataRows, $module, $id) 
{
	if ($permissionsDataRows)
		foreach ($permissionsDataRows as $pdr)
		{
			if ($pdr["permMod"] === $module && $pdr["permId"] === $id)
				return true;
		}
	return false;
}