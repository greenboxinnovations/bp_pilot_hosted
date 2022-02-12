<?php
date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['verify_otp'])) {

	require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

	$json = array();
	$json['success'] = false;

	$mobile_no	= addslashes($_POST['mobile_no']);
	$otp		= addslashes($_POST['otp']);
	$cust_id	= addslashes($_POST['cust_id']);
	$storage	= addslashes($_POST['storage']);
	$user_agent	= addslashes($_POST['user_agent']);

	$sql1 = "SELECT * FROM `otp` WHERE `mobile_no` = '".$mobile_no."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) == 0){
		// encode results
		$json['msg'] = 'Retry sending otp';
	}
	else{

		$row  = mysqli_fetch_assoc($exe1); 

		$time = date("Y-m-d H:i:s");
		$otp = $row['otp'];
		$timestamp = $row['timestamp'];

		$diff = strtotime($timestamp) - strtotime($time);
		
		if ($diff < 300) {
			$json['success'] = true;
			$json['msg'] = 'Login true';

			$token = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);

			// $sql1 = "UPDATE `customers` SET `token`= '".$token."' ,`cust_last_updated` = '".$time."' WHERE `cust_id` = '".$cust_id."' ;";
			// $exe1 = mysqli_query($conn ,$sql1);

			$sql2 = "INSERT INTO `tokens`(`cust_id`,`token`, `last_updated`, `user_agent`, `storage_details`) VALUES ('".$cust_id."','".$token."','".$time."','".$user_agent."','".$storage."');";
			$exe2 = mysqli_query($conn ,$sql2);

			$json['token'] = $token;

			setcookie("fuelmaster_user", $token, (30 * 24 * 60 * 60 * 1000),"/");


		}else{
			$json['msg'] = 'Retry sending otp';
		}

		$sql1 = "DELETE FROM `otp` WHERE `mobile_no` = '".$mobile_no."';";
		$exe1 = mysqli_query($conn ,$sql1);
	}	

	echo json_encode($json);		
}

?>