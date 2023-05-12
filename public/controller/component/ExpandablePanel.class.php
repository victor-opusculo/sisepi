<?php
//public
namespace SisEpi\Pub\Controller\Component;

class ExpandablePanel extends \ComponentBase
{
	protected $name = "ExpandablePanel";
    protected array $children = [];
    protected string $caption;
    protected int $tabIndex = -1;

    public function __construct(array $properties = null)
    {
        parent::__construct($properties);
    }

    public function render()
    {
        $children = $this->children;
        $caption = $this->caption;
        $tabIndex = $this->tabIndex;

        $view = $this->get_view();
		require($view);
    }

    public static function writeCssRules()
    { ?>
        <style>
            .expandablePanelContent 
            { 
                display: none; 
                margin: 0.5em;;
            }
            .expandablePanelArea
            {
                border: 1px solid darkgray;
                border-radius: 8px;
                padding: 0;
                display: flex;
                flex-direction: column;
                margin-bottom: 0.4em;
            }

            .expandablePanelButton
            {
                padding: 0.5em;
                display: flex;
                flex-direction: row;
                background-color: #EEE;
                border-radius: 8px;
                cursor: pointer;
            }

            .expandablePanelButton:hover
            {
                background-color: #E0E0E0;
            }

            .expandablePanelArea:focus-within > .expandablePanelContent
            {
                display: block;
            }

            .expandablePanelArea:focus-within > .expandablePanelButton
            {
                background-color: #DDD;
                font-weight: bold;
                border-radius: 8px 8px 0 0;
            }

            .expandablePanelArea:focus-within > .expandablePanelButton > .expandablePanelButtonArrow > span
            {
                transform: rotate(90deg);
            }

            .expandablePanelButtonArrow
            {
                flex: 10%; 
                display: flex;
                align-items: center;
                flex-direction: row-reverse;
                font-weight: bold;
            }
        </style>
    <?php }
}