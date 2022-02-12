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
ini_set('error_log', 'receive_videos.log');
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
set_error_handler('myErrorHandler');


if (isset($_POST) ){

	$output = array();
	$output['success'] = false;
	$output['error'] = 0;
	$trans_string = "";

	try {		

		$date = $_POST['date'];
		$pump_id = $_POST['pump_id'];		
		$dir  = 'videos/'.$date.'/'.$pump_id;

		if (!file_exists($dir)) {
			mkdir($dir, 0755, true);
		}

		if(move_uploaded_file($_FILES['file']['tmp_name'], $dir.'/'.$_FILES['file']['name'])){
			$output['success'] = true;
			$trans_string =  basename($_FILES['file']['name'], ".mp4");
			trigger_error($trans_string);
		}else{
			trigger_error('Move upload failed'.$_FILES["file"]["error"]);
			$output['error'] = $_FILES["file"]["error"];
		}			
		
	} catch (Exception $e) {

		$output['success'] = false;
		trigger_error($e);
	}

	$output['trans_string']= $trans_string;
	$output['date']= $date;
	$output['file_name']= $_FILES['file']['tmp_name'];

	echo json_encode($output,JSON_NUMERIC_CHECK);

}

?>