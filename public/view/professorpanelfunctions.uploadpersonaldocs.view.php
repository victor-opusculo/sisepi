<?php
include 'view/fragment/professors.logoutlink.view.php';

function writeSelectedStatus($property, $valueToLookFor)
{
    return (string)$property === (string)$valueToLookFor ? ' selected="selected" ' : '';
}
?>

<script src="<?php echo URL\URLGenerator::generateFileURL('view/professorpanelfunctions.uploadpersonaldocs.view.js'); ?>"></script>
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

<form id="frmUploadDocs" enctype="multipart/form-data" method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professorpanelfunctions.uploadpersonaldocs.post.php', 'cont=professorpanelfunctions&action=uploadpersonaldocs'); ?>">

    <div class="messageFrameWithIcon">
        <img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>
        Para o pagamento, você deve nos enviar as imagens (fotocópia digitalizada) dos seguintes documentos:
        <ol>
            <li>Documento de identidade (RG ou CNH);</li>
            <li>Comprovante de endereço de residência;</li>
            <li>Comprovante de situação acadêmica (Diploma/Certificado/Documento comprovando seu nível de formação).</li>
        </ol>
        Número máximo de 10 arquivos. O limite de tamanho para cada arquivo é de 1,8MB. Os formatos suportados são JPG/JPEG, PNG e PDF.
    </div>

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
                    <td><a href="<?php echo URL\URLGenerator::generateFileURL('generate/viewProfessorPersonalDocFile.php', [ 'file' => $att->fileName ]); ?>" class="previousUploadFileName"><?php echo $att->fileName; ?></a></td>
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
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitProfessorDocs" id="btnsubmitSubmitProfessorDocs" value="Salvar alterações">
    </div>
</form>