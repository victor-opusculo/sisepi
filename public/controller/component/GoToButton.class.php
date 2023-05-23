<?php

require_once __DIR__ . '/../../includes/URL/URLGenerator.php';
//public
class GoToButton extends ComponentBase
{
	protected $name = "GoToButton";

    public function __construct(?array $properties)
    {
        parent::__construct($properties);

        $this->actionsList = explode(",", $this->actions);
    }

    protected array $actionsList = [];
    protected string $actions = "";
    protected array $queryString = [];

    private function getLink(string $action, array $nextActions, array $queryString) : string
    {
        switch ($action)
        {
            case 'certificate':
                return URL\URLGenerator::generateSystemURL('events', 'gencertificate', null, [ ...$queryString, 'goTo' => implode(',', $nextActions) ] );
            case 'survey':
                return URL\URLGenerator::generateSystemURL('events2', 'fillsurvey', null, [ ...$queryString, 'goTo' => implode(',', $nextActions) ] );
            default:
                return '';
        }
    }

    public function getName(string $action) : string
    {
        switch ($action)
        {
            case 'certificate':
                return "Gerar certificado";
            case 'survey':
                return 'Preencher pesquisa de satisfação';
            default:
                return '';
        }
    }

    public function render()
    {
        $thisAction = array_pop($this->actionsList);
        $nextActions = $this->actionsList;
        $queryString = $this->queryString;
        $linkUrl = $this->getLink($thisAction, $nextActions, $queryString);
        $actionName = $this->getName($thisAction);

        $view = $this->get_view();
		require $view;
    }
}