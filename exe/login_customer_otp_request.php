<?php
date_default_timezone_set("Asia/Kolkata");
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$output=array();
function sendOTP($otp,$mobile_no){ 

	$message = "Your Login OTP for FuelCam account is: ".$otp;
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

if (isset($_POST['request_otp'])) {
	$mobile_no	= addslashes($_POST['mobile_no']);

	$sql   = "SELECT * FROM `customers` WHERE  `cust_ph_no` LIKE '%".$mobile_no."%';";
	$exe   = mysqli_query($conn ,$sql);
	$count = mysqli_num_rows($exe);

	if ($count >0) {

		$row = mysqli_fetch_assoc($exe);
		$cust_id = $row['cust_id'];


		$sql1 = "SELECT * FROM `otp` WHERE  `mobile_no` = '".$mobile_no."';";
		$exe1 = mysqli_query($conn ,$sql1);
		$row = mysqli_fetch_assoc($exe1);

		if(mysqli_num_rows($exe1) == 0){

			$time = date("Y-m-d H:i:s");
			$six_digit_random_number = mt_rand(1000, 9999);
			// details from CUSTOMERS
			$sql = "INSERT INTO `otp` (`mobile_no`,`otp`,`timestamp`) VALUES ('".$mobile_no."','".$six_digit_random_number."','".$time."') ;";
			$exe = mysqli_query($conn ,$sql);

			sendOTP($six_digit_random_number,$mobile_no);	

		}
		else{

			$time = date("Y-m-d H:i:s");
			$otp = $row['otp'];
			$timestamp = $row['timestamp'];

			$diff = strtotime($timestamp) - strtotime($time);
			
			if ($diff < 300) {
				sendOTP($otp,$mobile_no);
			}else{

				$sql1 = "DELETE FROM `otp` WHERE `mobile_no` = '".$mobile_no."';";
				$exe1 = mysqli_query($conn ,$sql1);

				$six_digit_random_number = mt_rand(1000, 9999);

				$sql = "INSERT INTO `otp` (`mobile_no`,`otp`,`timestamp`) VALUES ('".$mobile_no."','".$six_digit_random_number."','".$time."') ;";
				$exe = mysqli_query($conn ,$sql);

				sendOTP($six_digit_random_number,$mobile_no);
			}
		}	

		$output['success'] = true;
		$output['cust_id'] =  $cust_id;		
	}else{
		$output['success'] = false;
		$output['msg'] = 'Mobile number not registered';
	}

	echo json_encode($output, JSON_NUMERIC_CHECK);
		
}

?>