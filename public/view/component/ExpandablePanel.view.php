
<div class="expandablePanelArea" tabindex="<?= $tabIndex ?>">
    <div class="expandablePanelButton">
        <span style="flex: 90%;"><?= hsc($caption) ?></span>  
        <span class="expandablePanelButtonArrow"><span>&#10140;</span></span>
    </div>
    
    <div class="expandablePanelContent">
        <?php foreach ($children as $child)
        {
            if ($child instanceof \ComponentBase)
                $child->render();
            else if (is_string($child))
                echo $child;
        }?>
    </div>
</div>