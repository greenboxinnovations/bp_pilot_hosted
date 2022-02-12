<?php

date_default_timezone_set("Asia/Kolkata");

if (isset($_GET['ok'])) {
	require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

	$date = "2019-03-31 12:00:00";
	$new_out 	 = 0;

	$sql = "SELECT * FROM `invoices` WHERE `status` = 'Y'  AND date(`date`) <= '2019-03-31' ORDER BY `invoice_no` ASC;";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){

			$cust_id = $row['cust_id'];
			echo $invoice_no = $row['invoice_no'];
			echo '<br/>';

			if ($invoice_no == 640) {
				# code...
			}else{
				$invoice_amount = $row['amount'];
				$pump_id = $row['pump_id'];


				$amount_paid = $invoice_amount;
				$prev_out    = $invoice_amount;



				$sql1 = "SELECT `cust_post_paid` FROM `customers` WHERE `cust_id` = ".$cust_id." ;";
				$exe1 = mysqli_query($conn, $sql1);
				$row1 = mysqli_fetch_assoc($exe1);

				$is_postpaid = $row1['cust_post_paid'];


				echo $sql2 = "INSERT INTO `payments`(`cust_id`, `pump_id`, `prev_out`, `amount_paid`, `new_out`, `is_postpaid`,  `invoice_no`, `invoice_amount`, `date`, `last_updated`) VALUES ('".$cust_id."','".$pump_id."','".$prev_out."','".$amount_paid."','".$new_out."','".$is_postpaid."','".$invoice_no."','".$invoice_amount."','".$date."','".$date."');";
				$exe2 = mysqli_query($conn, $sql2);
			}

			


	}

}
?>