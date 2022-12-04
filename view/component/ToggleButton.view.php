<div class="inputToggleButton">
    <label>
        <input type="checkbox" />
        <span class="inputToggleButtonFace">
            <?php
            if ($buttonContent instanceof ComponentBase)
                $buttonContent->render();
            elseif (is_string($buttonContent))
                echo $buttonContent;
            else
                die('ToggleButtonComponent: buttonContent não é componente e nem string.');
            ?>
        </span>
    </label>
</div>