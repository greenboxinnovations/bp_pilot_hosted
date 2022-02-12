<?php
date_default_timezone_set("Asia/Kolkata");
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if(isset($_GET['date'])){
	

	$date = $_GET['date'];
	if($date == ""){
		$date = date("Y-m-d");
	}

	$date = date("Y-m-d" , strtotime($date));

	if(isset($_GET['pump_id'])){
		$pump_id = $_GET['pump_id'];
	}else{
		//$user_id   = $_SESSION['user_id'];

		$pump_id = $_SESSION['pump_id'];

		// $sql = "SELECT `role`,`user_pump_id` FROM `users` WHERE `user_id` = '".$user_id."';";
	 //    $exe = mysqli_query($conn, $sql);
	 //    while($row = mysqli_fetch_assoc($exe)){
	 //    	$_SESSION['access'] = $row['role'];
	 //    	$pump_id = $row['user_pump_id'];
	 //    }
	}

    $output = array();

	$sql = "SELECT * FROM `rates` WHERE `pump_id` = '".$pump_id."' AND `date` = '".$date."'  ORDER BY `rate_id` DESC LIMIT 1;";
	$exe = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($exe);
	if(mysqli_num_rows($exe) < 1){
	    $output['rate_set'] = false;
	}
	else{		
		$output['rate_set'] = true;	
		$output['petrol']	= $row['petrol'];			
		$output['diesel']	= $row['diesel'];
		$output['date']	    = $row['date'];
	}

	echo json_encode($output);
}
?>