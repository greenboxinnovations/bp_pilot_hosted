<?php
date_default_timezone_set("Asia/Kolkata");

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
 
$total_r = addslashes($obj['total_r']);
$cust_id = addslashes($obj['cust_id']);

$json = array();

function sendOTP($total_r,$mobile_no){

	$message = "Dear Customer this is a gentle reminder that your total remaining balace is ".$total_r." Please pay at earliest - FuelCam";
    $encodedMessage = urlencode($message);
    $api = Globals::msgString($encodedMessage, $mobile_no);
    
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

if($total_r > 0){

	// get details from cars
	$sql1 = "SELECT `cust_ph_no2` FROM `customers` WHERE `cust_id` = '".$cust_id."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) == 1){
		$row = mysqli_fetch_assoc($exe1);
		$cust_ph_no2	 = $row["cust_ph_no2"];

		// encode results
		if ($cust_ph_no2 != NULL) {
			$json['success'] 	= true;
			$json['msg']		= "Reminder sent";
			//sendOTP($total_r,$cust_ph_no2);		
		}else{

			$json['success'] 	= true;
			$json['msg']		= "Reminder not sent, Cust reminder no not set";
		}				
	}
}
else{
	// request is empty
	$json['success'] = false;
	$json['msg'] = "Total Amount is Zero";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>