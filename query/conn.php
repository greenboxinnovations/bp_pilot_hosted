<?php
	if(!isset($_SESSION))
	{
		session_start();
	}
	date_default_timezone_set("Asia/Kolkata");

	$oneUp = realpath(__DIR__.'/..');
	
	require_once $oneUp.'/api/classes/globals.class.php';
	require_once $oneUp.'/api/classes/database.class.php';

	$host_name 	= Globals::DB_HOSTNAME;
	$user_name 	= Globals::DB_USER_NAME;
	$password 	= Globals::DB_PASSWORD;
	$db_name 	= Globals::DB_NAME;

	$conn = mysqli_connect($host_name, $user_name, $password, $db_name);

	if($conn == FALSE)
	{
		echo 'ERROR: '.mysqli_connect_error($conn);
	}
?>