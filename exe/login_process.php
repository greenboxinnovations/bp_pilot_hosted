<?php

require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
// require dirname(__FILE__).'/query/conn.php';
// require "/opt/lampp/htdocs/pump_master/exe/query/conn.php";
// require $_SERVER["DOCUMENT_ROOT"].'/pump_master/query/conn.php';

if(!isset($_SESSION))
{
	session_start();
}
	
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

	if (($password == "12345678")&&($user == "demo")) {

		$_SESSION['role'] 		= "demo";
		$json['success'] = true;
		$json['message'] = "Authentication Demo";
		
	}else{
			//get employees salt	

			// echo '<b>bcrypt with complex salt: </b>'.$complex_hash = password_hash($complex_salt.$enc, PASSWORD_DEFAULT, ['cost' => 12]);

			if (($user != '')||($password != '')) {
					$stmt = mysqli_prepare($conn, "SELECT user_id, pass, user_pump_id, role FROM users WHERE name=? LIMIT 1");

					if ($stmt === false) {
						$json['success'] = false;
						$json['message'] = "Authentication Error";

					} else {

						$bind = mysqli_stmt_bind_param($stmt, "s", $user);

						if ($bind === false) {
							$json['success'] = false;
							$json['message'] = "Authentication Error";
						} else {

							mysqli_stmt_execute($stmt);
							mysqli_stmt_store_result($stmt);
							$rows = mysqli_stmt_num_rows($stmt);
							mysqli_stmt_bind_result($stmt,$user_id,$hash,$pump_id,$role);
										
							mysqli_stmt_fetch($stmt);		

		 
							if($rows>0 && $hash == '12345'){
								$json['success'] = false;
								$json['message'] = "reset";
							} else {							

								if ((password_verify($password, $hash)) && ($role != 'operator')) {
									$json['success'] = true;
									$json['message'] = "Authentication Success";

									$token = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);

									$json['token'] 			= $token;
									$_SESSION['token'] 		= $token;
									$_SESSION['pump_id'] 	= $pump_id;
									$_SESSION['user_id'] 	= $user_id;
									$_SESSION['role'] 		= $role;
									
									//create token table
								} else {
									$json['success'] = false;
									$json['message'] = "Authentication Error";
								}			
							}
						}
					}

					//close stmt
				    mysqli_stmt_close($stmt);
			}else{
				//get isnt allowed here
				$json['success'] = false;
				$json['message'] = "Authentication Error/no inputs specified";

			}
	}


	
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