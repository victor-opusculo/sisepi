<?php if (isset($proposalObj)): ?>

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
    action="<?php echo URL\URLGenerator::generateFileURL('post/professors2.editworkproposal.post.php', 'cont=professors2&action=editworkproposal&id' . $proposalObj->id); ?>">
        <span class="formField"><label>Nome: <input type="text" size="40" name="professorworkproposals:txtName" required="required" value="<?php echo hscq($proposalObj->name); ?>"/></label></span>
        <span class="formField"><label>Descrição (opcional): <textarea rows="4" maxlength="600" name="professorworkproposals:txtDescription"><?php echo hsc($proposalObj->description); ?></textarea></label></span>
        <span class="formField">
            <label>Docente dono:
                <select name="professorworkproposals:selOwnerProfessorId" required="required">
                    <?php foreach ($professorList as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo $p['id'] == $proposalObj->ownerProfessorId ? ' selected ' : ''; ?>><?php echo hsc($p['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </span>
        <span class="formField">
            <label>Status: </label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="" <?php echo is_null($proposalObj->isApproved) ? 'checked' : ''; ?> required="required"/> Pendente</label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="1" <?php echo !is_null($proposalObj->isApproved) && $proposalObj->isApproved ? 'checked' : ''; ?> required="required"/> Aprovado</label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="0" <?php echo !is_null($proposalObj->isApproved) && !$proposalObj->isApproved ? 'checked' : ''; ?> required="required"/> Rejeitado</label>
        </span>
        <input type="hidden" name="professorworkproposals:fileExtension" value="<?php echo hscq($proposalObj->fileExtension); ?>" />
        <span class="formField">
            <label>Upload de novo arquivo (opcional): <input type="file" id="fileProposalFile" name="fileProposalFile" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> </label>
        </span>

        <input type="hidden" name="professorworkproposals:profWorkProposalId" value="<?php echo $proposalObj->id; ?>" />
        <div class="centControl">
            <input type="submit" name="btnsubmitSubmitWorkProposal" value="Alterar dados" />
        </div>
    </form>

<?php endif; ?>