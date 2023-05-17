<form action="<?php echo URL\URLGenerator::generateFileURL("post/eventtesttemplates.edit.post.php", "cont=eventtesttemplates&action=edit&id=$templateDbEntity->id"); ?>" method="post">


    <span class="formField">ID: <?php echo $templateDbEntity->id; ?></span>
    <span class="formField"><label>Nome deste modelo de avaliação: <input name="eventtesttemplate:txtTestName" type="text" required="required" size="60" maxlength="140" value="<?php echo hscq($templateDbEntity->name); ?>"/></label></span>
    <ht/>
    <br/>
    <?php
        $editTestPage->render();
    ?>

    <input type="hidden" id="templateJsonOutput" name="eventtesttemplate:hidTestDataJson" />
    <div class="centControl">
        <input type="hidden" name="eventtesttemplate:hidTestTemplateId" value="<?php echo $templateDbEntity->id; ?>" />
        <input type="submit" id="btnsubmitSubmitTestTemplate" name="btnsubmitSubmitTestTemplate" value="Salvar alterações" />
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