<?php
//Public
// OUR DEFAULT TITLE TO USE IF WE DON'T SPECIFICALLY SET ONE
define('gb_title', 'SisEPI');

require_once("URL/URLGenerator.php");
require_once("URL/QueryString.php");
require_once  __DIR__ . "/Data/namespace.php";

define('SISEPI_BASEDIR', __DIR__ . "/../..");
define('PROFESSORS_UPLOADS_DIR', SISEPI_BASEDIR . "/uploads/professors");
define('_SYSTEM_TTFONTS', __DIR__ . '/../../includes/Fonts/');

function getHttpProtocolName()
{
    $isHttps = $_SERVER['HTTPS'] ?? $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    $isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);
    return $isHttps ? 'https' : 'http';
}

function formatPersonNameCase($fullName)
{
	return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
}

function formatDecimalToCurrency($decimal)
{
	return "R$ " . number_format($decimal, 2, ',', '.');
}

function hsc($stringData)
{
	return htmlspecialchars($stringData ?? '', ENT_NOQUOTES, "UTF-8");
}

function hscq($stringData)
{
	return htmlspecialchars($stringData ?? '', ENT_QUOTES, "UTF-8");
}

function timeStampToHours($timeStamp)
{
	$timeParse = explode(":", $timeStamp);
	$seconds = $timeParse[0] * 3600 + $timeParse[1] * 60 + $timeParse[2];
	return $seconds / 3600;
}

function truncateText($text, $maxLength)
{
	if (is_null($text)) return '';
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
      
  protected $action;
  protected $view_PageData;
  
  public $pageMessages;
   
  public function __construct($action)
  {
    $this->action = $action;
	$this->pageMessages = [];
	$this->view_PageData = [];
	
	$preActionMethod = 'pre_' . $action;
	if (method_exists($this, $preActionMethod))
		$this->$preActionMethod();
	
	if (method_exists($this, $action))
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
  
  protected function get_view()
  {
	  
    $view = "view/" . get_class($this) . "." . $this->action . ".view.php";
    if (file_exists($view)) 
	{
		return $view;
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
		
	public function __construct(array $properties = null)
	{
		if (isset($properties))
			foreach ($properties as $key => $value)
				$this->$key = $value;
	}
	
	public function get_view()
	{
		$view = "view/component/" . $this->name . '.view.php';
		if (file_exists($view)) 
		{
			return $view;
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