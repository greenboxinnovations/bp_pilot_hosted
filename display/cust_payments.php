<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT a.* , b.payment_balance
			FROM `payments` a
			JOIN `customers` b ON a.cust_id = b.cust_id
			WHERE a.cust_id ='".$cust_id."' ";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);
	if ($count > 0) {


		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';
		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
				echo '<th>#</th>';
				echo '<th>Date</th>';
				echo '<th>Invoice No</th>';
				echo '<th>Invoice Amount</th>';
				echo '<th>Prev Bal/Out</th>';
				echo '<th>Amount Paid</th>';
				echo '<th>New Bal/Out</th>';
				// echo '<th>Prev Payment Balance</th>';
			echo '</tr>';
		echo '</thead>';


		echo '<tbody>';
		$i=1;
		while($row = mysqli_fetch_assoc($exe)){

			
			$prev	 = $row["prev_out"];
			$new = $row["new_out"];
			
			$cust_id	 = $row["cust_id"];
			$invoice_no	 = $row["invoice_no"];
			$invoice_amt = $row["invoice_amount"];
			$amount_paid = $row["amount_paid"];
			$prev_payment= $row["payment_balance"];
			$date	 = $row["date"];
			$date = date("M-d, g:i a", strtotime($date));

			echo '<tr class="highlight view_comment" comment ="'.$row['comment'].'">';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td style="text-align:right"><a target="_blank" href="reports/Invoice-'.$invoice_no.'.pdf">'.$invoice_no.'</a></td>';
				echo '<td style="text-align:right">'.$invoice_amt.'</td>';
				echo '<td style="text-align:right">'.$prev.'</td>';
				echo '<td style="text-align:right">'.$amount_paid.'</td>';
				echo '<td style="text-align:right">'.$new.'</td>';
				// echo '<td style="text-align:right">'.$prev_payment.'</td>';
			echo '</tr>';
			$i++;
		}
		echo '</tbody>';
		echo '</table>';
	}else{
		echo 'No Payments Found';
	}
	
}
?>