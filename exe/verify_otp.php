<?php
date_default_timezone_set("Asia/Kolkata");
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$verify_otp = addslashes($obj['verify_otp']);
$mobile_no = addslashes($obj['mobile_no']);
$otp = addslashes($obj['otp']);

$json = array();

if($verify_otp == true){

	$sql1 = "SELECT * FROM `otp` WHERE `otp` = '".$otp."' AND `mobile_no` = '".$mobile_no."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) == 0){
		// encode results
		$json['success'] 	= false;
		$json['msg'] 	= "OTP Not matching";
	}
	else{
		
		$json['success'] 	= true;
		$json['msg'] = 'OTP Verified Successfully';
		$sql1 = "DELETE FROM `otp` WHERE `mobile_no` = '".$mobile_no."';";
		$exe1 = mysqli_query($conn ,$sql1);
	}		
}
else{
	// request is empty
	$json['success'] = false;
	$json['msg'] = "OTP verify Error";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>