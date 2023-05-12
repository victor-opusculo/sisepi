
<?php
    require_once "controller/component/ExpandablePanel.class.php";
    use SisEpi\Pub\Controller\Component\ExpandablePanel;
?>
<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<?php if (empty($_GET['messages'])): ?>

<script>
    function validateFile()
    {
        var errorMessages = [];
        var input = document.getElementById("fileProposalFile");
        var file = input.files[0];

        if (file)
            if (file.size > input.getAttribute("data-maxsize"))
                errorMessages.push("O arquivo do plano excede o tamanho de " + (input.getAttribute("data-maxsize") / Math.pow(1024, 2)) + " MB!");

        for (let err of errorMessages)
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err);

        return errorMessages.length === 0;
    }
</script>

<form id="frmUploadProposal" enctype="multipart/form-data" method="post" onsubmit="return validateFile();" action="<?php echo URL\URLGenerator::generateFileURL('post/professorpanelfunctions.newprofworkproposal.post.php', 'cont=professorpanelfunctions&action=newprofworkproposal'); ?>">
    <span class="formField">
        <label>Tema deste plano: <input type="text" required="required" name="professorworkproposals:txtName" size="40" maxlength="255" tabindex="1"/></label>
    </span>

    <span class="formField"><label>Objetivos: <input type="text" size="40" name="professorworkproposals:txtInfoObjective" maxlength="255" tabindex="2"/></label></span>
    <span class="formField"><label>Conteúdo: <input type="text" size="40" name="professorworkproposals:txtInfoContents" maxlength="255" tabindex="3"/></label></span>
    <span class="formField"><label>Procedimentos: <input type="text" size="40" name="professorworkproposals:txtInfoProcedures" maxlength="255" tabindex="4"/></label></span>
    <span class="formField"><label>Recursos: <input type="text" size="40" name="professorworkproposals:txtInfoResources" maxlength="255" tabindex="5"/></label></span>
    <span class="formField"><label>Avaliação: <input type="text" size="40" name="professorworkproposals:txtInfoEvaluation" maxlength="255" tabindex="6"/></label></span>

    <span class="formField">
        <label>Outras informações: </label><br/>
        <textarea style="width: 100%;" name="professorworkproposals:txtDescription" rows="4" maxlength="600" tabindex="7"></textarea>
    </span>
    <span class="formField">
        <label>Arquivo para upload (opcional): <input name="fileProposalFile" id="fileProposalFile" type="file" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> (Tamanho máximo de 5MB. Formatos suportados: PDF, DOC, DOCX, ODT, PPT e PPTX)</label>
    </span>

    <h4>Objetivos de Desenvolvimento Sustentável (ODS)</h4>
    <p>Marque abaixo as metas ODS para as quais o seu trabalho proposto contribui</p>
    <div>
        <?php
        $tabIndex = 8;
        ExpandablePanel::writeCssRules();
        foreach ($odsData as $ods)
        {
            $childs = array_fill(0, count($ods->goals), null);
            foreach ($ods->goals as $i => $goal)
            {
                $childs[$i] = 
                "
                    <label><input type=\"checkbox\" name=\"professorodsproposals:goalsCodes[]\" value=\"{$ods->number}.{$goal->id}\"/><span style=\"font-weight:bold;\">{$ods->number}.{$goal->id}</span> - " . hsc($goal->description) ."</label><br/>
                ";
            }

            (new ExpandablePanel(['caption' => "{$ods->number}. {$ods->description}", 'children' => $childs, 'tabIndex' => $tabIndex++ ]))->render();
        }
        ?>
    </div>

    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitNewWorkProposal" name="btnsubmitSubmitNewWorkProposal" value="Enviar plano de aula" />
    </div>
</form>

<?php endif; ?>