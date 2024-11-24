<form action="<?php echo URL\URLGenerator::generateFileURL("post/mailing.post.php", "cont=mailing"); ?>" method="post" >
	<h4>Cadastro de e-mail</h4>
	<p>Você aceita receber informações dos eventos? Cadastre o seu e-mail aqui!</p>
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="50" maxlength="80" required="required"/></label></span>
	<span class="formField"><label>Nome: <input type="text" name="txtName" size="60" maxlength="80" required="required"/></label></span>
	<span class="formField"><input type="submit" name="btnsubmitRegister" value="Cadastrar" /></span>
</form>
<br/>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/mailing.post.php", "cont=mailing"); ?>" method="post" >
	<h4>Remover cadastro</h4>
	<p>Se você deseja remover seu e-mail do nosso mailing e não receber mais informações dos nossos eventos, faça-o aqui:</p>
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="50" maxlength="80" required="required"/></label></span>
	<span class="formField"><input type="submit" name="btnsubmitDelete" value="Remover" /></span>
</form>