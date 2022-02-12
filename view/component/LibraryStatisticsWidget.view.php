<div class="viewDataFrame">
	<label>Número de itens no acervo: </label><?php echo $widgetObj->collectionCount; ?> <br/>
	<label>Empréstimos feitos: </label><?php echo $widgetObj->totalLoanCount; ?> (<?php echo $widgetObj->nonFinalizedLoanCount; ?> não finalizados) <br/>
	<label>Reservas feitas: </label><?php echo $widgetObj->totalReservationCount; ?> (<?php echo $widgetObj->nonFinalizedReservationCount; ?> pendentes) <br/>
	<label>Usuários cadastrados: </label><?php echo $widgetObj->totalUsersCount; ?> <br/>
</div>