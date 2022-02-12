<?php
require 'exe/lock.php';
header('Access-Control-Allow-Origin: *'); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Title</title>
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var print_url        = <?php echo json_encode(Globals::REPRINT_URL);?>;
			// generate invoice click
			$('body').delegate('#generate_bill', 'click', function(){

				if (!click) {

					click = true;

					var cust_id = $(this).attr('custid');
					var date1 = $(this).attr('date1');
					var date2 = $(this).attr('date2');
					var late_fee = $(this).attr('latefee');
					var invoice_no = $(this).attr('invoiceno');
					var date_invoice = $(this).attr('dateinvoice');
					var pump_id = $(this).attr('pumpid');
					var grand = $(this).attr('grand');
					var type  = 'new';
					if (confirm('Generate Bill and reset Credits?')) {	

						if ((cust_id == undefined )||(date1 == undefined)||(date2 == undefined)||(invoice_no == undefined)||(date_invoice == undefined)||(grand == undefined)) {}
						else{
							window.open('exe/report.php?cust_id='+cust_id+'&date1='+date1+'&date2='+date2+'&type='+type+'&date_invoice='+date_invoice+'&invoice_no='+invoice_no+'&late_fee='+late_fee+'&pump_id='+pump_id, '_blank');	
						
							window.top.close();
						}	
						
					   	window.top.close();
					   	
					}else{
						click = false;
					}
				}
			});

			$('body').delegate('.delete', 'click', function(){

				var id = $(this).attr("id");
				var url = 'api/transactions/delete';

				var myObject = {};
				myObject.trans_id = id;
				json_string = JSON.stringify(myObject);

				if (confirm('Delete Transaction?')) {		

					$.ajax({
						url: url,
						type: 'POST',
						contentType: "application/json",
						data:json_string,
						success: function(response){
							alert(response);
							location.reload();
						}
					});
				}
			});

			$('body').delegate('.print', 'click', function(){

				var trans_id = $(this).attr("id");
				var url = print_url + trans_id;

				if (confirm('Reprint Transaction?')) {		
					window.open(url,'_blank');
					// $.ajax({
					// 	url: url,
					// 	type: 'GET',				
					// 	success: function(response){
					// 		console.log(response);
					// 	}
					// });
				}
			});

		}); 

		var click = false;


	</script>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
		@font-face {
		    font-family: OpenSans;
		    src: url('css/fonts/OpenSans-Regular.ttf');

		}

		*{padding: 0;margin: 0;}
		body{font-family: OpenSans,sans-serif;z-index: 0;}	

		.delete{
		    	background: url('css/icons/ic_cancel.png') no-repeat center center;
		    	padding: 15px;
		    }
		.delete:hover{background-color: rgb(120,200,200);cursor: pointer;}

		.print{
		    	background: url('css/icons/ic_receipt_black.png') no-repeat center center;
		    	padding: 15px;
		    }
		.print:hover{background-color: rgb(120,200,200);cursor: pointer;}

		#wrapper{width: 800px;/*background-color: yellow;*/margin: 0 auto;}
		#pump_name{text-align: center;font-weight: 700;font-size: 30px;margin-top: 20px;}
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

		#generate_bill{margin-bottom: 50px;padding: 7px 25px;font-weight: bold;}
	</style>
</head>
<body>
 

<div id="wrapper">
<?php
if(isset($_GET['cust_id'])){

	require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
	$cust_id = $_GET['cust_id'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];
	$date_invoice = $_GET['date_invoice'];
	$late_fee =0;
	$late_fee = $_GET['late_fee'];


	$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."';";
	$exe = mysqli_query($conn, $sql);

	if(mysqli_num_rows($exe) == 1){
		$row = mysqli_fetch_assoc($exe);

		$cust_company	 = $row["cust_company"];
		$cust_gst	 	 = $row["cust_gst"];
		$cust_service	 = $row["cust_service"];
		$pump_id 		 = $row["cust_pump_id"];


	
		$cust_full_name = ucwords($row["cust_disp_name"]);
		if ($cust_gst != NULL) {
			$cust_address	= strtoupper('GST: '.$cust_gst);
		}else{
			$cust_address	= "";
		}
			
		$date = date("Y-m-d");

		$sql3 = "SELECT `invoice_no` FROM `invoices` WHERE `pump_id` = '".$pump_id."' ORDER BY `in_id` DESC LIMIT 1 ;";
		$exe3 = mysqli_query($conn, $sql3);
		$row3 = mysqli_fetch_assoc($exe3);
		if (($row3['invoice_no'] < 1)||($row3['invoice_no'] == "")) {
			$invoice_no =1;
		}else{
			$invoice_no = $row3['invoice_no'] +1;
		}


		$sql = "SELECT a.*, b.car_no_plate, b.car_fuel_type
		FROM `transactions` a
		JOIN `cars` b
		ON a.car_id = b.car_id
		WHERE date(a.date) BETWEEN '".$date1."' AND '".$date2."' AND a.cust_id = '".$cust_id."' AND a.billed = 'N'  ORDER BY date(a.date) ASC;";
		

		$sql2 = "SELECT * FROM `pumps` WHERE `pump_id` = '".$pump_id."';";
		$exe2 = mysqli_query($conn, $sql2);
		if(mysqli_num_rows($exe2) == 1){
			$row2 = mysqli_fetch_assoc($exe2);
			$pump_name	 	= ucwords($row2["pump_name"]);
			$pump_address	= ucwords($row2["pump_address"]);
			$pump_gst	 	= strtoupper($row2["pump_gst"]);


			echo '<div id="pump_name">'.$pump_name.'</div>';
			echo '<div id="pump_add">'.$pump_address.'</div>';	
			echo '<div id="pump_gst">GST No: '.$pump_gst.'</div>';
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
	
	$exe = mysqli_query($conn, $sql);

	$total 	= 0;

	if(mysqli_num_rows($exe) > 0){


		echo '<table>';
		echo '<tr>';
			echo '<th></th>';
			echo '<th>Receipt no</th>';
			echo '<th>Trans ID</th>';
			echo '<th style="text-align:center;">Date</th>';
			echo '<th style="text-align:center;">Vehicle No</th>';
			echo '<th style="text-align:center;">Fuel Type</th>';				
			echo '<th style="text-align:center;">Litres</th>';
			echo '<th style="text-align:center;">Fuel Rate</th>';
			echo '<th style="text-align:center;">Amount Rs</th>';
			echo '<th style="text-align:center;"></th>';
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

			$trans_string = $row["trans_string"];

			$trans_id_disp = $row["trans_id"] + 100000;

			if($fuel == 'Diesel'){
				$hsd += $amount;
				$hsd_lit += $liters;
			}
			else{
				$ms += $amount;
				$ms_lit += $liters;
			}

			echo '<tr>';

				if ($trans_string == NULL) {
					echo '<td class="td_num delete" id="'.$row['trans_id'].'"></td>';
				}else{
					echo '<td></td>';
				}				
				echo '<td>'.$row["receipt_no"].'</td>';
				echo '<td>'.$trans_id_disp.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td>'.$car_no.'</td>';				
				echo '<td>'.$fuel.'</td>';
				echo '<td class="td_num">'.$liters.'</td>';
				echo '<td class="td_num">'.$rate.'</td>';
				echo '<td class="td_num">'.$amount.'</td>';		
				echo '<td class="td_num print" id="'.$row['trans_id'].'"></td>';
			echo '</tr>';

			$total += $amount;
			++$count;
		}

		$service = round($total*($cust_service/100),2);

		

		echo '<tr style="border-top:2px solid rgb(170,170,170);"><td></td><td colspan="5" class="td_num g">HSD</td><td class="td_num">'.$hsd_lit.'</td><td></td><td class="td_num">'.$hsd.'</td></tr>';
		echo '<tr style="border-bottom:2px solid rgb(170,170,170);"><td></td><td colspan="5" class="td_num g">MS</td><td class="td_num">'.$ms_lit.'</td><td></td><td class="td_num">'.$ms.'</td></tr>';


		echo '<tr><td></td><td colspan="7" class="td_num g">TOTAL ITEM AMOUNT</td><td class="td_num">'.$total.'</td></tr>';
		echo '<tr><td></td><td colspan="7" class="td_num g">A: SERVICE CHARGES</td><td class="td_num">'.$service.'</td></tr>';
		echo '<tr><td></td><td colspan="7" class="td_num g">B: LATE PAYMENT FEE</td><td class="td_num">'.$late_fee.'</td></tr>';

		$a_b = round(($service+$late_fee)*(9/100),2);
		$grand = round($total+$service+$late_fee+(2*$a_b));

		echo '<tr><td></td><td colspan="7" class="td_num g">HSN-9997 CGST: 9%(A+B)</td><td class="td_num">'.$a_b.'</td></tr>';
		echo '<tr><td></td><td colspan="7" class="td_num g">SGST: 9%(A+B)</td><td class="td_num">'.$a_b.'</td></tr>';



		echo '<tr style="font-weight: 700;"><td></td><td colspan="7" class="td_num g">TOTAL BILL AMOUNT</td><td class="td_num">'.$grand.'</td></tr>';
		echo '</table>';


		echo '<div style="font-weight:600;font-size:14px;">PLEASE NOTE:</div>';
		echo '<div style="font-size:14px;">Charge of Rs. 300 is applicable for returned cheque.</div>';
		echo '<div style="font-size:14px;">Payment after the 10th is subject to 2% late fee.</div>';
		echo '<div style="font-size:14px;">Payment after the 15th is subject to 4% late fee.</div>';

		echo'<button id="generate_bill" custid="'.$cust_id.'" date1="'.$date1.'" date2="'.$date2.'" invoiceno="'.$invoice_no.'" grand="'.$grand.'" dateinvoice="'.$date_invoice.'" latefee="'.$late_fee.'" pumpid="'.$pump_id.'" >Invoice</a></button>';


		

	}else{
		echo'No Data';
	}		
}
?>
</div>



</body>
</html>