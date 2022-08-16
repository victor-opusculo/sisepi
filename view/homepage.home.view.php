<style>
	.frames h4, .singleFrame h4 { margin: 10px; text-align: center; }
	
	.frame1, .frame2, .singleFrame
	{ 
		margin: 5px;
		padding: 5px;
		background-color: #efefef;
		border-radius: 15px;
		border: 1px solid lightgray;
	}
	
	@media all and (min-width: 749px)
	{
		.frame1, .frame2 { flex: 50%; }
		.frames { display: flex;}
	}
	
</style>

<h3>Eventos</h3>
<div class="frames">
	<div class="frame1">
		<h4>Próximos eventos</h4>
		<?php $nextEventsWidget->render(); ?>
	</div>
	
	<div class="frame2">
		<h4>Ultimos docentes credenciados</h4>
		<?php $latestProfessorsWidget->render(); ?>
	</div>
</div>

<h3>Checklists</h3>
<p>Checklists com itens pendentes para o seu usuário</p>
<div class="singleFrame">
	<?php $nextChecklistsWidget->render(); ?>
</div>

<h3>Docentes</h3>
<div class="singleFrame">
	<h4>Propostas de trabalho pendentes</h4>
	<?php $pendingWorkProposalsWidget->render(); ?>
</div>

<h3>Biblioteca</h3>
<div class="frames">
	<div class="frame1">
		<h4>Estatísticas</h4>
		<?php $libraryStatisticsWidget->render(); ?>
	</div>
	
	<div class="frame2">
		<h4>Próximas devoluções</h4>
		<?php $libraryNextDevolutionsWidget ->render(); ?>
	</div>
</div>

<!--<div>
	<div class="inputToggleButton">
		<label>
			<input type="checkbox" />
			<span class="inputToggleButtonFace">
				<img width="24" height="24" src="" />
			</span>
		</label>
	</div>
</div>-->