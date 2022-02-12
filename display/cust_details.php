<?php
if(!isset($_SESSION))
{
	session_start();
}	
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
	
	if (isset($_GET['cust_id'])) {
		$cust_id = $_GET['cust_id'];

		$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."' ";
		$exe = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($exe)){
			
			$cust_ph_no	 	 = $row["cust_ph_no"];
			$cust_post_paid	 = $row["cust_post_paid"];
			$cust_balance	 = $row["cust_balance"];
			$cust_outstanding= $row["cust_outstanding"];
			$cust_disp_name	 = $row['cust_disp_name'];			
			$cust_disp_name  = ucwords($cust_disp_name);
			$gst			 = $row['cust_gst'];
			$limit			 = $row['cust_credit_limit'];

			$usable			 = $limit - $cust_outstanding;
					
		}

		echo'<div id="name">'.$cust_disp_name.'</div>';



		if ($cust_post_paid == 'Y') {
			echo'<div id="bal">CREDIT LIMIT '.$limit.'</div>';
			echo'<div id="bal">OUTSTANDING '.$cust_outstanding.'</div>';
			echo'<div id="bal">USABLE LIMIT '.$usable.'</div>';
		}else{
			echo'<div id="bal">BALANCE <span>'.$cust_balance.'</span></div>';
		}

		echo '<div id="ph_no">'.$cust_ph_no.'  GST : '.strtoupper($gst).'</div>';

		echo'<input type="hidden" id="cust_post_paid" value="'.$cust_post_paid.'"></input>';



		// echo'<button id="btn_add_payment" custid="'.$cust_id.'">Payment</button>';

	}

?>