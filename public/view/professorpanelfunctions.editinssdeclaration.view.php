<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<form method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professorpanelfunctions.editinssdeclaration.post.php', 'cont=' . get_class($this) . '&action=' . $this->action . '&workSheetId=' . $workSheetObj->id); ?>">
    <p>
        Informe abaixo as empresas pelas quais será realizado o desconto de sua contribuição previdenciária no seguinte período:
    </p>
    <?php 
    $inssPeriodBegin = $workSheetObj->paymentInfosJson->inssPeriodBegin ?? null;
    $inssPeriodEnd = $workSheetObj->paymentInfosJson->inssPeriodEnd ?? null;
    ?>
    <label>Período: </label><?php echo $inssPeriodBegin ? date_create($inssPeriodBegin)->format('d/m/Y') : '***'; ?>
    <label> a </label><?php echo $inssPeriodEnd ? date_create($inssPeriodEnd)->format('d/m/Y') : '***'; ?>

    <table>
        <thead>
            <tr>
                <th>Empresa</th><th>CNPJ</th><th>Remuneração</th><th>INSS retido</th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            <?php $icCount = !empty($workSheetObj->paymentInfosJson->companies) ? count($workSheetObj->paymentInfosJson->companies) : 4;
            for ($i = 0; $i < $icCount; $i++): 
                $icName = $workSheetObj->paymentInfosJson->companies[$i]->name ?? '';
                $icCnpj = $workSheetObj->paymentInfosJson->companies[$i]->cnpj ?? '';
                $icWage = $workSheetObj->paymentInfosJson->companies[$i]->wage ?? '';
                $icCollectedInss = $workSheetObj->paymentInfosJson->companies[$i]->collectedInss ?? '';
                ?>
                <tr>
                    <td><input type="text" name="inssCompanies[<?php echo $i; ?>][name]" maxlength="120" size="30" value="<?php echo hscq($icName); ?>"/></td>
                    <td><input type="text" name="inssCompanies[<?php echo $i; ?>][cnpj]" maxlength="120" size="15" value="<?php echo hscq($icCnpj); ?>"/></td>
                    <td><input type="number" name="inssCompanies[<?php echo $i; ?>][wage]" min="0" step="any" maxlength="120" style="width:150px;" value="<?php echo hscq($icWage); ?>"/></td>
                    <td><input type="number" name="inssCompanies[<?php echo $i; ?>][collectedInss]" step="any" maxlength="120" style="width:150px;" value="<?php echo hscq($icCollectedInss); ?>"/></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    <br/>
    <input type="hidden" name="workSheetId" value="<?php echo $workSheetObj->id; ?>" />
    <input type="submit" name="btnsubmitSubmitInssCompanies" value="Salvar alterações" />
</form>