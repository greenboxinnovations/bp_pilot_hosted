<?php

if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
//request method
$method = $_SERVER['REQUEST_METHOD'];
//response array
$json = array();

if($method == 'POST'){
	//get inputs
	$postParams = json_decode(file_get_contents("php://input"),true);							
	//user
	$user = $postParams["name"];
	//password
	$password = $postParams["password"];

	$salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);

	$complex_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

	$stmt = mysqli_prepare($conn, "UPDATE `users` SET `pass` = ? WHERE name=? LIMIT 1");

	if ($stmt === false) {
		$json['success'] = false;
		$json['message'] = "Password Change Error";

	} else {

		$bind = mysqli_stmt_bind_param($stmt,"ss", $complex_hash,$user);

		if ($bind === false) {
			$json['success'] = false;
			$json['message'] = "Password Change Error";
		} else {

			mysqli_stmt_execute($stmt);
			
			$json['success'] = true;
			$json['message'] = "Authentication Success";
			$token = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
			$json['token'] = $token;	
			$_SESSION['token'] = $token;	
		}
	}
	//close stmt
    mysqli_stmt_close($stmt);
} else {
	//get isnt allowed here
	$json['success'] = false;
	$json['message'] = "Authentication Error";	
}
//send response
echo json_encode($json);
//close conn
mysqli_close($conn);


?>