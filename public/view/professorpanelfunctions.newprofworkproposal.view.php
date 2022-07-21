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
                errorMessages.push("O arquivo da proposta excede o tamanho de " + (input.getAttribute("data-maxsize") / Math.pow(1024, 2)) + " MB!");

        for (let err of errorMessages)
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err);

        return errorMessages.length === 0;
    }
</script>

<form id="frmUploadProposal" enctype="multipart/form-data" method="post" onsubmit="return validateFile();" action="<?php echo URL\URLGenerator::generateFileURL('post/professorpanelfunctions.newprofworkproposal.post.php', 'cont=professorpanelfunctions&action=newprofworkproposal'); ?>">
    <span class="formField">
        <label>Nome desta proposta: <input type="text" required="required" name="professorworkproposals:txtName" size="40" maxlength="255" /></label>
    </span>
    <span class="formField">
        <label>Descrição breve (opcional): </label><br/>
        <textarea style="width: 100%;" name="professorworkproposals:txtDescription" rows="4" maxlength="600"></textarea>
    </span>
    <span class="formField">
        <label>Arquivo para upload: <input name="fileProposalFile" id="fileProposalFile" type="file" required="required" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> (Tamanho máximo de 5MB. Formatos suportados: PDF, DOC, DOCX, ODT, PPT e PPTX)</label>
    </span>
    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitNewWorkProposal" name="btnsubmitSubmitNewWorkProposal" value="Enviar" />
    </div>
</form>

<?php endif; ?>