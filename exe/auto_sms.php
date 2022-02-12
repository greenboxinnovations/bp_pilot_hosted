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

	$message = "<#> SampleApp: Your verification code is 143567".$newline."Te0m+Q4I4AL";
	$encodedMessage = urlencode($message);

	$api = Globals::msgString($encodedMessage, $phone_no, true);

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

// $newline = "\n";
// $message = "<#> SampleApp: Your verification code is 143567".$newline."Te0m+Q4I4AL";
// echo '<pre>';
// echo $message;
// echo '</pre>';

sendMSG("8411815106");




?>
