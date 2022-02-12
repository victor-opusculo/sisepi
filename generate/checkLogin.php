<?php
session_start();
if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['passwordhash']) == true))
{
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	unset($_SESSION['passwordhash']);
	unset($_SESSION['permissions']);
	header('location:../login.php');
	exit();
}