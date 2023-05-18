<form action="<?php echo URL\URLGenerator::generateFileURL("post/eventchecklisttemplates.edit.post.php", "cont=eventchecklisttemplates&action=edit&id=$templateObj->id"); ?>" method="post">
    <input type="hidden" name="jsontemplates:templateType" value="eventchecklist" />
    <input type="hidden" name="jsontemplates:templateId" value="<?php echo $templateDbEntity->id; ?>" />
    <span class="formField">ID: <?php echo $templateObj->id; ?></span>
    <span class="formField"><label>Nome deste modelo de checklist: <input name="jsontemplates:name" type="text" required="required" size="60" maxlength="140" value="<?php echo hscq($templateObj->name); ?>"/></label></span>
    <br/>
    <?php
        $editChecklistPage->render();
    ?>

    <input type="hidden" id="templateJsonOutput" name="jsontemplates:templateJson" />
    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitChecklistTemplate" name="btnsubmitSubmitChecklistTemplate" value="Salvar alterações" />
    </div>
</form>

<script>
    window.addEventListener("load", function(e)
    {
        document.getElementById("btnsubmitSubmitChecklistTemplate").onclick = function(f)
        {
            if (generateChecklistJson)
                document.getElementById("templateJsonOutput").value = JSON.stringify(generateChecklistJson());
        };
    });
</script>