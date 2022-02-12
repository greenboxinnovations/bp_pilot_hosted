<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id']; 

	if (isset($_GET['date1'])) {
		$date1 = $_GET['date1'];
		$date2 = $_GET['date2'];
	}else{
		$date2 = date("Y-m-d");
		$date1 = date('Y-m-d', strtotime("-3 months", strtotime($date2)));
	}

	$sql = "SELECT a.*,b.cust_company,b.cust_f_name,b.cust_m_name,b.cust_l_name,b.cust_post_paid,c.car_no_plate
			FROM `transactions` a 
			JOIN `customers` b ON a.cust_id = b.cust_id
			JOIN `cars` c ON c.car_id = a.car_id
			WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND a.cust_id = '".$cust_id."' AND a.billed = 'Y'   ORDER BY a.trans_id DESC;";	

	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);

	$date1 = date("d-m-Y",strtotime($date1));
	$date2 = date('d-m-Y',strtotime($date2));

	echo'<div style="display:inline-block;margin-right:10px;">From<br>';
		// echo' <input type="date" id="date1" value="'.$date1.'"></input>';
		echo' <input type="text" class="date1_h" value="'.$date1.'"></input>';
	echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;">To<br>';
		// echo'<input type="date" id="date2" value="'.$date2.'"></input>';
		echo' <input type="text" class="date2_h" value="'.$date2.'"></input>';
	echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;"><br>';
		echo'<button id="search" custid="'.$cust_id.'">Search</button>';
	echo'</div>';

	echo'<br/><br/>';

	if ($count > 0) { 

		$invoice_no = null;
			
		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';

		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
				echo '<th>Receipt no</th>';		
				echo '<th>Transaction ID</th>';	
				echo '<th>Plate No</th>';
				echo '<th class="th_num">Rate</th>';
				echo '<th class="th_num">Liters</th>';
				echo '<th class="th_num">Amount</th>';
				echo '<th>Date</th>';
				echo '<th class="th_num">Duration</th>';
				if ($_SESSION['role'] == "admin") {
					echo '<th class="th_num"></th>';
				}
				
			echo '</tr>';
		echo '</thead>';

		echo '<tbody>';

		while($row = mysqli_fetch_assoc($exe)){
			// transaction details
			$trans_id	 	= $row["trans_id"];	
			$trans_id_disp  = $trans_id + 100000;
			$cust_id	 	= $row["cust_id"];
			$car_id	 		= $row["car_id"];
			$liters	 		= $row["liters"];
			$rate	 		= $row["rate"];
			$amount	 		= $row["amount"];
			$date	 		= $row["date"];	
			$cust_post_paid = $row["cust_post_paid"];
			$status 		= $row["billed"];
			$duration 		= $row['trans_time'];
			$trans_string	= $row["trans_string"];	
			
			if ($duration == "") {
				$duration = '00:00';
			}

			$t_date 		= date("Y-m-d", strtotime($date));

			if ($status == 'Y') {

				$sql0 = "SELECT `invoice_no` FROM `invoices` WHERE `cust_id` = '".$cust_id."' AND `from` <= '".$t_date."' AND `to` >= '".$t_date."' ;";
				$exe0 = mysqli_query($conn, $sql0);
				$row0 = mysqli_fetch_assoc($exe0);
				$invoice_no = $row0['invoice_no'];

			}	


			$date = date("M-d, g:i a", strtotime($date));


			// date formatting
			// date_default_timezone_set("Asia/Kolkata");
			// $today 			= date("Y-m-d");
			// $unix_tstamp 	= strtotime($date);	// unix	timestamp
			// $unix_date 		= date("Y-m-d", $unix_tstamp);	// unix	date only
			// // if today show time
			// // else gmail style date-format
			// if($today == $unix_date){
			// 	$display_date = date("g:i a", $unix_tstamp);	// 1.30 am, 11:00 am
			// }
			// else{
			// 	$display_date = date("M d", $unix_tstamp);		// Sep 11, Aug 20
			// }

			$comp_name		 = $row["cust_company"];
			if($comp_name == ""){
				// cust details
				$cust_f_name	 = ucfirst($row["cust_f_name"]);
				$cust_m_name	 = ucfirst($row["cust_m_name"]);
				$cust_l_name	 = ucfirst($row["cust_l_name"]);
				$cust_name 		 = ucwords($cust_f_name.' '.$cust_m_name.' '.$cust_l_name);	
			}
			else{
				$cust_name 		 = ucwords($comp_name);
			}

			

			// car details
			$car_no_plate	 = strtoupper($row["car_no_plate"]);

			if ($row["trans_string"] != NULL) {
				echo '<tr class="highlight view_transaction" transstring="'.$trans_string.'" >';
			}else{
				echo '<tr >';
			}
			
				echo '<td>'.$row["receipt_no"].'</td>';
				echo '<td>'.$trans_id_disp.'</td>';
				echo '<td>'.$car_no_plate.'</td>';
				echo '<td class="td_num">'.$rate.'</td>';
				echo '<td class="td_num">'.$liters.'</td>';
				echo '<td class="td_num">'.$amount.'</td>';
				echo '<td class="td_num">'.$date.'</td>';
				echo '<td class="td_num">'.$duration.'</td>';	
				if ($_SESSION['role'] == "admin") {
					echo '<td class="td_num edit" id="'.$trans_id.'"></td>';	
				}
					
			echo '</tr>';

		}
		echo '<input type="hidden" id="cust_post_paid" value="'.$cust_post_paid.'" >';
		echo '</tbody>';
		echo '</table>';
	}else{

		echo '<div >';
			echo '<span class="c_id">No Transactions present between '.$date1.' To '.$date2.'</span>';
		echo '</div>';
	}

}
?>