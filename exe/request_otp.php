<?php
date_default_timezone_set("Asia/Kolkata");

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
 
$request_otp = addslashes($obj['request_otp']);
$mobile_no = addslashes($obj['mobile_no']);

$json = array();

function sendOTP($otp,$mobile_no){

	$message = "Thanks for registration with FuelCam. OTP: ".$otp;
    $encodedMessage = urlencode($message);
    $api = Globals::msgString($encodedMessage, $mobile_no);
    // $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".$mobile_no."&flash=0";

    // Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $api,
	    //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
	));
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	// echo $httpcode;
	// Close request to clear up some resources
	curl_close($curl);
}

if($request_otp == true){


	$six_digit_random_number = mt_rand(1000, 9999);

	// get details from cars
	$sql1 = "SELECT * FROM `otp` WHERE `mobile_no` = '".$mobile_no."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) == 0){

		$time = date("Y-m-d H:i:s");
		
		// details from CUSTOMERS
		$sql = "INSERT INTO `otp` (`mobile_no`,`otp`,`timestamp`) VALUES ('".$mobile_no."','".$six_digit_random_number."','".$time."') ;";
		$exe = mysqli_query($conn ,$sql);
		
		// encode results
		$json['success'] 	= true;
		$json['otp'] 	= $six_digit_random_number;

		sendOTP($six_digit_random_number,$mobile_no);		
	}
	else{
		
		$sql1 = "SELECT `otp` FROM `otp` WHERE  `mobile_no` = '".$mobile_no."';";
		$exe1 = mysqli_query($conn ,$sql1);
		$row = mysqli_fetch_assoc($exe1);

		$otp = $row['otp'];
		sendOTP($otp,$mobile_no);	
		
		$json['success'] 	= true;
		$json['msg'] = 'OTP already present,resent';
	}		
}
else{
	// request is empty
	$json['success'] = false;
	$json['msg'] = "OTP Request Error";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>