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
        <span class="formField"><label>Tema: <input type="text" size="40" name="professorworkproposals:txtName" required="required" value="<?php echo hscq($proposalObj->name); ?>"/></label></span>
        
        <span class="formField"><label>Objetivos: <input type="text" size="40" name="professorworkproposals:txtInfoObjective" maxlength="255" value="<?php echo hscq($proposalObj->infosFields->objectives ?? ''); ?>"/></label></span>
        <span class="formField"><label>Conteúdo: <input type="text" size="40" name="professorworkproposals:txtInfoContents" maxlength="255" value="<?php echo hscq($proposalObj->infosFields->contents ?? ''); ?>"/></label></span>
        <span class="formField"><label>Procedimentos: <input type="text" size="40" name="professorworkproposals:txtInfoProcedures" maxlength="255" value="<?php echo hscq($proposalObj->infosFields->procedures ?? ''); ?>"/></label></span>
        <span class="formField"><label>Recursos: <input type="text" size="40" name="professorworkproposals:txtInfoResources" maxlength="255" value="<?php echo hscq($proposalObj->infosFields->resources ?? ''); ?>"/></label></span>
        <span class="formField"><label>Avaliação: <input type="text" size="40" name="professorworkproposals:txtInfoEvaluation" maxlength="255" value="<?php echo hscq($proposalObj->infosFields->evaluation ?? ''); ?>"/></label></span>
        
        <span class="formField">
            <label>Outras informações: <br/>
                <textarea rows="4" maxlength="600" style="width: 100%;" name="professorworkproposals:txtDescription"><?php echo hsc($proposalObj->moreInfos); ?></textarea>
            </label>
        </span>
        <input type="hidden" name="professorworkproposals:fileExtension" value="<?php echo hscq($proposalObj->fileExtension); ?>" />
        <span class="formField">
            <label>Upload de novo arquivo (opcional): <input type="file" id="fileProposalFile" name="fileProposalFile" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> (Tamanho máximo de 5MB. Formatos suportados: PDF, DOC, DOCX, ODT, PPT e PPTX)</label>
            <label><input type="checkbox" name="professorworkproposals:chkDelCurrentProposalFile" value="1"/> Excluir arquivo atual</label>
        </span>

        <input type="hidden" name="professorworkproposals:profWorkProposalId" value="<?php echo $proposalObj->id; ?>" />
        <div class="centControl">
            <input type="submit" name="btnsubmitSubmitWorkProposal" value="Alterar dados" />
        </div>
    </form>

<?php endif; ?>