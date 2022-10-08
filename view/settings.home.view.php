<script src="<?php echo URL\URLGenerator::generateFileURL("view/settings.home.view.js"); ?>"></script>

<div>
	<span>Ir para: </span>
	<a href="#hGeneralSettings">Geral</a>
	<a href="#hUsers">Usuários</a> 
	<a href="#hEnums">Enumeradores</a> 
	<a href="<?php echo URL\URLGenerator::generateSystemURL("settings", "editeventlocations"); ?>">Locais de eventos</a> 
	<a href="<?php echo URL\URLGenerator::generateSystemURL("terms"); ?>">Termos</a> 
	<br/>
</div>

<h3 id="hGeneralSettings">Geral</h3>

<form id="frmChangeGeneralSettings" method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/general.settings.post.php", "cont=settings&action=home"); ?>">
	<fieldset>
		<legend>Termos de consentimento de uso de dados pessoais</legend>
		<?php if (checkUserPermission("LIBR", 16)): ?>
		<span class="formField">
			<label>Usuários da biblioteca: 
				<select name="sett_LIBRARY_USERS_CONSENT_FORM_TERM_ID">
					<?php foreach ($pageData['termsList'] as $t): ?>
						<option value="<?= $t['id'] ?>" <?php echo $t['id'] == $pageData["generalSettings"]["LIBRARY_USERS_CONSENT_FORM_TERM_ID"] ? ' selected ':''; ?> ><?= hsc($t['name']) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</span>
		<?php else: ?>
		<span class="formField">Você não tem permissão para alterar o termo dos usuários da biblioteca!</span>
		<?php endif; ?>
		
		<?php if (checkUserPermission("PROFE", 4)): ?>
		<span class="formField">
			<label>Docentes/Palestrantes de eventos: 
				<select name="sett_PROFESSORS_CONSENT_FORM_TERM_ID">
					<?php foreach ($pageData['termsList'] as $t): ?>
						<option value="<?= $t['id'] ?>" <?php echo $t['id'] == $pageData["generalSettings"]["PROFESSORS_CONSENT_FORM_TERM_ID"] ? ' selected ':''; ?> ><?= hsc($t['name']) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</span>
		<?php else: ?>
		<span class="formField">Você não tem permissão para alterar o termo dos docentes!</span>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>Eventos</legend>
		<?php if (checkUserPermission("EVENT", 10)): ?>
		<span class="formField"><label>Imagem de fundo padrão para certificados (novos eventos): <input type="text" name="sett_STUDENTS_CURRENT_CERTIFICATE_BG_FILE" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["STUDENTS_CURRENT_CERTIFICATE_BG_FILE"]); ?>"/></label></span>
		
		<span class="formField"><label>Porcentagem de presença mínima para aprovação: <input type="number" min="1" max="100" step="1" name="sett_STUDENTS_MIN_PRESENCE_PERCENT" value="<?php echo $pageData["generalSettings"]["STUDENTS_MIN_PRESENCE_PERCENT"]; ?>"/>%</label></span>
		
		<?php else: ?>
		<span class="formField">Você não tem permissão para alterar as configurações gerais de eventos!</span>
		<?php endif; ?>
	</fieldset>
	<fieldset>
		<legend>Redes sociais</legend>
		<?php if (checkUserPermission("SOCN", 1)): ?>
		<span class="formField"><label>Endereço do Facebook: <input type="text" name="sett_SOCIAL_MEDIA_URL_FACEBOOK" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["SOCIAL_MEDIA_URL_FACEBOOK"]); ?>"/></label></span>
		<span class="formField"><label>Endereço do Twitter: <input type="text" name="sett_SOCIAL_MEDIA_URL_TWITTER" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["SOCIAL_MEDIA_URL_TWITTER"]); ?>"/></label></span>
		<span class="formField"><label>Endereço do Instagram: <input type="text" name="sett_SOCIAL_MEDIA_URL_INSTAGRAM" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["SOCIAL_MEDIA_URL_INSTAGRAM"]); ?>"/></label></span>
		<span class="formField"><label>Endereço do Youtube: <input type="text" name="sett_SOCIAL_MEDIA_URL_YOUTUBE" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["SOCIAL_MEDIA_URL_YOUTUBE"]); ?>"/></label></span>
		<span class="formField"><label>Endereço do LinkedIn: <input type="text" name="sett_SOCIAL_MEDIA_URL_LINKEDIN" size="50" maxlength="255" value="<?php echo hscq($pageData["generalSettings"]["SOCIAL_MEDIA_URL_LINKEDIN"]); ?>"/></label></span>
		<?php else: ?>
			<span class="formField">Você não tem permissão para alterar as configurações de redes sociais!</span>
		<?php endif; ?>
	</fieldset>
	<input type="submit" name="btnsubmitChangeGeneralSettings" value="Alterar dados"/>
</form>

<h3 id="hUsers">Usuários</h3>

<form id="frmChangeUserData" method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/user.settings.post.php", "cont=settings&action=home"); ?>">
	<fieldset>
		<legend>Meus dados de usuário</legend><br/>
		<label>Nome: </label><input type="text" name="txtNewUserName" value="<?php echo hscq($pageData["userName"]); ?>" required/>
		<br/><br/>
		<label>Alterar senha:</label><br/>
		<label>Senha atual: </label><input type="password" name="txtCurrentPassword"/><br/>
		<label>Senha nova: </label><input type="password" id="txtNewPassword" name="txtNewPassword"/><br/>
		<label>Confirme a senha nova: </label><input type="password" id="txtNewPassword2" name="txtNewPassword2"/><br/>
		
		<input type="submit" id="btnsubmitChangeUserData" name="btnsubmitChangeUserData" value="Alterar dados"/>
	</fieldset>
</form>
<br/>
<form id="frmManageOtherUsers" method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/user.settings.post.php", "cont=settings&action=home&umUserId=" . $pageData["currSelectedUserId"]); ?>">
	<fieldset>
		<legend>Gerenciar usuários</legend>
		<?php if (checkUserPermission("USERS", 1)) { ?>
		<script>
			const currentUserId = <?php echo $_SESSION["userid"]; ?>;
			const thisPageURL = '<?php echo URL\URLGenerator::generateSystemURL("settings", "home", null, "{paramname}={paramvalue}" ); ?>';
			const postUserSettingsURL = '<?php echo URL\URLGenerator::generateFileURL("post/user.settings.post.php", "cont=settings&action=home"); ?>';
		</script>
		<script src="<?php echo URL\URLGenerator::generateFileURL("view/um.settings.home.view.js"); ?>"></script>
		<div>
			<label>Usuário: <select id="selUser" name="selUser">
			<?php 
				$selectedUserId = (int)$pageData["currSelectedUserId"];
			?>
				<?php foreach ($pageData["userList"] as $u) { ?>
				<option value="<?php echo $u["id"]; ?>" <?php echo ($u["id"] === $selectedUserId) ? 'selected="selected"' : ''; ?>><?php echo hsc($u["name"]); ?></option>
				<?php } ?>
			</select></label>
			<label>Alterar senha: <input type="text" name="txtManageUsersNewPassword" size="20" maxlength="80"/></label>
			<span>
				<a href="#" id="lnkNewUser">Novo usuário</a> 
				<a href="#" id="lnkDelUser">Excluir usuário</a>
			</span>
		<div>
		<br/>
		<div>
			<label>Permissões do usuário selecionado:</label>
			<ol>
			<?php
				$writeCheckedState = function($permMod, $permId) use ($pageData)
				{
					if (checkPermissionInList($pageData["currSelectedUserPermissions"], $permMod , $permId))
						return 'checked="checked"';
					else
						return '';
				};
				
				$disableManageUsersPermission = function($permMod, $permId) use ($selectedUserId)
				{
					if ($selectedUserId === (int)$_SESSION["userid"])
						if ($permMod === "USERS" && $permId === 1)
							return 'onclick="checkSetManUserPermission_onClick_blockChange.apply(this, event);"';
						else
							return '';
				};
			?>
				<?php foreach ($pageData["permissionList"] as $p) { ?>
				<li><label><input type="checkbox" name="permission[]" value="<?php echo $p["permMod"] . "_" . $p["permId"]; ?>" <?php echo $writeCheckedState($p["permMod"], $p["permId"]) . " " . $disableManageUsersPermission($p["permMod"], $p["permId"]); ?>/><?php echo $p["permDesc"]; ?></label></li>
				<?php } ?>
			</ol>
		</div>
		<input type="submit" name="btnsubmitChangeOtherUser" value="Alterar dados"/>
		<div>
			<p>Aviso: As mudanças de permissão, senha, ou exclusão de usuário só terão efeito após o usuário alterado sair e logar-se novamente.</p>
		</div>
		<?php } else { ?>
		<p>Você não tem permissão para gerenciar usuários.</p>
		<?php } ?>
	</fieldset>
</form>

<h3 id="hEnums">Enumeradores</h3>

<style>
.btnDeleteEnumType
{
	min-width: 20px !important;
}
</style>

<script src="<?php echo URL\URLGenerator::generateFileURL("view/enums.settings.home.view.js"); ?>"></script>
<form id="frmEditEnums" action="<?php echo URL\URLGenerator::generateFileURL("post/enums.settings.post.php", "cont=settings&action=home"); ?>" method="post">
	<fieldset>
		<legend>Eventos: Tipos de eventos</legend>
		<?php if(checkUserPermission("ENUM", 1)) { ?>
		<ol class="EVENT">
			<?php foreach ($pageData["enums"]["EVENT"] as $et): ?>
			<li data-id="<?php echo $et["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($et["name"]); ?>"/></li>
			<?php endforeach; ?>		
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/> 
		<p>Aviso: Só é possível adicionar ou editar tipos de evento para evitar problemas técnicos no banco de dados. Se precisar realmente excluir um tipo de evento, contate o desenvolvedor do sistema.</p>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Gêneros</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="GENDER">
			<?php foreach ($pageData["enums"]["GENDER"] as $gt): ?>
			<li data-id="<?php echo $gt["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($gt["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Áreas de atuação</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="OCCUPATION">
			<?php foreach ($pageData["enums"]["OCCUPATION"] as $ot): ?>
			<li data-id="<?php echo $ot["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($ot["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Escolaridades</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="SCHOOLING">
			<?php foreach ($pageData["enums"]["SCHOOLING"] as $st): ?>
			<li data-id="<?php echo $st["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($st["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Etnia</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="RACE">
			<?php foreach ($pageData["enums"]["RACE"] as $st): ?>
			<li data-id="<?php echo $st["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($st["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Nacionalidade</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="NATION">
			<?php foreach ($pageData["enums"]["NATION"] as $st): ?>
			<li data-id="<?php echo $st["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($st["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Inscrição: Estado (UF)</legend>
		<?php if (checkUserPermission("ENUM", 2)) { ?>
		<ol class="UF">
			<?php foreach ($pageData["enums"]["UF"] as $st): ?>
			<li data-id="<?php echo $st["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($st["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<fieldset>
		<legend>Biblioteca: Tipos de aquisição</legend>
		<?php if (checkUserPermission("ENUM", 3)) { ?>
		<ol class="LIBACQTYPE">
			<?php foreach ($pageData["enums"]["LIBACQTYPE"] as $lat): ?>
			<li data-id="<?php echo $lat["id"]; ?>"><input type="text" class="txtEnumItemName" size="40" value="<?php echo hscq($lat["name"]); ?>"/><input type="button" class="btnDeleteEnumType" value="X"/></li>
			<?php endforeach; ?>
		</ol>
		<input type="button" class="btnAddEnumType" value="Adicionar"/>
		<?php } else { ?>
		<p>Você não tem permissão para editar este enumerador.</p>
		<?php } ?>
	</fieldset>
	<input type="submit" id="btnsubmitEditEnums" name="btnsubmitEditEnums" value="Alterar enumeradores" />
	<input type="hidden" id="hiddenJsonChangesReport" name="hiddenJsonChangesReport" value="" />
</form>