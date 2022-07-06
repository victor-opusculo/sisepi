<?php

session_name("sisepi_professor");
session_start();

if((!isset ($_SESSION['professorid']) === true) and (!isset ($_SESSION['professoremail']) === true))
{
    session_unset();
    session_destroy();
    header('location:' . URL\URLGenerator::generateSystemURL('professors', 'login'));
    exit();
}