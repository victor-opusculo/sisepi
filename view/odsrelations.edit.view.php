<?php if (isset($odsData, $odsRelation)): ?>
    <?php 
        require_once "view/fragment/checklistLikeListStyle.css.php";
        function writeCheckedGoalStatus($relation, $number, $id, $preChecked)
        {
            return (!empty($relation->codesArray) && in_array("{$number}.{$id}", $relation->codesArray))
             || 
             (!empty($preChecked) && in_array("{$number}.{$id}", $preChecked)) 
             ? 
             ' checked ' 
             : 
             '';
        }  
    ?>
    <form id="frmCreateEditOdsRelation" method="post" action="<?= URL\URLGenerator::generateFileURL("post/odsrelations.{$mode}.post.php", [ 'cont' => $_GET['cont'], 'action' => $mode == 'create' ? 'home' : 'edit', 'id' => $odsRelation->id ?? '' ]) ?>">
        
        <fieldset>
            <legend>Evento vinculado</legend>
            <span style="display: flex; align-items: center;">
                <label>Evento ID: 
                    <input type="number" id="numEventId" name="odsrelations:numEventId" min="1" step="1" value="<?= hscq($odsRelation->eventId ?? $_GET['eventId'] ?? '') ?>" />
                </label>
                <button type="button" id="btnLoadEvent" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
                <button type="button" id="btnSearchEvent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
            </span>
            <div class="viewDataFrame">
                <label>Nome: </label><span id="lblEventName"></span>
            </div>
        </fieldset>
        <fieldset>
            <legend>Relação ODS</legend>
            <span class="formField">
                <label>Nome: <input type="text" maxlength="254" name="odsrelations:txtName" id="txtRelationName" size="40" required value="<?= hscq($odsRelation->name ?? '') ?>"/></label> (normalmente o nome do evento)
            </span>
            <span class="formField">
                <label>Exercício: <input type="number" name="odsrelations:numYear" min="2000" step="1" value="<?= $odsRelation->year ?? date('Y') ?>" required /></label>
            </span>
            <span class="formField">
                <div class="listMainBlock">
                    <h3>Metas ODS</h3>
                    <?php foreach ($odsData as $ods): ?>
                        <div class="listItem">
                            <h4 class="itemTitle"><?= hsc($ods->description) ?></h4>
                            <?php foreach ($ods->goals as $goal): ?>
                                <span class="formField">
                                    <label>
                                        <input type="checkbox" class="odsGoalItemCheckbox" data-code="<?= "{$ods->number}.{$goal->id}" ?>" <?= writeCheckedGoalStatus($odsRelation, $ods->number, $goal->id, $preChecked) ?>/>
                                        <?= "<strong>{$ods->number}.{$goal->id}</strong> - " . hsc($goal->description) ?>
                                    </label>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </span>
        </fieldset>
        <div class="centControl">
            <input type="hidden" name="odsrelations:odsRelationId" value="<?= $odsRelation->id ?? '' ?>" />
            <input type="hidden" name="odsrelations:hidOdsCodes" id="hidOdsCodes" value="" />
            <input type="submit" name="btnsubmitSubmitOdsRelation" value="<?= $mode === 'create' ? 'Criar' : 'Editar' ?> relação" />
        </div>
    </form>
    <script src="<?= URL\URLGenerator::generateFileURL('view/fragment/eventByIdLoader.js') ?>"></script>
    <script>
        setUpEventByIdLoader
        ({
            setData: data => document.getElementById('lblEventName').innerText = document.getElementById('txtRelationName').value = data.name,
            setId: id => document.getElementById('numEventId').value = id,
            getId: () => document.getElementById('numEventId').value,
            buttonLoad: document.getElementById('btnLoadEvent'),
            buttonSearch: document.getElementById('btnSearchEvent')
        });

        window.addEventListener('load', function()
        {
            document.getElementById('frmCreateEditOdsRelation').onsubmit = function(e)
            {
                const codes = [];
                document.querySelectorAll('.odsGoalItemCheckbox').forEach( checkbox =>
                {
                    if (checkbox.checked) codes.push(checkbox.getAttribute('data-code'));
                });

                if (codes.length < 1)
                {
                    e.preventDefault();
                    showBottomScreenMessageBox(BottomScreenMessageBoxType.error, "Selecione pelo menos uma meta ODS para criar a relação.");
                    return;
                }

                document.getElementById('hidOdsCodes').value = JSON.stringify(codes);
            };
        });
    </script>
<?php endif; ?>