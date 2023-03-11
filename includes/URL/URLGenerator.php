<?php
namespace URL;

abstract class URLGenerator
{
	const URLBase = '/sisepi';
	public static bool $useFriendlyURL;
	
	private static function appendQueryString($queryString = null, $directFileAccess = false)
	{
		$qsFinal = "";
		if (is_string($queryString))
			$qsFinal = $queryString;
		else if (is_array($queryString))
			$qsFinal = http_build_query($queryString);
		
		if (!empty($qsFinal))
			return self::$useFriendlyURL || $directFileAccess ? "?$qsFinal" : "&$qsFinal";
		else 
			return "";
	}
	
	public static function generateSystemURL($controller, $action = "home", $id = null, $queryString = null)
	{					
		$url = self::$useFriendlyURL ? self::URLBase . "/$controller/$action" : self::URLBase . "/index.php?cont=$controller&action=$action";
		
		if (!empty($id))
			$url .= self::$useFriendlyURL ? "/$id" : "&id=$id";
							
		$url .= self::appendQueryString($queryString);
		
		return $url;
	}
	
	public static function generatePublicSystemURL($controller, $action = "home", $id = null, $queryString = null)
	{
		$url = self::$useFriendlyURL ? self::URLBase . "/public/$controller/$action" : self::URLBase . "/public/index.php?cont=$controller&action=$action";
		
		if (!empty($id))
			$url .= self::$useFriendlyURL ? "/$id" : "&id=$id";
							
		$url .= self::appendQueryString($queryString);
		
		return $url;
	}
	
	public static function generateFileURL($pathFromBaseURL = null, $queryString = null)
	{
		$fileBaseFriendly = self::URLBase . '/file';
		$fileBaseUnfriendly = self::URLBase;
		$url = (self::$useFriendlyURL ? $fileBaseFriendly : $fileBaseUnfriendly) . (!empty($pathFromBaseURL) ? "/$pathFromBaseURL" : "") . self::appendQueryString($queryString, true);
		return $url;
	}
	
	public static function generatePopupURL($popupPage, $queryString = null)
	{
		$url = self::$useFriendlyURL ? self::URLBase . "/popup/$popupPage" : self::URLBase . "/popup.php?page=$popupPage";
		$url .= self::appendQueryString($queryString);
		return $url;
	}
	
	public static function decodeJSONStruct($json) : string
	{
		$obj = is_object($json) ? $json : json_decode($json);

		if (isset($obj->system))
			return self::generateSystemURL($obj->system->controller, $obj->system->action, $obj->system->id, $obj->system->queryString);
		else if (isset($obj->publicSystem))
			return self::generatePublicSystemURL($obj->system->controller, $obj->system->action, $obj->system->id, $obj->system->queryString);
		else if (isset($obj->file))
			return self::generateFileURL($obj->file->path, $obj->file->queryString);
		else if (isset($obj->popup))
			return self::generatePopupURL($obj->popup->page, $obj->popup->queryString);
		else if (isset($obj->direct))
			return $obj->direct;
	}
}

abstract class JSONStructURLGenerator
{
	public static function generateSystemURL($controller, $action = "home", $id = null, $queryString = null) : string
	{
		$obj =
		[
			"system" => [ "controller" => $controller, "action" => $action, "id" => $id, "queryString" => $queryString ]
		];
		return json_encode($obj);
	}

	public static function generatePublicSystemURL($controller, $action = "home", $id = null, $queryString = null) : string
	{
		$obj =
		[
			"publicSystem" => [ "controller" => $controller, "action" => $action, "id" => $id, "queryString" => $queryString ]
		];
		return json_encode($obj);
	}

	public static function generateFileURL($pathFromBaseURL = null, $queryString = null) : string
	{
		$obj = 
		[
			"file" => [ "path" => $pathFromBaseURL, "queryString" => $queryString ]
		];
		return json_encode($obj);
	}

	public static function generatePopupURL($popupPage, $queryString = null) : string
	{
		$obj =
		[
			"popup" => [ "page" => $popupPage, "queryString" => $queryString ]
		];
		return json_encode($obj);
	}

	public static function useDirectURL($url) : string
	{
		$obj =
		[
			'direct' => $url
		];
		return json_encode($obj);
	}
}

$sisepiConfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/sisepi_config.ini", true);
URLGenerator::$useFriendlyURL = (bool)$sisepiConfig['urls']['usefriendly'] ?? false;