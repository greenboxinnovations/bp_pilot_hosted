<?php


if(!isset($_SESSION)) {
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
$pump_id = $_SESSION['pump_id'];


if(isset($_GET['date'])){

	$date = $_GET["date"];
	if($date == ""){
		$date = date("Y-m-d");	
	}
	else{
		$date = date("Y-m-d", strtotime($date));
	}

	$shift = $_GET["shift"];


	echo '<table id="emp_table">';

	$total = 0;

	$first = true;
	$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' and `role` = 'operator' order by `name` ASC";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){
		$user_id	 = $row["user_id"];
		$name	 = $row["name"];
		$pass	 = $row["pass"];
		$user_pump_id	 = $row["user_pump_id"];
		$imei	 = $row["imei"];
		$role	 = $row["role"];


		
		$sql2 = "SELECT sum(`amount`) as emp_total FROM `transactions` WHERE `user_id` = '".$user_id."' AND `shift` = '".$shift."' AND date(`date`) = '".$date."';";
		$exe2 = mysqli_query($conn, $sql2);
		$num_rows = mysqli_num_rows($exe2);	
		$row = mysqli_fetch_assoc($exe2);
		$emp_total = $row["emp_total"];		

		$total = $total+$row["emp_total"];	
		
		

		
		if($first){
			echo '<tr empid="'.$user_id.'" class="emp_active">';
			echo '<td>'.ucwords($name).'</td>';
			echo '<td style="text-align:right;">'.$emp_total.'</td>';
			echo '</tr>';
		}
		else{
			echo '<tr empid="'.$user_id.'">';
			echo '<td>'.ucwords($name).'</td>';
			echo '<td style="text-align:right;">'.$emp_total.'</td>';
			echo '</tr>';
		}
		

		$first = false;
	}
	echo '</table>';	

	echo '<div class="daily_rate_single" style="margin-top:10px;">Shift TOTAL <span>'.$total.'</span></div>';
}



?>