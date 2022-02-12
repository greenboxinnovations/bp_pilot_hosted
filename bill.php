<?php
require __DIR__.'/query/conn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Title</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// alert('working');
		});
	</script>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
		@font-face {
		    font-family: OpenSans;
		    src: url('css/fonts/OpenSans-Regular.ttf');
		}

		*{padding: 0;margin: 0;}
		body{font-family: OpenSans,sans-serif;z-index: 0;}	

		#wrapper{width: 800px;/*background-color: yellow;*/margin: 0 auto;}
		#pump_name{text-align: center;font-weight: 700;font-size: 30px;}
		#pump_add{text-align: center;}
		#pump_gst{text-align: center;}
		#pump_phno{text-align: center;padding-bottom: 30px;border-bottom: 1px solid rgb(200,200,200);margin-bottom: 40px;}


		#float_name{width: 50%;float: left;margin-bottom: 50px;}
		#float_bill_date{width: 50%;float: right;margin-bottom: 50px;text-align: right;}

		#cust_name{font-weight: 700;font-size: 30px;}
		#cust_add{width: 250px;}

		table{border-collapse: collapse;margin: 10px auto;width: 95%;border: 1px solid black;}
		td,th{border: 1px solid rgb(200,200,200);padding: 5px;}
		th{text-align: left;}
		td.td_num{text-align: right;}

		/*td.g{border: 1px solid white;border-right: 1px solid rgb(200,200,200);}*/
		.inline{display: inline-block;vertical-align: top;width: 100px;text-align: left;}
		.inline_num{width: 120px;}


	</style>
</head>
<body>


<div id="wrapper">
<?php
if(isset($_GET['cust_id'])){

	$cust_id = $_GET['cust_id'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];
	$type = $_GET['type'];
	$date_invoice = $_GET['date_invoice'];
	$late_fee =0;
	if (isset($_GET['late_fee'])) {
		$late_fee = $_GET['late_fee'];
	}
	

	$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."';";
	$exe = mysqli_query($conn, $sql);

	if(mysqli_num_rows($exe) == 1){
		$row = mysqli_fetch_assoc($exe);

		$cust_company	 = $row["cust_company"];
		$cust_gst	 	 = $row["cust_gst"];
		$pump_id	 	 = $row["cust_pump_id"];
		$cust_service	 = $row["cust_service"];
		$cust_post_paid  = $row["cust_post_paid"];

	
		$cust_full_name  = ucwords( $row["cust_disp_name"]);
		if ($cust_gst != NULL) {
			$cust_address	= strtoupper('GST: '.$cust_gst);
		}else{
			$cust_address	= "";
		}
		
		$date = date("Y-m-d");
		$date = date('d-m-Y', strtotime($date));

		$sql3 = "SELECT `invoice_no`,date(`date`) as date1 FROM `invoices` WHERE `pump_id` = '".$pump_id."' ORDER BY `in_id` DESC LIMIT 1 ;";
		$exe3 = mysqli_query($conn, $sql3);
		$row3 = mysqli_fetch_assoc($exe3);
		if (($row3['invoice_no'] < 1)||($row3['invoice_no'] == "")) {
			$invoice_no =1;
		}else{
			$invoice_no = $row3['invoice_no'] +1;
		}

		if ($type != 'new') {
			$invoice_no = $type;
			$date = date('d-m-Y', strtotime($row3['date1']));
			$sql = "SELECT a.trans_id,a.receipt_no,a.amount, a.rate, a.liters, a.date, b.car_no_plate, b.car_fuel_type
			FROM `transactions` a
			JOIN `cars` b
			ON a.car_id = b.car_id
			WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND a.cust_id = '".$cust_id."' AND a.billed = 'Y' ORDER BY date(a.date) ASC;";

			$sql9 = "SELECT  date(`date`) as d FROM `invoices` WHERE `cust_id` = '".$cust_id."' AND `from` =  '".$date1."' AND `to`= '".$date2."';";
			$exe9 = mysqli_query($conn, $sql9);
			$row9 = mysqli_fetch_assoc($exe9);
			$date_invoice = $row9['d'];

		}else{
			$sql = "SELECT a.trans_id,a.receipt_no,a.amount, a.rate, a.liters, a.date, b.car_no_plate, b.car_fuel_type
			FROM `transactions` a
			JOIN `cars` b
			ON a.car_id = b.car_id
			WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND a.cust_id = '".$cust_id."' AND a.billed = 'N' ORDER BY date(a.date) ASC;";

			
		}

		$exe = mysqli_query($conn, $sql);

		

		
		$sql2 = "SELECT * FROM `pumps` WHERE `pump_id` = '".$pump_id."';";
		$exe2 = mysqli_query($conn, $sql2);
		if(mysqli_num_rows($exe2) == 1){
			$row2 = mysqli_fetch_assoc($exe2);
			$pump_name	 	= ucwords($row2["pump_name"]);
			$pump_address	= ucwords($row2["pump_address"]);
			$pump_gst	 	= strtoupper($row2["pump_gst"]);


			echo '<div id="pump_name">'.$pump_name.'</div>';
			echo '<div id="pump_add">'.$pump_address.'</div>';
			echo '<div id="pump_gst">GST No '.$pump_gst.'</div>';
			echo '<div id="pump_phno">Ph No: 020-25433867</div>';
		}



		echo '<div id="float_name">';
			echo '<div id="cust_name">'.$cust_full_name.'</div>';
			echo '<div id="cust_add">'.ucwords($cust_address).'</div>';
		echo '</div>';
		echo '<div id="float_bill_date">';
			echo '<div style="font-weight: 600;">';
				echo '<div class="inline">BILL NO</div>';
				echo '<div class="inline inline_num">: '.$invoice_no.'</div>';
			echo '</div>';
			echo '<div style="font-weight: 600;">';
				echo '<div class="inline">BILL DATE</div>';
				echo '<div class="inline inline_num">: '.$date_invoice.'</div>';
			echo '</div>';
			echo '<div>';
				echo '<div class="inline">BILL FROM</div>';
				echo '<div class="inline inline_num">: '.date('d-m-Y',strtotime($date1)).'</div>';
			echo '</div>';
			echo '<div>';
				echo '<div class="inline">UPTO</div>';
				echo '<div class="inline inline_num">: '.date('d-m-Y',strtotime($date2)).'</div>';
			echo '</div>';
		echo '</div>';
	}
	

	
	// table
	


	$total 	= 0;

	if(mysqli_num_rows($exe) > 0){


		echo '<table>';
		echo '<tr>';
			echo '<th>Receipt no</th>';
			echo '<th>Trans ID</th>';
			echo '<th style="text-align:center;">Date</th>';
			echo '<th style="text-align:center;">Vehicle No</th>';
			echo '<th style="text-align:center;">Fuel Type</th>';				
			echo '<th style="text-align:center;">Litres</th>';
			echo '<th style="text-align:center;">Fuel Rate</th>';
			echo '<th style="text-align:center;">Amount Rs</th>';
		echo '</tr>';

		$count 	= 1;
		$hsd = 0;
		$ms = 0;
		
		$hsd_lit = 0;
		$ms_lit =0;

		while($row = mysqli_fetch_assoc($exe)){

			$amount	 = $row["amount"];
			$rate	 = $row["rate"];
			$liters	 = $row["liters"];
			$date	 = date('d-m-Y', strtotime($row["date"]));
			$car_no	 = strtoupper($row["car_no_plate"]);
			$fuel	 = ucwords($row["car_fuel_type"]);

			$trans_id_disp = $row["trans_id"] + 100000;

			if ($count == 1) {
				$date1 =  date('Y-m-d', strtotime($row["date"]));
			}

			$date2 =  date('Y-m-d', strtotime($row["date"]));

			if($fuel == 'Diesel'){
				$hsd += $amount;
				$hsd_lit += $liters;
			}
			else{
				$ms += $amount;
				$ms_lit += $liters;
			}

			echo '<tr>';
				echo '<td>'.$row["receipt_no"].'</td>';
				echo '<td>'.$trans_id_disp.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td>'.$car_no.'</td>';				
				echo '<td>'.$fuel.'</td>';
				echo '<td class="td_num">'.$liters.'</td>';
				echo '<td class="td_num">'.$rate.'</td>';
				echo '<td class="td_num">'.$amount.'</td>';				
			echo '</tr>';

			$total += $amount;
			++$count;
		}

		$service = round($total*($cust_service/100),2);


		echo '<tr style="border-top:2px solid rgb(170,170,170);"><td colspan="5" class="td_num g">HSD</td><td class="td_num">'.$hsd_lit.'</td><td></td><td class="td_num">'.$hsd.'</td></tr>';
		echo '<tr style="border-bottom:2px solid rgb(170,170,170);"><td colspan="5" class="td_num g">MS</td><td class="td_num">'.$ms_lit.'</td><td></td><td class="td_num">'.$ms.'</td></tr>';


		echo '<tr><td colspan="7" class="td_num g">TOTAL ITEM AMOUNT</td><td class="td_num">'.$total.'</td></tr>';
		echo '<tr><td colspan="7" class="td_num g">A: SERVICE CHARGES</td><td class="td_num">'.$service.'</td></tr>';
		echo '<tr><td colspan="7" class="td_num g">B: LATE PAYMENT FEE</td><td class="td_num">'.$late_fee.'</td></tr>';

		$a_b = round(($service+$late_fee)*(9/100),2);
		$grand = round($total+$service+$late_fee+(2*$a_b));

		echo '<tr><td colspan="7" class="td_num g">HSN-9997 CGST: 9%(A+B)</td><td class="td_num">'.$a_b.'</td></tr>';
		echo '<tr><td colspan="7" class="td_num g">SGST: 9%(A+B)</td><td class="td_num">'.$a_b.'</td></tr>';




		echo '<tr style="font-weight: 700;"><td colspan="7" class="td_num g">TOTAL BILL AMOUNT</td><td class="td_num">'.$grand.'</td></tr>';
		echo '</table>';

		// echo'<button id="generate_bill" custid="'.$cust_id.'" date1="'.$date1.'" date2="'.$date2.'" invoiceno="'.$invoice_no.'">Invoice</a></button>';

		echo '<div style="font-weight:600;font-size:14px;">PLEASE NOTE:</div>';
		echo '<div style="font-size:14px;">Charge of Rs. 300 is applicable for returned cheque.</div>';
		echo '<div style="font-size:14px;">Payment after the 10th is subject to 2% late fee.</div>';
		echo '<div style="font-size:14px;">Payment after the 15th is subject to 4% late fee.</div>';


		if ($type == 'new') {

			$date = date("Y-m-d H:i:s");


			$sql = "SELECT 1 FROM `invoices` WHERE `invoice_no` =  '".$invoice_no."' AND `pump_id` = '".$pump_id."' ;";
			$exe = mysqli_query($conn, $sql);
			if (mysqli_num_rows($exe) < 1) {

				$sql = "INSERT INTO `invoices`(`invoice_no`, `cust_id`, `from`, `to`,`amount`, `date`, `pump_id`) VALUES ('".$invoice_no."','".$cust_id."','".$date1."','".$date2."','".$grand."','".$date."','".$pump_id."');";
				$exe = mysqli_query($conn, $sql);
			}			

			$sql1 = "UPDATE `transactions` SET `billed`= 'Y', `last_updated` = '".$date."'  WHERE `cust_id` = '".$cust_id."' AND date(`date`) BETWEEN '".$date1."' AND '".$date2."' ;";
			$exe1 = mysqli_query($conn, $sql1);

			if ($cust_post_paid == "Y") {
				$sql4 = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding` - '".$total."' , `payment_balance` = `payment_balance` + '".$grand."' WHERE `cust_id` = '".$cust_id."' ;";
			}else{
				$sql4 = "UPDATE `customers` SET `cust_balance` = `cust_balance` + '".$total."' , `payment_balance` = `payment_balance` + '".$grand."'  WHERE `cust_id` = '".$cust_id."' ;";
			}
			
			$exe4 = mysqli_query($conn, $sql4);
		}
	}
	else{
		echo'No Data';
	}		
}
?>
</div>


</body>
</html>