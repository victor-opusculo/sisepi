<?php if($profObject !== null) 
{
?>

<div class="viewDataFrame">
	<label>ID: </label><?php echo $profObject->id; ?> <br/>
	<label>Nome: </label><?php echo hsc($profObject->name); ?> <br/>
	<label>E-mail: </label><?php echo hsc($profObject->email); ?> <br/>
	<label>Telefone: </label><?php echo hsc($profObject->telephone); ?> <br/>
	<label>Escolaridade: </label><?php echo hsc($profObject->schoolingLevel); ?> <br/>
	<label>Temas de interesse: </label><?php echo hsc($profObject->topicsOfInterest); ?> <br/>
	<label>Plataforma Lattes: </label><?php echo hsc($profObject->lattesLink); ?> <br/>
	<label>Termo de consentimento para tratamento de dados pessoais: </label><?php echo hsc($profObject->consentForm); ?> <br/>
	<label>Concorda com o termo? </label><?php echo ($profObject->agreesWithConsentForm) ? "Concorda" : "Não concorda"; ?><br/>
	<label>Data de registro: </label><?php echo date_format(date_create($profObject->registrationDate), "d/m/Y H:i:s"); ?>
	
	<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'edit', $profObject->id); ?>">Editar</a></li>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL('professors', 'delete', $profObject->id); ?>">Excluir</a></li>
		</ul>
	</div>
</div>

<?php } 
else
{
	echo "Registro não localizado";
}
?>