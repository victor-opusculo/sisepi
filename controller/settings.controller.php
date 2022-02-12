<?php
require_once("model/database/user.settings.database.php");
require_once("model/database/enums.settings.database.php");
require_once("model/database/generalsettings.database.php");

final class settings extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Configurações";
		$this->subtitle = "Configurações";
	}
	
	public function home()
	{
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
		$enums["LIBCOLTYPE"] = getEnum("LIBCOLTYPE", $conn);
		$enums["LIBACQTYPE"] = getEnum("LIBACQTYPE", $conn);
		$enums["LIBPERIOD"] = getEnum("LIBPERIOD", $conn);
		
		$generalSettings = readAllSettings($conn);
		
		$conn->close();
		
		$pageData =
		[
			"userName" => $_SESSION['username'],
			"userList" => $userList,
			"permissionList" => $permissionList,
			"currSelectedUserId" => $manageUsersSelectedUserId,
			"currSelectedUserPermissions" => $currSelectedUserPermissions,
			"enums" => $enums,
			"generalSettings" => $generalSettings
		];
		
		$this->view_PageData['pageData'] = $pageData;
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