<?php
date_default_timezone_set("Asia/Kolkata");
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
ini_set('error_log', 'receive_photos.log');
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
set_error_handler('myErrorHandler');

if (isset($_POST) ){
	$output = array();

	for ($i=0; $i < sizeof($_FILES['file']['name']); $i++) { 

		$dir = $_POST['path'][$i];
		$pump_id = $_POST['pump_id'][$i];


		trigger_error($dir);

		$dir = str_replace('/opt/lampp/htdocs/middleware/', '', $dir);

		trigger_error($_FILES['file']['name'][$i]);

		if (!file_exists($dir.'/'.$pump_id)) {
			mkdir($dir.'/'.$pump_id, 0777,true);
		}

		move_uploaded_file($_FILES['file']['tmp_name'][$i], $dir.'/'.$pump_id.'/'.$_FILES['file']['name'][$i]);

		array_push($output, $dir."/".$_FILES['file']['name'][$i]);

	}
	echo json_encode($output);
}

?>