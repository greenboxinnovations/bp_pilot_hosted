<?php
date_default_timezone_set("Asia/Kolkata");
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$rnum = addslashes($obj['rnum']);

$json = array();

if($rnum != ""){

	// details from CUSTOMERS
	$sql = "SELECT * FROM `transactions` WHERE `receipt_no` = '".$rnum."';";
	$exe = mysqli_query($conn ,$sql);

	if (mysqli_num_rows($exe) > 0) {
		$json['success'] = false;
		$json['msg'] = 'Receipt already used';	
	}	
	else{
		// no matches in cars db
		$json['success'] = true;
	}		
}
else{
	// QR is empty
	$json['success'] = false;
	$json['msg'] = "Empty R-Num";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>