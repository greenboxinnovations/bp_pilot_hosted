<?php

require __DIR__.'/query/conn.php';

$local_install_dir = Globals::LOCAL_INSTALL_DIR;

function myErrorHandler( $errType, $errStr, $errFile, $errLine, $errContext ) {
	$displayErrors 	= ini_get( 'display_errors' );
	$logErrors 		= ini_get( 'log_errors' );
	$errorLog 		= ini_get( 'error_log' );

	// if( $displayErrors ) echo $errStr.PHP_EOL;
	if( $logErrors ) {
		$message = sprintf('[%s] - (%s, %s) - %s ', date('Y-m-d H:i:s'), $errFile, $errLine ,$errStr);
		file_put_contents( $errorLog, $message.PHP_EOL, FILE_APPEND );
	}
}

ini_set('log_errors', 1);
ini_set('error_log', $local_install_dir.'sync_device_health.log');
error_reporting(E_ALL);

set_error_handler('myErrorHandler');

$url_main = Globals::URL_SYNC_CHECK;
$data = array();

$sql = "SELECT *  FROM `device_status` WHERE  1;";
$exe = mysqli_query($conn,$sql);
$count = mysqli_num_rows($exe);

if ($count > 0) {

	while($row = mysqli_fetch_assoc($exe)){
		array_push($data,$row);
	}

	$data_json = json_encode($output);
	$url = $url_main."/exe/set_device_health.php";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($http_code == 200) {
		try {
			$json = json_decode($response, true);
			print_r($response);
			if($json['success']){
				echo "working";
			}
			else{
				echo 'debug';
			}
		} catch (Exception $e) {
			trigger_error('Test');
		}
			
	}
	else{
		trigger_error('Test'.$http_code);
	}
	curl_close($ch);
}
else{
	echo 'No Data';
}
?>