<?php 

function getMailConfigs()
{
    $configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/sisepi_config.ini", true);
    return $configs['regularmail'];
}