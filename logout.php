<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/
session_start();
require 'database/Login.php';

$login = new Login();

if($login->do_logout() == TRUE){

	$login->redirect('index.php');

}

?>