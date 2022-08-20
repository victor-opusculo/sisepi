<h2 style="font-size: 1.3em;">Olá, <?php echo $professorName; ?>!</h2>
<p>Você enviou por nosso sistema o plano de aula "<?= $proposalName ?>" para trabalho de docente. Informamos que ele foi 
<span style="font-weight:bold; color:<?php echo $isApproved ? 'green;' : 'red;'; ?>"><?php echo $isApproved ? 'aprovado' : 'rejeitado'; ?></span>
<?php if (!empty($feedbackMessage)): ?>
	com a seguinte mensagem de retorno: 
	</p>
	<span style="
		font-size: 1em;
		display: block;
		padding: 8px;
		margin: 10px;
		background-color: #eeeeee;
		text-align: justify;"><?php echo $feedbackMessage; ?></span>
<?php else: ?>
	sem mensagem de retorno.
	</p>
<?php endif; ?>
<p>Em caso de dúvidas, entre em contato conosco pelo e-mail <a href="mailto:<?= $configs['replyto'] ?>"><?= $configs['replyto'] ?></a></p>