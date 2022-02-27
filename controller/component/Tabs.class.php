<?php

class TabsComponent extends ComponentBase
{
    protected $name = "Tabs";
    private $identifierName;
    
    private $isFrameOpen = false;
    private $isTabOpen = false;
    private $tabCount = 0;

    public function __construct($identifierName)
    {
        parent::__construct();

        $this->identifierName = $identifierName; 
    }

    public function render($beginTabsFrame = false)
	{		
		$view = $this->get_view();
		require_once($view);

        if ($beginTabsFrame)
            $this->beginTabsFrame();
	}

    public function beginTabsFrame()
    {
        if ($this->isFrameOpen) throw new TabsComponentException("Frame já aberto.", $this);
        
        echo '<div class="tabsComponent_tabs">';

        $this->isFrameOpen = true;
    }

    public function beginTab($tabName, $selected = false)
    {
        if (!$this->isFrameOpen) throw new TabsComponentException("Frame não aberto.", $this);
        if ($this->isTabOpen) throw new TabsComponentException("Aba já aberta.", $this);

        $checked = $selected ? 'checked="checked"' : '';
        $tabId = $this->identifierName . ++$this->tabCount;

        echo "<input type=\"radio\" class=\"tabsComponent_inputRadio\" name=\"$this->identifierName\" id=\"$tabId\" $checked>";
        echo "<label class=\"tabsComponent_label\" for=\"$tabId\">$tabName</label>";
        echo "<div class=\"tabsComponent_tab\">";

        $this->isTabOpen = true;
    }

    public function endToBeginTab($tabName, $selected = false)
    {
        $this->endTab();
        $this->beginTab($tabName, $selected);
    }

    public function endTab()
    {
        if (!$this->isTabOpen) throw new TabsComponentException("Aba não aberta.", $this);
        if (!$this->isFrameOpen) throw new TabsComponentException("Frame não aberto.", $this);

        echo "</div>";

        $this->isTabOpen = false;
    }

    public function endTabsFrame()
    {
        if (!$this->isFrameOpen) throw new TabsComponentException("Frame ainda fechado.", $this);
        if ($this->isTabOpen) throw new TabsComponentException("Não é possível fechar o frame. Aba ainda aberta.", $this);

        echo '</div>';
        $this->isFrameOpen = false;
    }
}

class TabsComponentException extends Exception
{
    private TabsComponent $compInstance;

    public function __construct(string $message, TabsComponent $componentInstance = null)
    {
        parent::__construct('TabsComponent: ' . $message);
        $this->compInstance = $componentInstance;
    }

    public function getComponentInstance()
    {
        return $this->compInstance;
    }
}