<?php if (empty($_GET["messages"])): ?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/eventchecklisttemplates.create.post.php", "cont=eventchecklisttemplates&action=create"); ?>" method="post">
    <input type="hidden" name="jsontemplates:templateType" value="eventchecklist" />
    <span class="formField"><label>Nome deste modelo de checklist: <input name="jsontemplates:name" required="required" type="text" size="60" maxlength="140" value="<?php echo hscq($templateObj->name); ?>"/></label></span>
    <br/>
    <?php
        $editChecklistPage->render();
    ?>

    <input type="hidden" id="templateJsonOutput" name="jsontemplates:templateJson" />
    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitChecklistTemplate" name="btnsubmitSubmitChecklistTemplate" value="Criar" />
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
<?php endif; ?>