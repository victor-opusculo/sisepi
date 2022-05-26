<script src="<?php echo URL\URLGenerator::generateFileURL("view/settings.editeventlocations.view.js"); ?>"></script>
<style>
    input[type='text'], select {  width: 90%;}
    .btnRemoveLocation { min-width: 20px; }
</style>
<div id="pageElementsTemplates" style="display: none;">
    <table>
        <tr id="trNewLocation">
            <td>
                <input type="text" required="required" class="txtLocationName" value="" />
            </td>
            <td>
                <select class="selLocationType">
                    <?php foreach ($locationTypes as $val => $label): ?>
                        <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <label>Cor de fundo: <input type="color" class="colorStyleBgColor" value="#90ee90" /></label><br/>
                <label>Cor do texto: <input type="color" class="colorStyleTextColor" value="#22B14C" /></label><br/>
            </td>
            <td>
                <button type="button" class="btnRemoveLocation">&times;</button>
            </td>
        </tr>
    </table>
</div>

<?php
function writeSelectedStatus($property, $valueToCheckFor)
{
    if (empty($property)) return '';
    return (string)$property === (string)$valueToCheckFor ? ' selected="selected" ' : '';
}
?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/settings.editeventlocations.post.php", "cont=settings&action=editeventlocations"); ?>" method="post">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo/modalidade</th>
                <th>Estilo usado na agenda</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbodyLocationRows">
            <?php foreach ($locationsDataRows as $loc): ?>
                <tr data-id="<?php echo $loc->id; ?>">
                    <td>
                        <input type="text" required="required" class="txtLocationName" value="<?php echo $loc->name; ?>" />
                    </td>
                    <td>
                        <select class="selLocationType">
                            <?php foreach ($locationTypes as $val => $label): ?>
                                <option value="<?php echo $val; ?>" <?php echo writeSelectedStatus($loc->type, $val); ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <label>Cor de fundo: <input type="color" class="colorStyleBgColor" value="<?php echo !empty($loc->calendarInfoBoxStyleJson) ? $loc->calendarInfoBoxStyleJson->backgroundColor : ''; ?>" /></label><br/>
                        <label>Cor do texto: <input type="color" class="colorStyleTextColor" value="<?php echo !empty($loc->calendarInfoBoxStyleJson) ? $loc->calendarInfoBoxStyleJson->textColor : ''; ?>" /></label><br/>
                    </td>
                    <td>
                        <button type="button" class="btnRemoveLocation">&times;</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="btnAddNewLocation" type="button">Adicionar</button>
    <input type="hidden" id="locationChangesReport" name="extra:locationChangesReport" value="" />
    <div class="centControl">
        <input type="submit" id="btnsubmitSubmitLocations" name="btnsubmitSubmitLocations" value="Salvar alterações" />
    </div>
</form>