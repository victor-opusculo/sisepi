<?php 

if (!empty($_GET["messages"])) {?>

<?php }
else
{

if($isValidEventId && $eventObj->subscriptionListNeeded)
{	
	if ($passedClosureDate) { ?>
		<div class="centControl">Desculpe, as inscrições para este evento foram encerradas.</div>
<?php } else if ($isSubscriptionYetToOpen) { ?>
		<div class="centControl">Esta lista de inscrição ainda não foi aberta. Abertura prevista para <?php echo dateInFullString($eventObj->subscriptionListOpeningDate); ?>.</div>
<?php } else if ($isListFull) { ?>
		<div class="centControl">Desculpe, todas as vagas deste evento foram preenchidas.</div>
<?php } else { ?>

<?php if($eventObj->posterImageAttachmentFileName): ?>
<div class="centControl responsiveImageFrame">
	<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/$eventObj->posterImageAttachmentFileName"); ?>" alt="<?php echo hscq($eventObj->name); ?>" style="border-radius: 10px;"/> 
</div>
<?php endif; ?>

<script>
	const getLastSubscriptionScriptURL = '<?php echo URL\URLGenerator::generateFileURL("generate/getLastSubscriptionData.php", "email={email}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/events.subscribe.view.js"); ?>"></script>

<div class="viewDataFrame">
	<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
	<label>Vagas: </label><?php echo $eventObj->maxSubscriptionNumber; ?>
</div>

<?php 
$showSocialMediaLinks = count(array_filter($socialMediaLinks, fn($link) => $link === '')) < count($socialMediaLinks);
if ($showSocialMediaLinks): ?>
<div id="socialMediaLinks" class="centControl">
	<style>
		#socialMediaLinks .socialMediaButton
		{
			display: inline-block;
			margin: 0.5em;
		}

		#socialMediaLinks .socialMediaButton img { max-width: 3em; }
	</style>
	<h3>Curta nossas redes!</h3>
	<?php if (!empty($socialMediaLinks['SOCIAL_MEDIA_URL_FACEBOOK'])): ?>
		<div class="socialMediaButton">
			<a target="_blank" href="<?php echo $socialMediaLinks['SOCIAL_MEDIA_URL_FACEBOOK']; ?>"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/facebook.png"); ?>" title="Facebook" alt="Facebook" /></a>
		</div>
	<?php endif; ?>
	<?php if (!empty($socialMediaLinks['SOCIAL_MEDIA_URL_TWITTER'])): ?>
		<div class="socialMediaButton">
			<a target="_blank" href="<?php echo $socialMediaLinks['SOCIAL_MEDIA_URL_TWITTER']; ?>"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/twitter.png"); ?>" title="Twitter" alt="Twitter" /></a>
		</div>
	<?php endif; ?>
	<?php if (!empty($socialMediaLinks['SOCIAL_MEDIA_URL_INSTAGRAM'])): ?>
		<div class="socialMediaButton">
			<a target="_blank" href="<?php echo $socialMediaLinks['SOCIAL_MEDIA_URL_INSTAGRAM']; ?>"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/instagram.png"); ?>" title="Instagram" alt="Instagram" /></a>
		</div>
	<?php endif; ?>
	<?php if (!empty($socialMediaLinks['SOCIAL_MEDIA_URL_YOUTUBE'])): ?>
		<div class="socialMediaButton">
			<a target="_blank" href="<?php echo $socialMediaLinks['SOCIAL_MEDIA_URL_YOUTUBE']; ?>"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/youtube.png"); ?>" title="Youtube" alt="Youtube" /></a>
		</div>
	<?php endif; ?>
	<?php if (!empty($socialMediaLinks['SOCIAL_MEDIA_URL_LINKEDIN'])): ?>
		<div class="socialMediaButton">
			<a target="_blank" href="<?php echo $socialMediaLinks['SOCIAL_MEDIA_URL_LINKEDIN']; ?>"><img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/linkedin.png"); ?>" title="LinkedIn" alt="LinkedIn" /></a>
		</div>
	<?php endif; ?>
	<div>
		<button type="button" onclick="document.getElementById('frmSubs').style.display = 'block';document.getElementById('socialMediaLinks').style.display = 'none';">Iniciar inscrição</button>
	</div>
</div>
<?php endif; ?>

<form id="frmSubs" action="<?php echo URL\URLGenerator::generateFileURL("post/subscribe.post.php", "cont=events&action=subscribe&eventId=$eventObj->id"); ?>" method="post" style="margin-top: 30px; <?php echo $showSocialMediaLinks ? 'display: none;' : ''; ?>">
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
	<span class="formField"><label>E-mail: <input id="txtEmail" type="email" name="txtEmail" size="60" maxLength="80" required="required"/></label></span>
	
	<?php 
		require_once __DIR__ . '/../includes/GenerateView/EventSubscription.php';

		$injectHtmlCallbacks =
		[
			'preTerms' =>
			[
				function()
				{
					echo '<span class="formField">Aceita receber por e-mail informações dos eventos da Escola do Parlamento "Doutor Osmar de Souza"? <br/><label><input type="checkbox" name="chkSubscribeMailing" value="1"/>Aceito</label></span>';
				}
			],
			'preQuestions' =>
			[
				function()
				{
					echo '<span class="formField"><label>Nome completo: <input type="text" id="txtName" name="txtName" size="60" maxLength="80" required="required"/></label></span>';
				}
			]
		];

		\GenerateView\EventSubscription\writeSubscriptionFormFieldsHTML($subscriptionTemplate, $injectHtmlCallbacks, false, $connection);
		$connection->close();
	?>
	
	<br/>
	<div class="centControl">
		<input type="submit" id="btnsubmitSubmitSubscription" name="btnsubmitSubmitSubscription" value="Enviar"/>
	</div>
</form>

<?php } 
}  ?>
<?php 
} ?>