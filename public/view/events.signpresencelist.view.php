<?php if ($eventDateInfos !== null && $eventInfos !== null)
{ ?>

<div class="viewDataFrame" style="margin-bottom: 30px;">
	<label>Evento: </label><?php echo hsc($eventInfos->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventInfos->typeName); ?> <br/>
	<label>Data do dia: </label><?php echo date_format(date_create($eventDateInfos->date), "d/m/Y"); ?> <br/>
	<label>Horário: </label><?php echo $eventDateInfos->beginTime . ' - ' . $eventDateInfos->endTime; ?> <br/>
	<label>Nome/Conteúdo: </label><?php echo hsc($eventDateInfos->name); ?> <br/>
	<label>Docente: </label><?php echo hsc($eventDateInfos->professorName); ?>
</div>

 <?php
switch ($operation)
{
	case "unnecessary": ?>
	
<div class="centControl"><strong>Esta data do evento não requer lista de presença.</strong></div>
	
<?php	break;
	case "closedList": ?>
	
<div class="centControl"><strong>Desculpe, esta lista está fechada.</strong></div>
	
<?php	break;
	case "showMessages": ?>
	
	<!--Show messages-->
	
<?php	break;
	case "askPassword": ?>
	
	<form action="<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, [ 'eventDateId' => $eventDateInfos->id ] ); ?>" method="post">
		<?php if (isset($_POST["txtListPassword"])): ?>
		<div style="color:red;">
			Senha incorreta!
		</div>
		<?php endif; ?>
		<span class="formField"><label>Senha de acesso: <input type="password" name="txtListPassword" size="20" maxlength="120"/></label></span>
		<div style="margin-top: 20px;">
			<input type="submit" name="btnsubmitSubmitPassword" value="Entrar"/>
		</div>
	</form>
	
<?php	break;
	case "noSubscription": ?>
	
	<form action="<?php echo URL\URLGenerator::generateFileURL("post/signpresencelist.post.php", "cont=events&action=signpresencelist&eventDateId=$eventDateInfos->id"); ?>" method="post">
		<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="60" maxlength="80" required="required"/></label></span>
		<span class="formField"><label>Nome completo: <input type="text" name="txtName" size="60" maxlength="80" required="required"/></label></span>
		<div style="color: red; margin: 10px; font-size: medium;">
			<label>Aviso: Escreva sempre o mesmo e-mail em todas as vezes que assinar a lista de presença deste evento. Assim, será computada corretamente a sua carga horária cumprida.</label>
		</div>
		<div>
			<input type="submit" name="btnsubmitSubmitNoSubscription" value="Enviar" />
			<input type="hidden" name="hiddenListPassword" value="<?php echo $_POST["txtListPassword"]; ?>"/>
			<input type="hidden" name="eventId" value="<?php echo $eventInfos->id; ?>"/>
			<input type="hidden" name="eventDateId" value="<?php echo $eventDateInfos->id; ?>"/>
		</div>
	</form>
	
<?php	break;
	case "common": ?>
	
	<form action="<?php echo URL\URLGenerator::generateFileURL("post/signpresencelist.post.php", "cont=events&action=signpresencelist&eventDateId=$eventDateInfos->id"); ?>" method="post">
		<span class="formField"><label>Selecione seu nome:</label>
			<select name="selName">
				<?php foreach ($subscriptionListNames as $n): ?>
				<option value="<?php echo $n["id"]; ?>"><?php echo $n["name"] . ($n["socialName"] ? " (" . $n["socialName"] . ")" : ""); ?></option>
				<?php endforeach; ?>
			</select>
		</span>
		<?php if (!$isSubscriptionListFull && $eventInfos->allowLateSubscriptions) { ?>
		<div style="font-size: medium; margin: 20px;">
			<a href="<?php echo URL\URLGenerator::generateSystemURL("events", "latesubscription", null, [ 'eventId' => $eventInfos->id, 'backToPresenceListEventId' => $eventDateInfos->id ]); ?>">Seu nome não está na lista? Inscreva-se agora! Ainda temos vagas!</a>
		</div>
		<?php } ?>
		<div>
			<input type="submit" name="btnsubmitSubmitCommon" value="Enviar" />
			<input type="hidden" name="hiddenListPassword" value="<?php echo $_POST["txtListPassword"]; ?>" />
			<input type="hidden" name="eventId" value="<?php echo $eventInfos->id; ?>"/>
			<input type="hidden" name="eventDateId" value="<?php echo $eventDateInfos->id; ?>"/>
		</div>
	</form>
	
<?php	break;
} 
} else { ?>

<!--Show messages-->

<?php } ?>