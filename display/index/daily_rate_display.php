<?php
date_default_timezone_set("Asia/Kolkata");
 
if(!isset($_SESSION)) {
	session_start();
	if (!isset($_SESSION['pump_id']) || !isset($_SESSION['role'])) 
	{
	    header("location: login.php");
	}
}
$pump_id = $_SESSION['pump_id'];
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


if(isset($_GET['date'])){

	$date = $_GET['date'];
	if($date == ""){
		$date = date("Y-m-d");
	}else{
		$date = date("Y-m-d", strtotime($date));
	}

	// $user_id = $_SESSION['user_id'];

	// $sql = "SELECT `role`,`user_pump_id` FROM `users` WHERE `user_id` = '".$user_id."';";
	// $exe = mysqli_query($conn, $sql);
	// while($row = mysqli_fetch_assoc($exe)){
	// 	$_SESSION['access'] = $row['role'];
	// 	$pump_id = $row['user_pump_id'];
	// }

	$sql = "SELECT SUM(`amount`) as total FROM `transactions` WHERE date(`date`) = '".$date."';";
	$exe = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($exe);

	if(mysqli_num_rows($exe) != 0){
		$total	= $row['total'];	
	}else{
		$total = 0;
	}

	

	$sql = "SELECT * FROM `rates` WHERE `pump_id` = '".$pump_id."' AND `date` = '".$date."' ORDER BY `rate_id` DESC LIMIT 1;";
	$exe = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($exe);

	if(mysqli_num_rows($exe) < 1){
		echo '<p>Rate Not Set</p>';
	}
	else{		
		$petrol	= $row['petrol'];			
		$diesel	= $row['diesel'];


		echo '<div id="daily_rate_holder">';
			echo '<div class="daily_rate_single">PETROL <span>'.$petrol.'</span></div>';
			echo '<div style="margin-left: 10px;" class="daily_rate_single">DIESEL <span>'.$diesel.'</span></div>';
			echo '<div style="margin-left: 10px;" class="daily_rate_single">Total Sale <span>'.$total.'</span></div>';
			echo '<div style="margin-left:10px;" class="mat_btn" id="btn_new_rate">NEW RATE</div>';
		echo '</div>';

	}	
}
else{
	echo '<p>Error</p>';
}


?>