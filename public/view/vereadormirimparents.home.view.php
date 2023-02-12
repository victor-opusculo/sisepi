<style>
    #vmParentPanelPages 
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

<?php include __DIR__ . '/fragment/vereadormirimparents.logoutlink.view.php'; ?>

<h3>Bem-vindo ao seu painel do responsável!</h3>
<p></p>

<div id="vmParentPanelPages">
    <a class="pageLink" href="<?php echo URL\URLGenerator::generateSystemURL('vereadormirimparentpanel', 'listvmstudents'); ?>">
        <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/politician-by-Freepik.png"); ?>" title="Meus Vereadores Mirins (ícone por Freepik)" /><br/>
        <span class="pageLinkName">Meus Vereadores Mirins</span>
    </a>
    </a>
</div>