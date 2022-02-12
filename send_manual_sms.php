<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

function sendMSG($car_no_plate, $fuel, $amount, $url, $phone_no){

	 $message = "Hi, Yor vehicle no ".$car_no_plate." just filled ".$fuel." worth ".$amount.". details: ".$url;
    $encodedMessage = urlencode($message);
   echo $api = Globals::msgString($encodedMessage, $phone_no, true);

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

$trans_ids = [3];
echo 'dvsv';
foreach ($trans_ids as $trans_id) {
	$sql = "SELECT * FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
	$exe = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($exe)){

		//get car no
		$sql1 = "SELECT `car_no_plate` FROM `cars` WHERE `car_id` = '".$row['car_id']."';";		
		$exe1 = mysqli_query($conn, $sql1);
		$row1 = mysqli_fetch_assoc($exe1);
		$car_no_plate = $row1['car_no_plate'];

		$url = Globals::URL_MSG_VIEW.$row['trans_string'];

		$sql2 = "SELECT `cust_ph_no` FROM `customers` WHERE `cust_id` = '".$row['cust_id']."';";		
		$exe2 = mysqli_query($conn, $sql2);
		$row2 = mysqli_fetch_assoc($exe2);
		$ph_no = $row2['cust_ph_no'];

		sendMSG($car_no_plate, $row['fuel'], $row['amount'], $url, $ph_no);
	}
}

?>