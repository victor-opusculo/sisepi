<h2 style="font-size: 1.3em;">Olá, <?php echo $vmParentName; ?>!</h2>
<p>Você solicitou a assinatura de termo/documento vinculado ao seu vereador mirim. Informe a senha temporária abaixo na página de confirmação da assinatura:</p>
<span style="font-weight: bold;
			font-size: 2em;
			display: block;
			padding: 25px;
			margin: 10px;
			background-color: #eeeeee;
			text-align: center;"><?php echo $oneTimePassword; ?></span>
<p>Campos a assinar:</p>
<ul>
	<?php foreach ($fieldNamesToSign as $dn): ?>
		<li><?php echo $dn; ?></li>
	<?php endforeach; ?>
</ul>