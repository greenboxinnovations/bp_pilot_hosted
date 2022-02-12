<?php

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


if(!isset($_SESSION))
{
	session_start();
}

if(isset($_POST['new_cashier'])){

	$cashier = strtolower($_POST['new_cashier']);

	$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$_SESSION['pump_id']."' AND `name` = '".$cashier."' ;";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);

	$p = '$2y$12$mSDdyxbGuBsPpq/haYsWtuTtRyzoBOn5cJNUoWnjy.r91.8zBOJoq';



	if ($count < 1) {
		$sql = "INSERT INTO `users`(`name`,`pass`,`user_pump_id`,`role`) VALUES('".$cashier."','".$p."','".$_SESSION['pump_id']."','operator') ;";
		$exe = mysqli_query($conn, $sql);		

		echo'User added successfully';

		date_default_timezone_set("Asia/Kolkata");
		$date_new = date("Y-m-d H:i:s");
		$unix = strtotime($date_new);

		Globals::updateSyncTable("users", "user_id", $unix, $_SESSION['pump_id']);
	}else{
		echo'Duplicate name';
	}	
	
}


?>