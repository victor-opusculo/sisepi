<style>
    .listMainBlock { counter-reset: mainblock; }
    .listMainBlock > .listItem::before 
    { 
        counter-increment: mainblock;
        content: counter(mainblock); 
    }

    .listItem::before
    {
        font-weight: bold;
        color: #22B14C;
        display: block;
        position: absolute;
        background-color: white;
        text-align: center;
        left: -20px;
        top: -2px;
        width: 40px;
    }

    .listItem
    { 
        margin: 0.8em 0.3em 0.8em 0.3em;
        padding-left: 0.8em;
        border-left: 2px solid black;
        position:relative;
    }

    .itemTitle 
    { 
        display: block;
        font-size: 1em;
        font-weight: bold;
    } 

    .listItem_subItem ol li { margin-bottom: 0.8em; }
</style>