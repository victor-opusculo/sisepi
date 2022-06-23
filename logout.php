<?php
session_name("sisepi_system_user");
session_start();

require_once('includes/logEngine.php');
writeLog("Log-off de usuário.");

session_unset();
session_destroy();

header('location:login.php');