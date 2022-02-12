<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if(isset($_GET['trans_id'])){
	$trans_id = $_GET['trans_id'];

	$output = array();

	// $sql0 = "SELECT a.*,b.car_no_plate,d.cust_company,d.cust_f_name,d.cust_l_name FROM `transactions` a JOIN  `cars` b ON a.car_id=b.car_id  JOIN `customers` d ON b.car_cust_id = d.cust_id  WHERE a.trans_id =  '".$trans_id."' ;";


	$sql0 = "SELECT a.*,b.cust_disp_name, c.name, d.car_no_plate
			FROM `transactions` a 
			JOIN `customers` b ON b.cust_id = a.cust_id 
			JOIN `users` c ON c.user_id = a.user_id 
			JOIN `cars` d ON d.car_id = a.car_id 
			WHERE a.trans_id = '".$trans_id."';";
	$result0 = mysqli_query($conn,$sql0);
	$row = mysqli_fetch_assoc($result0);
	array_push($output,$row);

	echo json_encode($output);	
}

?>

