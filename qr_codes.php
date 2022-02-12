<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
require 'phpqrcode/qrlib.php';

function generateRand(){

	Global $conn;

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$length = 10;
	$qr_code = '';
	for ($i = 0; $i < $length; $i++) {
		$qr_code .= $characters[rand(0, $charactersLength - 1)];
	}



	$sql = "SELECT 1 FROM `codes` WHERE `qr_code` = '".$qr_code."' ;";
	$exe = mysqli_query($conn ,$sql);

	if(mysqli_num_rows($exe) > 0){
		generateRand();
	}
	else{
		if(($qr_code == "asdfg12345")||($qr_code == "12345asdfg")||($qr_code == "4xzliayQPL")||($qr_code == "8FuAVN303E")){
			generateRand();
		}
		else{
			$sql = "INSERT INTO `codes`(`qr_code`) Values('".$qr_code."' );";
			$exe = mysqli_query($conn ,$sql);
			return $qr_code;	
		}
	}
}


for ($i=0; $i < 200; $i++) { 
// for ($i=0; $i < 1; $i++) { 

	$qr = generateRand();
	// $qr = "abcderftasd";

	$filename= "qr_codes/".$qr.".png";
	// $filename= "test/".$qr.".png";
	QRcode::png($qr,$filename, QR_ECLEVEL_M, 9.0);
	echo '<br/>';
}

?>