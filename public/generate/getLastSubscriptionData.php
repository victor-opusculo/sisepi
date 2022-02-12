<?php

require("../model/database/students.database.php");

header('Content-Type: application/json; charset=utf-8');

$email = $_GET["email"] ?? "";
$dataRow = getLastSubscriptionByEmail($email);

$outputObject = [ "fields" => [] ];

if ($dataRow !== null)
{
	$outputObject["fields"][] = [ "field" => "txtName", "type" => "text", "value" => $dataRow["name"] ];
	$outputObject["fields"][] = [ "field" => "txtSocialName", "type" => "text", "value" => $dataRow["socialName"] ];
	if ($dataRow["socialName"])
		$outputObject["fields"][] = [ "field" => "chkUseSocialName", "type" => "check", "value" => true ];
	else
		$outputObject["fields"][] = [ "field" => "chkUseSocialName", "type" => "check", "value" => false ];
	$outputObject["fields"][] = [ "field" => "txtTelephone", "type" => "text", "value" => $dataRow["telephone"] ];
	$outputObject["fields"][] = [ "field" => "dateBirthDate", "type" => "date", "value" => $dataRow["birthDate"] ];
	$outputObject["fields"][] = [ "field" => "radGender", "type" => "radio", "value" => $dataRow["gender"] ];
	$outputObject["fields"][] = [ "field" => "radSchoolingLevel", "type" => "radio", "value" => $dataRow["schoolingLevel"] ];
	$outputObject["fields"][] = [ "field" => "radOccupation", "type" => "radio", "value" => $dataRow["occupation"] ];
	$outputObject["fields"][] = [ "field" => "radNationality", "type" => "radio", "value" => $dataRow["nationality"] ];
	$outputObject["fields"][] = [ "field" => "radRace", "type" => "radio", "value" => $dataRow["race"] ];
	$outputObject["fields"][] = [ "field" => "selUF", "type" => "select", "value" => $dataRow["stateUf"] ];
	$outputObject["fields"][] = [ "field" => "txtAccessibilityRequired", "type" => "text", "value" => $dataRow["accessibilityFeatureNeeded"] ];
	if ($dataRow["accessibilityFeatureNeeded"])
		$outputObject["fields"][] = [ "field" => "radAccessibilityRequired", "type" => "radio", "value" => "1" ];
	else
		$outputObject["fields"][] = [ "field" => "radAccessibilityRequired", "type" => "radio", "value" => "0" ];
}

echo json_encode($outputObject);
exit();