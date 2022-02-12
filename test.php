<?php
 date_default_timezone_set("Asia/Kolkata");
		$timestamp = date("d/m/Y H:i:s");
		echo "Formatted date from timestamp:" . $timestamp;

		$newline = "\n";

		$message ="MHKS Mohd. Ali
                    Fuel Summary MH31MH0001 19438.00 200.00 ltr Diesel 05/10/2021 12:59:42 Click: http://fuelcam.in/cmsg.php?t=P2xvfd6QvwUv";
		//$message = $url;
		$encodedMessage = urlencode($message);

// 		$api = Globals::msgString($encodedMessage,$phone_no, false);

		// $api_new = "http://sumit.bulksmsnagpur.net/sendsms?uname=mhksfr&pwd=mhksfr&senderid=MHKSFR&to=".$phone_no."&msg=".$encodedMessage."&route=T"; 

	$api_new = 'http://sumit.bulksmsnagpur.net/sendsms?uname=mhksfr&pwd=mhksfr&senderid=MHKSFR&to=9762230207&msg='.$encodedMessage.'&route=T'; 
        
        echo $api_new;

	    // Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $api_new,
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
?>
