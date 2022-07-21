<style>
    .tabsComponent_tabs 
    {
        display: flex;
        flex-wrap: wrap;
    }
 
    .tabsComponent_tabs label.tabsComponent_label 
    {
        order: 1;
        display: block;
        padding: 0.7rem 1.2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background:  white;
        font-weight: bold;
        border-radius: 7px 7px 0 0;
        border-top: 1px solid darkgray;
        border-left: 1px solid darkgray;
        border-right: 1px solid darkgray;
        box-shadow: 3px -1px 4px 0 rgb(0 0 0 / 20%)
    }

    .tabsComponent_tabs label.tabsComponent_label:hover
    {
        background-color: #eee;
    }
 
    .tabsComponent_tabs .tabsComponent_tab 
    {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        border: 1px solid darkgray;
        box-shadow: 4px 1px 4px 0 rgb(0 0 0 / 20%);
    }
 
    .tabsComponent_tabs input[type="radio"].tabsComponent_inputRadio
    {
        display: none;
    }
 
    .tabsComponent_tabs input[type="radio"].tabsComponent_inputRadio:checked + label.tabsComponent_label 
    {
        position: relative;
    }

    .tabsComponent_tabs input[type="radio"].tabsComponent_inputRadio:checked + label.tabsComponent_label:before
    {
        content: "";
        border-radius: 7px 7px 0 0;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 7px;
        background-color: #22B14C;
    }

    .tabsComponent_tabs input[type="radio"].tabsComponent_inputRadio:checked + label.tabsComponent_label:after
    {
        content: "";
        position:absolute;
        background-color: white;
        height: 2px;
        bottom: -2px;
        left: 0;
        right: 0;
    }
 
    .tabsComponent_tabs input[type="radio"].tabsComponent_inputRadio:checked + label.tabsComponent_label + .tabsComponent_tab 
    {
        display: block;
    }

    @media (max-width: 749px) 
    {
        .tabsComponent_tabs .tabsComponent_tab,
        .tabsComponent_tabs label.tabsComponent_label 
        {
            order: initial;
        }
 
        .tabsComponent_tabs label.tabsComponent_label 
        {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
}
</style>