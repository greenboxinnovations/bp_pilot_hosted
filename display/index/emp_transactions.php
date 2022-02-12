<?php


if(!isset($_SESSION)) {
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
$pump_id = $_SESSION['pump_id'];

if(isset($_GET['emp_id'])){


	$emp_id 	= $_GET['emp_id'];
	$date 		= $_GET['date'];
	$shift 		= $_GET['shift'];


	// // get pump id
	// $user_id = $_SESSION['user_id'];
	// $sql = "SELECT `role`,`user_pump_id` FROM `users` WHERE `user_id` = '".$user_id."';";
	// $exe = mysqli_query($conn, $sql);
	// while($row = mysqli_fetch_assoc($exe)){
	// 	$_SESSION['access'] = $row['role'];
	// 	$pump_id = $row['user_pump_id'];
	// }


	// get first emp if not emp id passed
	if($emp_id == ""){
		$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' and `role` = 'operator' order by `name` ASC LIMIT 1";
		$exe = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($exe);
		$emp_id = $row["user_id"];	
	}

	// get todays date if no date passed
	if($date == ""){
		$date = date("Y-m-d");
	}
	else{
		$date = date("Y-m-d", strtotime($date));
	}



	
	$sql = "SELECT a.*, b.cust_f_name, b.cust_l_name, b.cust_company
			FROM `transactions` a
			JOIN `customers` b
			ON a.cust_id = b.cust_id
			WHERE date(a.date) = '".$date."' AND a.user_id = '".$emp_id."' AND a.shift = '".$shift."'  ORDER BY a.receipt_no ASC;";
	$exe = mysqli_query($conn, $sql);

	$count = mysqli_num_rows($exe);
	if($count > 0){
		echo '<table>';
		

		while($row = mysqli_fetch_assoc($exe)){
			
			$cust_id	 = $row["cust_id"];
			$car_id	 = $row["car_id"];
			$receipt_no	 = $row["receipt_no"];
			$shift	 = $row["shift"];
			$fuel	 = $row["fuel"];
			$amount	 = $row["amount"];
			$rate	 = $row["rate"];
			$liters	 = $row["liters"];
			$billed	 = $row["billed"];
			// $date	 = $row["date"];			
			

			// from customers table
			$cust_company = $row['cust_company'];
			if($cust_company == ""){
				$cust_f_name	 = $row["cust_f_name"];
				$cust_l_name	 = $row["cust_l_name"];
				$cust_disp = ucwords($cust_f_name." ".$cust_l_name);
			}
			else{
				$cust_disp = ucwords($cust_company);
			}

			echo '<tr>';
				echo '<td>'.$receipt_no.'</td>';
				echo '<td>'.$cust_disp.'</td>';
				// echo '<td>'.$car_id.'</td>';			
				// echo '<td>'.$shift.'</td>';
				// echo '<td>'.$fuel.'</td>';
				echo '<td>'.$amount.'</td>';
				// echo '<td>'.$rate.'</td>';
				// echo '<td>'.$liters.'</td>';
				// echo '<td>'.$billed.'</td>';
				// echo '<td>'.$date.'</td>';			
			echo '</tr>';
		}
		echo '</table>';
	}
	else{
		echo '<p>No data found</p>';
	}

}


?>