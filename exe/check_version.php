<?php

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
date_default_timezone_set("Asia/Kolkata");

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$name 		= addslashes($obj['name']);
$pump_id 	= addslashes($obj['pump_id']);

$json = array();

$json['version'] = 0;

$sql = "SELECT `value` FROM `versions` WHERE `name` = '".$name."' AND `pump_id` = '".$pump_id."' ;";
$exe = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($exe)){
	$json['version'] = $row['value'];
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?> 