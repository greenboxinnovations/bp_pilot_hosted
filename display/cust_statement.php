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

	$date11 = date("d-m-Y",strtotime($date1));
	$date22 = date('d-m-Y',strtotime($date2));

	echo'<div style="display:inline-block;margin-right:10px;">From<br>';
		echo' <input type="text" class="date1_s" value="'.$date11.'"></input>';
	echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;">To<br>';
		echo' <input type="text" class="date2_s" value="'.$date22.'"></input>';
	echo'</div>';

	echo'<div style="display:inline-block;margin-right:10px;"><br>';
		echo'<button id="search_s" custid="'.$cust_id.'">Search</button>';
	echo'</div>';

	echo'<br/><br/>';


	$sql = "SELECT `invoice_no`,`amount`,`date` FROM `invoices`	WHERE date(`date`) BETWEEN '".$date1."' AND '".$date2."' AND `cust_id` = '".$cust_id."' AND `status` = 'Y' ORDER BY `date` DESC;";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);

	$total = 0;

	if ($count > 0) {

		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';

		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
						
				echo '<th>Invoice NO</th>';	
				echo '<th>Amount</th>';
				echo '<th>Amount Paid</th>';
				echo '<th>Amount Remaining</th>';
				echo '<th>Date</th>';
			echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
		
		while ($row = mysqli_fetch_assoc($exe)) {
			
			$invoice_no = $row["invoice_no"];
			$amount 	= $row["amount"];
			$date 		= $row["date"];

			echo '<tr >';
				echo '<td>'.$invoice_no.'</td>';
				echo '<td class="right">'.money_format('%!.0n', $amount).'</td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td>'.date("M-d",strtotime($date)).'</td>';
				
			echo '</tr>';

		
			
			$sql2 = "SELECT `amount_paid`,`date` FROM `payments` WHERE `invoice_no` = '".$invoice_no."'  ORDER BY `date` ASC;";	
			$exe2 = mysqli_query($conn, $sql2);
			$count2 = mysqli_num_rows($exe2);
			if ($count2 > 0) {
				while ($row2 = mysqli_fetch_assoc($exe2)) {
		
					$amount_paid 	= $row2["amount_paid"];
					$date_p			= $row2["date"];
					$remaining		= $amount - $amount_paid;
					$total 			= $total +	$remaining;

					echo '<tr >';
						echo '<td>'.$invoice_no.'</td>';
						echo '<td class="right">P</td>';
						echo '<td class="right">'.money_format('%!.0n', $amount_paid).'</td>';
						echo '<td class="right">'.money_format('%!.0n', $remaining).'</td>';
						echo '<td>'.date("M-d",strtotime($date_p)).'</td>';
					echo '</tr>';
				}
			}else{
				$total 			= $total +	$amount;
			}
			
		}

		$total = money_format('%!.0n', $total);

		echo '<tr >';
			echo '<td colspan="4" class="right">Total Receiveable : '.$total.'</td>';
			echo '<td></td>';
		echo '</tr>';

		echo '</tbody>';
		echo '</table>';
	}
	
	else{

		echo '<div >';
			echo '<span class="c_id">No Transactions present between '.$date1.' To '.$date2.'</span>';
		echo '</div>';
	}
}

?>