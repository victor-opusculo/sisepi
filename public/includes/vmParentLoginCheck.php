<?php

session_name("sisepi_vmparent");
session_start();

require_once __DIR__ . '/common.php';

if((!isset ($_SESSION['vmparentid']) === true) and (!isset ($_SESSION['vmparentemail']) === true))
{
    session_unset();
    session_destroy();
    header('location:' . URL\URLGenerator::generateSystemURL('vereadormirimparents', 'login'));
    exit();
}