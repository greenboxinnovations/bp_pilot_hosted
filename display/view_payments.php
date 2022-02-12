<?php
if(!isset($_SESSION))
{
	session_start();
}
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$month = $_GET['month']; 
$year  = $_GET['year']; 
$pump_id = $_SESSION['pump_id'];

$date_array = array();


if (($month == 0) || ($year == 0)) {
	$month = date("m"); 
	$year  = date("Y");
}

function grandTotal($data){

	$grandTotal = 0;
	$grandReceivable = 0;
	$received =0;
	global $date_array;

	for ($i=0; $i < sizeof($data) ; $i++) { 

		$amount_paid	= $data[$i]["paid"];
		
		$invoice_amount	= $data[$i]["amount"];

		$grandReceivable += $invoice_amount - $amount_paid;
		$grandTotal      += $invoice_amount;
		$received        += $amount_paid;

		$invoice_date	= $data[$i]["invoice_date"];

		array_push($date_array, $invoice_date);
	}

	$grandTotal = money_format('%!.0n', $grandTotal);
	// echo '<div id="grand_total">Total: '.$grandTotal.'</div>';

	

	$received = money_format('%!.0n', $received);
	// echo '<div id="grand_total">Received: '.$received.'</div>';

	$grandReceivable = money_format('%!.0n', $grandReceivable);
	// echo '<div id="grand_total">Balance: '.$grandReceivable.'</div>';


	echo '<table style="border:none;" id="total_table">
		<tr><td>Total</td><td style="text-align:right;">'.$grandTotal.'</td>
		<td>Received</td><td style="text-align:right;">'.$received.'</td>
		<td>Balance</td><td style="text-align:right;">'.$grandReceivable.'</td></tr>
	</table>';

}

function renderTable($data){

	$prev_name = NULL; 
	global $month;
	global $year;
	global $conn;

	global $date_array;

	echo'<table>';

		echo '<tr>';
			echo '<th>#</th>';
			echo '<th>Customer Name</th>';
			echo '<th>Invoice Range</th>';
			echo '<th>Invoice No</th>';
			echo '<th class="invoice_amount">Invoice Amount</th>';
			echo '<th class="amount_paid">Paid</th>';
			echo '<th colspan="2" class="invoice_pending">Remaining</th>';

			echo '<th></th>';
			echo '<th></th>';
		echo '</tr>'; 
	$j 					= 0; // Sr no for display
	$display 			= false;
	$old_amount			= 0;  
	$total_r 		    = 0;
	$prev_balance 		= 0;
	$first = false;

	for ($i=0; $i < sizeof($data) ; $i++) { 
		
		$show 			= true;
		$invoice_pending 	= 0;

		$cust_id	 	= $data[$i]["cust_id"];
		$cust_disp_name	= $data[$i]["cust_disp_name"];
		$cust_ph_no     = $data[$i]["cust_ph_no"];
		$amount_paid	= $data[$i]["paid"];
		$invoice_no		= $data[$i]["invoice_no"];
		$from			= $data[$i]["from"];
		$to				= $data[$i]["to"];
		$invoice_amount	= $data[$i]["amount"];
		
		$invoice_pending = $invoice_amount - $amount_paid;

		$total_r += $invoice_pending;

		if ($amount_paid == "") {
			$amount_paid = 0;
		}

		$from = date('d/m',strtotime($from));
		$to   = date('d/m',strtotime($to));

		//New customer name show only once
		if (($prev_name == NULL)||($prev_name != $cust_disp_name)) {
			$prev_name = $cust_disp_name;
			$display = true;
			
			$j++;
		}else{
			
			$display = false;
		}

		if ($display) {
			 $sql1 = "SELECT a.new_out,date(b.date) as invoice_date
				FROM `payments` a 
				JOIN `invoices` b 
				ON a.invoice_no = b.invoice_no
				WHERE a.cust_id = ".$cust_id." AND a.payment_id IN (SELECT MAX(`payment_id`) FROM `payments` WHERE `cust_id` = ".$cust_id." GROUP BY `invoice_no`) ;";

			$exe1 = mysqli_query($conn, $sql1);
			$count1 = mysqli_num_rows($exe1);
			if ($count1 > 0) {
				while ($row1 = mysqli_fetch_assoc($exe1)) {
					if (!in_array($row1['invoice_date'], $date_array)) {
						$prev_balance =  $prev_balance + $row1['new_out'];	
					}		
				}
			}else{
				$prev_balance 	= 0;
			}

			$total_r = $total_r + $prev_balance;
		}

		//multiple invoice for one customer withim same billing month reset display variables
		if (($old_amount == 0)||($old_amount != $invoice_amount)) {			
			
			$old_amount 		= $invoice_amount;	
			if (isset($data[$i+1])){
				if (($invoice_no != $data[$i+1]["invoice_no"])&&($cust_id == $data[$i+1]["cust_id"])) {
					if (!$first) {
						$first = true;
						// $invoice_pending = $invoice_pending + $new_out;
					}
				}
			}
			
		}else{
			$show = false;
		}


		echo ($j  % 2 == 0 ?  '<tr class="odd invoice" invoiceno="'.$invoice_no.'">': '<tr class ="even invoice" invoiceno="'.$invoice_no.'">');
		
			if ($display) {
				echo '<td>'.$j.'</td>';
				echo '<td class="cust_disp_name"><a target="_blank" href="customer_details.php?cust_id='.$cust_id.'" >'.$cust_disp_name.'</a></td>';

			}else{
				echo '<td></td>';
				echo '<td></td>';
			}

			if ($show) {
				echo '<td class="date_range">'.$from.' - '.$to.'</td>';
				echo '<td class="invoice_no"><a target="_blank" href="reports/Invoice-'.$invoice_no.'.pdf">'.$invoice_no.'</a></td>';
				echo '<td class="invoice_amount">'.money_format('%!.0n',$invoice_amount).'</td>';	
				$first = false;
			}else{
				echo '<td></td>';
				echo '<td></td>';
				echo '<td></td>';
			}			

			echo '<td class="amount_paid">'.money_format('%!.0n',$amount_paid).'</td>';
			echo '<td></td>';
			echo '<td class="invoice_pending">'.money_format('%!.0n',$invoice_pending).'</td>';	

			if ($invoice_pending > 0) {
				echo '<td class="new_pay" invoiceno="'.$invoice_no.'" invoiceamount="'.$invoice_amount.'" custid="'.$cust_id.'"></td>';	
				echo '<td class="new_sms" totalr="'.$total_r.'" custid="'.$cust_id.'"></td>';
			}else{
				echo '<td></td>';
				echo '<td></td>';
			}
			
			
		echo '</tr>';


		//if next row is of new customer and not new invoice no
		if (isset($data[$i+1])){
			// if (($invoice_no != $data[$i+1]["invoice_no"])&&($cust_id != $data[$i+1]["cust_id"]) &&(($total_r  > 0)||($prev_balance > 0))) {
			if (($invoice_no != $data[$i+1]["invoice_no"])&&($cust_id != $data[$i+1]["cust_id"]) ) {	

				if($j  % 2 == 0){
					echo '  <tr class="odd invoice">';
								if ($prev_balance == 0) {
									echo'<td></td>';
									echo'<td colspan="5">'.$cust_ph_no.'</td>';
								}else{
									echo'<td></td>';
									echo'<td colspan="3">'.$cust_ph_no.'</td>';
									echo'<td class="prev_balance">Previous Balance</td>';
									echo'<td class="prev_b_val">'.$prev_balance.'</td>';
								}							
								echo'<td class="total_text">Receivable </td>
								<td class="total_r">'.money_format('%!.0n',$total_r).'</td>
								<td></td><td></td>
							</tr>';
				}
				else{
					echo '  <tr class="even invoice">';
								if ($prev_balance == 0) {
									echo'<td></td>';
									echo'<td colspan="5">'.$cust_ph_no.'</td>';
								}else{
									echo'<td></td>';
									echo'<td colspan="3">'.$cust_ph_no.'</td>';
									echo'<td class="prev_balance">Previous Balance</td>';
									echo'<td class="prev_b_val">'.$prev_balance.'</td>';
								}							
								echo'<td class="total_text">Receivable </td>
								<td class="total_r">'.money_format('%!.0n',$total_r).'</td>
								<td></td><td></td>
							</tr>';
				}
				$total_r = 0;	
				$prev_balance = 0;
			}
		}
		//last row display 
		else{
				if($j  % 2 == 0){
					echo '  <tr class="odd invoice">';
								if ($prev_balance == 0) {
									echo'<td></td>';
									echo'<td colspan="5">'.$cust_ph_no.'</td>';
								}else{
									echo'<td></td>';
									echo'<td colspan="3">'.$cust_ph_no.'</td>';
									echo'<td class="prev_balance">Previous Balance</td>';
									echo'<td class="prev_b_val">'.$prev_balance.'</td>';
								}							
								echo'<td class="total_text">Receivable </td>
								<td class="total_r">'.money_format('%!.0n',$total_r).'</td>
								<td></td><td></td>
							</tr>';
				}
				else{
					echo '  <tr class="even invoice">';
								if ($prev_balance == 0) {
									echo'<td></td>';
									echo'<td colspan="5">'.$cust_ph_no.'</td>';
								}else{
									echo'<td></td>';
									echo'<td colspan="3">'.$cust_ph_no.'</td>';
									echo'<td class="prev_balance">Previous Balance</td>';
									echo'<td class="prev_b_val">'.$prev_balance.'</td>';
								}							
								echo'<td class="total_text">Receivable </td>
								<td class="total_r">'.money_format('%!.0n',$total_r).'</td>
								<td></td><td></td>
							</tr>';
				}
				$total_r = 0;	
				$prev_balance = 0;		
		}
		
	}	
	echo '</table>';
}

$sql = "SELECT a.cust_id,a.cust_disp_name,a.cust_ph_no,b.invoice_no,b.amount,b.from,b.to,date(b.date) as invoice_date, (SELECT SUM(`amount_paid`) FROM `payments` WHERE `invoice_no` = b.invoice_no) as paid 
		FROM `customers` a 
		JOIN `invoices` b ON b.cust_id = a.cust_id 
		WHERE `pump_id` = '".$pump_id."' AND '".$month."' = (SELECT MONTH(b.date)) AND b.status = 'Y' AND '".$year."' = (SELECT YEAR(b.date)) 
		ORDER BY a.cust_disp_name,b.invoice_no ASC";

$data = array();

$exe = mysqli_query($conn, $sql);
$count = mysqli_num_rows($exe);
if ($count > 0) {

	while($row = mysqli_fetch_assoc($exe)){
		array_push($data,$row);
	}

	grandTotal($data);
	renderTable($data);
}
else{
	echo '<div>No Customers Invoiced.</div>';
}

?>