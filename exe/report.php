<?php

date_default_timezone_set("Asia/Kolkata");

$cust_id = $_GET['cust_id'];
$date = date("Y-m-d");

$date1 = $_GET['date1']	;
$date2 = $_GET['date2']	;
$type = $_GET['type'];
$pump_id = $_GET['pump_id'];

$invoice_no = $_GET['invoice_no'];

$late_fee = 0;
if (isset($_GET['late_fee'])) {
	$late_fee = $_GET['late_fee'];
}


function url(){
	return sprintf(
		"%s://%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME']
	);
}


if ($type == 'new') {

	$date_invoice = $_GET['date_invoice'];

	$html_file_url = url()."/bill.php?cust_id=".$cust_id."&date1=".$date1."&date2=".$date2."&type=".$type."&date_invoice=".$date_invoice."&late_fee=".$late_fee;

	$pdf_file_url = '../reports/'.$pump_id.'/Invoice-'.$invoice_no.'.pdf'; // pdf file 

	if (!file_exists($pdf_file_url)) {  

		//linux
		$cmd = "../wkhtmltopdf/bin/wkhtmltopdf --page-size A4 --enable-smart-shrinking \"".$html_file_url."\" \"".$pdf_file_url."\" 2>&1 > output.log";// command

		//windows
		// $cmd = "C:/wkhtmltopdf/bin/wkhtmltopdf.exe --page-size A4 --enable-smart-shrinking \"".$html_file_url."\" \"".$pdf_file_url."\" 2>&1";// command

		exec($cmd); // execute command from php

		$pdf = file_get_contents($pdf_file_url);

		header('Content-Type: application/pdf');
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Length: '.strlen($pdf));
		header('Content-Disposition: inline; filename="'.basename($pdf_file_url).'";');
		ob_clean(); 
		flush(); 
		echo $pdf;
	}
	else{
		echo 'exists';
	}

}
else{


	$pdf_file_url = "../reports/".$pump_id."/Invoice-".$invoice_no.".pdf"; // pdf file 
	$pdf = file_get_contents($pdf_file_url);

	header('Content-Type: application/pdf');
	header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
	header('Pragma: public');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Content-Length: '.strlen($pdf));
	header('Content-Disposition: inline; filename="'.basename($pdf_file_url).'";');
	ob_clean(); 
	flush(); 
	echo $pdf;

}


?>