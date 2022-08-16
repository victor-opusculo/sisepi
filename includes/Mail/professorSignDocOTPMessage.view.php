<h2 style="font-size: 1.3em;">Olá, <?php echo $professorName; ?>!</h2>
<p>Você solicitou a assinatura de sua documentação de trabalho de docente. Informe a senha temporária abaixo na página de confirmação da assinatura:</p>
<span style="font-weight: bold;
			font-size: 2em;
			display: block;
			padding: 25px;
			margin: 10px;
			background-color: #eeeeee;
			text-align: center;"><?php echo $oneTimePassword; ?></span>
<p>Documentos a assinar:</p>
<ul>
	<?php foreach ($docNamesToSign as $dn): ?>
		<li><?php echo $dn; ?></li>
	<?php endforeach; ?>
</ul>