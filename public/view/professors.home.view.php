<style>
    #professorPanelPages 
    { 
        display: flex; 
        justify-content: center;
        flex-wrap: wrap
    }

    a.pageLink
    {
        display: inline-block;
        margin: 0.5em;
        width: 200px;
        height: 200px;
        text-align: center;
        font-size: 110%;
        padding: 0.2em;
        border: solid 1px #BBB;
        border-radius: 5px;
        font-weight: bold;
        color: black;
        background-color: #EEE;
        text-decoration: none;
        box-shadow: inset 0px -7px 10px 0 rgba(0, 0, 0, 0.12);
        transition-duration: 0.1s;
        cursor: pointer;
        flex-shrink: 0;
        flex-grow: 0;

    }
    
    a.pageLink:hover { background-color: #DDD; }
    
    a.pageLink:active
    {
        background-color: #DDD;
        box-shadow: inset 0px 7px 10px 0 rgba(0, 0, 0, 0.12);
    }
</style>

<?php include 'view/fragment/professors.logoutlink.view.php'; ?>

<h3>Bem-vindo ao seu painel de docente!</h3>
<p>Aqui você poderá editar seus dados cadastrais e enviar a documentação necessária para o pagamento. 
    Futuramente, novas funcionalidades serão adicionadas para sua maior conveniência.</p>

<div id="professorPanelPages">
    <a class="pageLink" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'editpersonalinfos'); ?>">
        <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/professor-personal-infos-by-Freepik.png"); ?>" title="Alterar dados cadastrais (ícone por Freepik)" />
        <span class="pageLinkName">Alterar dados cadastrais</span>
    </a>
    <a class="pageLink" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'uploadpersonaldocs'); ?>">
        <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/professor-documents-by-Freepik.png"); ?>" title="Upload de documentos (ícone por Freepik)" />
        <span class="pageLinkName">Upload de documentos</span>
    </a>
    <!--
    <a class="pageLink" href="<?php echo URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'professorworkproposals'); ?>">
        <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/professor-work-proposals-by-Freepik.png"); ?>" title="Propostas de trabalho (ícone por Freepik)" />
        <span class="pageLinkName">Propostas de trabalho</span>
-->
    </a>
</div>