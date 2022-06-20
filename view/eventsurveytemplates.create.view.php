<?php if (empty($_GET['messages'])): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/eventsurveytemplates.create.post.php", "cont=eventsurveytemplates&action=create"); ?>" method="post">
    <input type="hidden" name="jsontemplates:templateType" value="eventsurvey" />
    <span class="formField"><label>Nome deste modelo de pesquisa de satisfação: <input name="jsontemplates:name" type="text" required="required" size="60" maxlength="140" value="<?php echo hscq($templateObj->name); ?>"/></label></span>
    <br/>
    <?php
        $editSurveyPage->render();
    ?>

    <input type="hidden" id="templateJsonOutput" name="jsontemplates:templateJson" />
    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitSurveyTemplate" name="btnsubmitSubmitSurveyTemplate" value="Salvar alterações" />
    </div>
</form>

<script>
    window.addEventListener("load", function(e)
    {
        document.getElementById("btnsubmitSubmitSurveyTemplate").onclick = function(f)
        {
            if (generateSurveyJson)
                document.getElementById("templateJsonOutput").value = JSON.stringify(generateSurveyJson());
        };
    });
</script>

<?php endif; ?>