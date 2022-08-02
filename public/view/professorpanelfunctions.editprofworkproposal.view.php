<?php if (isset($proposalObj) && $proposalObj->isApproved != 1): ?>

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
    <form method="post" enctype="multipart/form-data" onsubmit="return validateFile();"
    action="<?php echo URL\URLGenerator::generateFileURL('post/professorpanelfunctions.editprofworkproposal.post.php', 'cont=professorpanelfunctions&action=editprofworkproposal&id=' . $proposalObj->id); ?>">
        <span class="formField"><label>Nome: <input type="text" size="40" name="professorworkproposals:txtName" required="required" value="<?php echo hscq($proposalObj->name); ?>"/></label></span>
        <span class="formField">
            <label>Descrição (opcional): <br/>
                <textarea rows="4" maxlength="600" style="width: 100%;" name="professorworkproposals:txtDescription"><?php echo hsc($proposalObj->description); ?></textarea>
            </label>
        </span>
        <input type="hidden" name="professorworkproposals:fileExtension" value="<?php echo hscq($proposalObj->fileExtension); ?>" />
        <span class="formField">
            <label>Upload de novo arquivo (opcional): <input type="file" id="fileProposalFile" name="fileProposalFile" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> (Tamanho máximo de 5MB. Formatos suportados: PDF, DOC, DOCX, ODT, PPT e PPTX)</label>
        </span>

        <input type="hidden" name="professorworkproposals:profWorkProposalId" value="<?php echo $proposalObj->id; ?>" />
        <div class="centControl">
            <input type="submit" name="btnsubmitSubmitWorkProposal" value="Alterar dados" />
        </div>
    </form>

<?php endif; ?>