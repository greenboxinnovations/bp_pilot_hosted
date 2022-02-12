<?php
if(!isset($_SESSION))
{
	session_start();
	if (!isset($_SESSION['pump_id']) || !isset($_SESSION['role'])) 
	{
	    header("location: login.php");
	}
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT * FROM `cars` WHERE `car_cust_id` ='".$cust_id."' AND `status` = 'active' ";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);
	if ($count > 0) {
		
		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';
		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
				echo '<th>#</th>';		
				echo '<th>Car Brand</th>';
				echo '<th>Car Sub-Brand</th>';
				echo '<th>No Plate</th>';
				echo '<th>Fuel Type</th>';
				echo '<th>Car QR</th>';
				
				if ($_SESSION['role'] == "admin") {
					echo '<th></th>';
				}
			echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
			$i=1;
			while($row = mysqli_fetch_assoc($exe)){
				// $car_id	 = $row["car_id"];
				// $car_pump_id	 = $row["car_pump_id"];
				$car_brand	 	 = ucwords($row["car_brand"]);
				$car_sub_brand	 = ucwords($row["car_sub_brand"]);
				$car_no_plate	 = strtoupper($row["car_no_plate"]);
				$car_fuel_type	 = ucwords($row["car_fuel_type"]);
				// $car_cust_id	 = $row["car_cust_id"];
				$car_qr_code	 = strtoupper($row["car_qr_code"]);
				$car_id 		 = $row['car_id'];

				echo '<tr class="highlight">';
					// echo '<td>'.$car_id.'</td>';
					echo '<td>'.$i.'</td>';
					echo '<td>'.$car_brand.'</td>';
					echo '<td>'.$car_sub_brand.'</td>';
					echo '<td>'.$car_no_plate.'</td>';
					echo '<td>'.$car_fuel_type.'</td>';
					// echo '<td>'.$car_cust_id.'</td>';
					echo '<td>'.$car_qr_code.'</td>';
					if ($_SESSION['role'] == "admin") {
						echo '<td><button class="del_car" carid="'.$car_id.'" >Delete</button></td>';	
					}
					
				echo '</tr>';
				$i++;
			}
		echo '</tbody>';
		echo '</table>';

		// echo'<button id="btn_add_car" custid="'.$cust_id.'">Add Car</button>';
		// echo'<div class="mat_btn_clear" style="margin-top:10px;" id="btn_add_car" custid="'.$cust_id.'">ADD CAR</div>';
	}
}
?>