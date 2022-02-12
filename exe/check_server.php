<?php

// require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
require dirname(__DIR__,1).'/query/conn.php';


// echo date("Y-m-d H:i:s");


$now = new Datetime("now");
$begintime = new DateTime('01:00');
$endtime = new DateTime('03:45');

if($now >= $begintime && $now <= $endtime){
    // between times
    $sql = "SELECT `last_updated` FROM `sync` WHERE `table_name` = 'local_server';";
	$exe = mysqli_query($conn, $sql);

	if(mysqli_num_rows($exe) > 0){


		$r = mysqli_fetch_assoc($exe);

		$unix = strtotime(date("Y-m-d H:i:s"));	
		$last_updated = $r["last_updated"];


		$last_sync	 		= ($unix - $last_updated);	


		// 15 min
		if($last_sync > 900){


			$title = "Server Down";
			$message = "From Cron";
			$json_data = json_encode(array( "to" => "/topics/weather",
											"data" => array("title" => $title, 
															"message" => $message)));



			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $json_data,
				CURLOPT_HTTPHEADER => array(
					"Accept: */*",
					"Accept-Encoding: gzip, deflate",		
					"Authorization: key=AAAASovyhrg:APA91bHMAD2X0uNzJ_ppkY7GS2M7IgVmZbaXJHvECfa86OfOml1KB5FXR5D35tjQ7lIg3Fs0imk9kzlpsI7w1UbFWUHTJjs-cNdYdkvcnZ8JnMY02cg_bBnNo5VWkkkO6TnIeWS0AXGv",
					"Cache-Control: no-cache",
					"Connection: keep-alive",
					"Content-Length: ".strlen($json_data),
					"Content-Type: application/json",
					"Host: fcm.googleapis.com",		
					"cache-control: no-cache"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				echo $response;
			}
		}	

	}
}


	


?>