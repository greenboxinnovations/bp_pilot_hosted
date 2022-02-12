<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
$pump_id = $_SESSION['pump_id'];

echo '<table border="1">';

$sql = "SELECT * FROM `cars` WHERE `pump_id` = '".$pump_id."';";
$exe = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($exe)){
	$car_id	 = $row["car_id"];
	$car_brand	 = $row["car_brand"];
	$car_sub_brand	 = $row["car_sub_brand"];
	$car_no_plate	 = $row["car_no_plate"];
	$car_fuel_type	 = $row["car_fuel_type"];
	$car_cust_id	 = $row["car_cust_id"];
	$car_qr_code	 = $row["car_qr_code"];


	echo '<tr>';

		echo '<td>'.$car_brand.'</td>';
		echo '<td>'.$car_sub_brand.'</td>';
		echo '<td>'.$car_no_plate.'</td>';
		echo '<td>'.$car_fuel_type.'</td>';
		echo '<td>'.$car_cust_id.'</td>';
		echo '<td>'.$car_qr_code.'</td>';
	echo '</tr>';
}


echo '</table>';

?>