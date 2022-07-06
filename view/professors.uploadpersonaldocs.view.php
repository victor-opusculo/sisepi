<?php
function writeSelectedStatus($property, $valueToLookFor)
{
    return (string)$property === (string)$valueToLookFor ? ' selected="selected" ' : '';
}
?>

<script src="<?php echo URL\URLGenerator::generateFileURL('view/professors.uploadpersonaldocs.view.js'); ?>"></script>
<div id="elementsTemplates" style="display:none;">
    <table>
        <tr id="trNewDocUpload">
            <td><input type="file" class="fileUploadDoc" required="required" data-maxsize="1887436.8" accept="image/png, image/jpeg, application/pdf"></td>
            <td>
                <select class="selDocType">
                    <?php foreach ($docTypes as $typeIdent => $typeName): ?>
                        <option value="<?php echo $typeIdent; ?>"><?php echo $typeName; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><button type="button" class="btnDelDoc" style="min-width: 20px;">&times;</button></td>
        </tr>
    </table>
</div>

<div class="viewDataFrame">
    <label>Docente: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("professors", "view", $professorObj->id); ?>"><?php echo $professorObj->name; ?></a> <br/>
    <label>E-mail: </label><?php echo $professorObj->email; ?>
</div>

<form id="frmUploadDocs" enctype="multipart/form-data" method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professors.uploadpersonaldocs.post.php', 'cont=professors&action=uploadpersonaldocs'); ?>">
    <button type="button" id="btnAddDoc" style="line-height: 0" <?php echo count($professorDocsAttachments) >= 10 ? ' disabled="disabled" ' : '';?> >Novo &#10010;</button>
    <table>
        <thead>
            <tr>
                <th></th><th class="shrinkCell"></th><th class="shrinkCell"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($professorDocsAttachments as $att): ?>
                <tr data-id="<?php echo $att->id; ?>">
                    <td><a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorPersonalDocFile.php', [ 'professorId' => $professorObj->id, 'file' => $att->fileName ]); ?>" class="previousUploadFileName"><?php echo $att->fileName; ?></a></td>
                    <td>
                        <select class="selDocType">
                            <?php foreach ($docTypes as $typeIdent => $typeName): ?>
                                <option value="<?php echo $typeIdent; ?>" <?php echo writeSelectedStatus($att->docType, $typeIdent); ?>><?php echo $typeName; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><button type="button" class="btnDelDoc" style="min-width: 20px;">&times;</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br/>
    <input type="hidden" id="hidUploadDocsChangesReport" name="hidUploadDocsChangesReport" />
    <input type="hidden" name="professorId" value="<?php echo $professorObj->id; ?>" />
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitProfessorDocs" id="btnsubmitSubmitProfessorDocs" value="Salvar alterações">
    </div>
</form>