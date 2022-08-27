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
    <form method="post" enctype="multipart/form-data" onsubmit="return validateFile();"
    action="<?php echo URL\URLGenerator::generateFileURL('post/professors2.newworkproposal.post.php', [ 'title' => $this->subtitle ] ); ?>">
        <span class="formField"><label>Tema: <input type="text" size="40" name="professorworkproposals:txtName" required="required" /></label></span>
        
        <span class="formField"><label>Objetivos: <input type="text" size="40" name="professorworkproposals:txtInfoObjective" maxlength="255"/></label></span>
        <span class="formField"><label>Conteúdo: <input type="text" size="40" name="professorworkproposals:txtInfoContents" maxlength="255"/></label></span>
        <span class="formField"><label>Procedimentos: <input type="text" size="40" name="professorworkproposals:txtInfoProcedures" maxlength="255"/></label></span>
        <span class="formField"><label>Recursos: <input type="text" size="40" name="professorworkproposals:txtInfoResources" maxlength="255"/></label></span>
        <span class="formField"><label>Avaliação: <input type="text" size="40" name="professorworkproposals:txtInfoEvaluation" maxlength="255"/></label></span>
        
        <span class="formField"><label>Outras informações: <textarea rows="4" maxlength="600" name="professorworkproposals:txtMoreInfos"></textarea></label></span>
        <span class="formField">
            <label>Docente dono:
                <select name="professorworkproposals:selOwnerProfessorId" required="required">
                    <?php foreach ($professorList as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo hsc($p['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </span>
        <span class="formField">
            <label>Status: </label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="" checked="checked" required="required"/> Pendente</label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="1" required="required"/> Aprovado</label>
            <label><input type="radio" name="professorworkproposals:radioApproved" value="0" required="required"/> Rejeitado</label>
        </span>

        <span class="formField">
            <label>Upload do arquivo (opcional): <input type="file" id="fileProposalFile" name="fileProposalFile" data-maxsize="5242880" 
        accept="<?php echo $fileAllowedMimeTypes; ?>"/> </label>
        </span>

        <div class="centControl">
            <input type="submit" name="btnsubmitSubmitWorkProposal" value="Criar" />
        </div>
    </form>

<?php endif; ?>