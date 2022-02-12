<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


function sendMSG($phone_no){
// private function sendMSG($car_no_plate, $fuel, $amount, $url, $phone_no){	

	$url = Globals::URL_MSG_VIEW."Yqpey175d9";
	$car_no_plate = "MH11BM0201";
	$fuel = "Diesel";
	$amount	= "1662.52";	

	$timestamp = date("d/m/Y H:i:s");
	// echo "Formatted date from timestamp:" . $timestamp;

	$newline = "\n";

	$message = "SELECT AUTOMOBILES".$newline."Karve Road".$newline.$newline.$car_no_plate.$newline."Rs.".$amount.$newline.strtoupper($fuel).$newline.$timestamp.$newline.$newline.$url;
	$encodedMessage = urlencode($message);

	$api = Globals::msgString($encodedMessage,$phone_no, true);

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
	// Close request to clear up some resources
	curl_close($curl);
}



if (isset($_POST['myObject'])) {
	
	$myObj 		= $_POST['myObject'];

	$cust_name 	= $myObj["cust_name"];
	$cust_num 	= $myObj["cust_num"];

	$timestamp 	= date("Y-m-d H:i:s");

	$output = array();


	// table structure
	// id // cust_name // cust_ph_no // datetime // last-updated // msg_num //

	// check if phone number exists
	$sql = "SELECT * FROM `demo_cust_details` WHERE `cust_ph_no` = '".$cust_num."';";
	$exe = mysqli_query($conn, $sql);
	// echo mysqli_num_rows($exe);
	if(mysqli_num_rows($exe) == 0){
		// if not add to demo-cust db

		$sql2 = "INSERT INTO `demo_cust_details`(`cust_name`, `cust_ph_no`, `date`, `last_updated`, `msg_num`) 
				VALUES ('$cust_name','$cust_num','$timestamp','$timestamp', 1);";
		$exe2 = mysqli_query($conn, $sql2);
		// send msg logic

		$output["success"] = true;
		$output["msg"] = "MSG SENT SUCCESSFULLY";
		sendMSG($cust_num);
	}
	else{
		while($row = mysqli_fetch_assoc($exe)){
			$msg_num = $row["msg_num"];
			$last_updated = $row["last_updated"];

			// prevent msg spam
			// if number of sms exceeds max limit
			if($msg_num < Globals::MAX_DEMO_MSG){

				// check time diff
				$timediff = strtotime($timestamp) - strtotime($last_updated);
				$output['time'] = $timediff;

				// if less than 1 min allow
				if($timediff > Globals::DEMO_MSG_TIME_DIFF){

					$sql3 = "UPDATE `demo_cust_details` 
							SET `last_updated` = '$timestamp', `msg_num` = `msg_num`+1 
							WHERE `cust_ph_no` = '$cust_num';";
					$exe3 = mysqli_query($conn, $sql3);
					$output["success"] = true;
					$output["msg"] = "MSG SENT SUCCESSFULLY";
					sendMSG($cust_num);
				}
				else{
					$output["success"] = false;
					$output["msg"] = "PLEASE TRY AFTER 1 MIN";
				}
			}
			else{
				$output["success"] = false;
				$output["msg"] = "MAX MSG LIMIT EXCEEDED";
			}
		}
		
	}

	echo json_encode($output);
}
// else{
// 	echo "chutya";
// }



?>
