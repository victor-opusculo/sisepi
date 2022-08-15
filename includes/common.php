<?php
// OUR DEFAULT TITLE TO USE IF WE DON'T SPECIFICALLY SET ONE
define('gb_title', 'SisEPI');

require_once("URL/URLGenerator.php");
require_once("URL/QueryString.php");
require_once("Data/namespace.php");

define('SISEPI_BASEDIR', __DIR__ . "/..");
define('PROFESSORS_UPLOADS_DIR', SISEPI_BASEDIR . "/uploads/professors");

function getHttpProtocolName()
{
    $isHttps = $_SERVER['HTTPS'] ?? $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    $isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);
    return $isHttps ? 'https' : 'http';
}

function checkUserPermission($module, $id) 
{
	if (!isset($_SESSION['permissions'][$module]))
		return false;
	
	return in_array($id, $_SESSION['permissions'][$module]);
}

function formatDecimalToCurrency($decimal)
{
	return "R$ " . number_format($decimal, 2, ',', '.');
}

function hsc($stringData)
{
    return htmlspecialchars($stringData, ENT_NOQUOTES, "UTF-8");
}

function hscq($stringData)
{
	return htmlspecialchars($stringData, ENT_QUOTES, "UTF-8");
}

function truncateText($text, $maxLength)
{
	return mb_strlen($text, 'UTF-8') > $maxLength ? (mb_substr($text, 0, $maxLength, 'UTF-8') . "...") : ($text);
}

function dateInFullString($date)
{
	$dt = $date instanceof DateTime ? $date : new DateTime($date);
	
	$format = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, 
              IntlDateFormatter::NONE, NULL, IntlDateFormatter::GREGORIAN);

	return $format->format($dt);
}

class BaseController 
{
  protected $title = gb_title;
  protected $subtitle = gb_title;
    
  protected $hasUserPermission;
  protected $moduleName;
  protected $permissionIdRequired;
  
  protected $action;
  protected $view_PageData;
  protected $actionData;
  
  public $pageMessages;
   
  public function __construct($action, $actionData = null)
  {
    $this->action = $action;
	$this->pageMessages = [];
	$this->view_PageData = [];
  $this->actionData = $actionData;
	
	$preActionMethod = 'pre_' . $action;
	if (method_exists($this, $preActionMethod))
		$this->$preActionMethod();
	
	$this->hasUserPermission = (!$this->permissionIdRequired) ? true : checkUserPermission($this->moduleName, $this->permissionIdRequired);
	
	if ($this->hasUserPermission && method_exists($this, $action))
		$this->$action();
	
	//Get page messages from query string
	if (isset($_GET['messages']) && strlen($_GET['messages']) > 0)
		$this->pageMessages = explode('//', $_GET['messages']);
  }
  
  public function title()
  {
    echo $this->title;
  }
  
  public function hasSubtitle() { return !empty($this->subtitle); }
  
  public function subtitle()
  {
	  echo $this->subtitle;
  }
  
  public function inheritViewPageData(array $foreignViewPageData)
  {
      $this->view_PageData = array_merge($this->view_PageData, $foreignViewPageData);
  }

  protected function getActionData($key)
  {
      if (isset($this->actionData) && array_key_exists($key, $this->actionData))
          return $this->actionData[$key];
      else
          return $_GET[$key] ?? null;
  }

  protected function wasPageCalledDirectly()
  {
      return $_GET['cont'] === static::class && $_GET['action'] === $this->action;
  }

  protected function get_view()
  {
	  
    $view = "view/" . get_class($this) . "." . $this->action . ".view.php";
    if (file_exists($view)) 
	{
		if ($this->hasUserPermission)
			return $view;
		else
			return 'view/_nopermission.view.php';
    }
    return 'view/_error.view.php';
  }
  
  public function render()
  {
	foreach ($this->view_PageData as $key => $value)
		$$key = $value;
	  
    $view = $this->get_view();
    require_once($view);
  }
}

abstract class ComponentBase
{
	protected $name = null;
	
	protected $hasUserPermission;
	protected $moduleName;
	protected $permissionIdRequired;
	
	public function __construct()
	{
		$this->hasUserPermission = (!$this->permissionIdRequired) ? true : checkUserPermission($this->moduleName, $this->permissionIdRequired);
	}
	
	public function get_view()
	{
		$view = "view/component/" . $this->name . '.view.php';
		if (file_exists($view)) 
		{
			if($this->hasUserPermission)
				return $view;
			else
				return 'view/nopermission.view.php';
		}
		return 'view/error.view.php';
	}
	
	public function render()
	{
		$view = $this->get_view();
		require_once($view);
	}
}

class PopupBasePage 
{
  protected $title = gb_title;
    
  protected $hasUserPermission;
  protected $moduleName;
  protected $permissionIdRequired;

  public $page;
  
  public function __construct($page)
  {
    $this->page = $page;
	
	$this->hasUserPermission = (!$this->permissionIdRequired) ? true : checkUserPermission($this->moduleName, $this->permissionIdRequired);
	
	if ($this->hasUserPermission)
		$this->postConstruct();
  }
  
  protected function postConstruct()
  {
	  
  }
  
  public function title()
  {
    echo $this->title;
  }

  protected function get_view()
  {
	  
    $view = "view/popup/" . $this->page . '.view.php';
    if (file_exists($view)) 
	{
		if ($this->hasUserPermission)
			return $view;
		else
			return 'view/_nopermission.view.php';
    }
    return 'view/_error.view.php';
  }
  
  public function render()
  {
    $view = $this->get_view();
    require_once($view);
  }
}
?>