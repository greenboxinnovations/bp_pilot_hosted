<?php
require 'exe/lock.php';
if(!isset($_SESSION))
{
    session_start();
    if (!isset($_SESSION['pump_id']) || !isset($_SESSION['role'])) 
	{
	    header("location: login.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Payments</title>

	<!-- favicons -->
	<link rel="apple-touch-icon" sizes="57x57" href="css/favi/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="css/favi/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="css/favi/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="css/favi/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="css/favi/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="css/favi/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="css/favi/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="css/favi/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="css/favi/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="css/favi/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favi/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="css/favi/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favi/favicon-16x16.png">
	<link rel="manifest" href="css/favi/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="css/favi/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">


	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<style type="text/css">
		*{padding: 0;margin: 0;}
		/*#transactions_table{background-color: yellow;}*/
		.tab{display: inline-block;font-weight: 600;padding: 5px;margin-right: 10px;color: rgb(150,150,150);border-bottom: 3px solid transparent;}
		/*.tab_active{border-bottom: 3px solid orange;color: orange;}
		.tab:hover{border-bottom: 3px solid orange;cursor: pointer;}*/
		/*.tab_active{border-bottom: 3px solid rgb(41,169,201);color: rgb(41,169,201);}
		.tab:hover{border-bottom: 3px solid rgb(41,169,201);cursor: pointer;}*/
		.tab_active{border-bottom: 3px solid rgb(29,182,167);color: rgb(29,182,167);}
		.tab:hover{border-bottom: 3px solid rgb(29,182,167);cursor: pointer;}

		th{text-align: left;}
		th.th_num{text-align: right;padding-left: 10px;}
		/*tr:nth-child(odd){background-color: rgb(207,216,220);}*/
		.odd,th{background-color: rgb(222,228,231);}
		/*tr:nth-child(odd){background-color: rgb();}*/

		/*.td_num{text-align: right;}*/
		#display{padding-top: 50px;margin-bottom: 30px;}
		#name{font-family: 'Roboto Slab', serif;font-size: 40px;margin-bottom: 10px;}
		#ph_no{font-weight: 600; color: rgb(100,100,100);}
		#bal{font-weight: 700;color: rgb(100,100,100);}
		/*#bal span{font-weight: 700;color: rgb(100,100,100);}*/

		/*car plate no*/
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none;margin: 0; }
		#in_car_no_plate_state{width: 30px;text-transform:uppercase;}		
		#in_car_no_plate_city{width: 20px;}
		#in_car_no_plate_letter{width: 30px;text-transform:uppercase;}
		#in_car_no_plate_number{width: 40px;}

		input{padding: 5px;}
		select{padding: 5px;margin-top: 5px;width: 250px;}
		.form_header{font-weight: 700;margin: 10px auto;padding-left: 2px;color: rgb(100,100,100);}


		.transactions_fab{background: url('css/icons/ic_edit.png') no-repeat center center;}
		.cars_fab{background: url('css/icons/ic_car.png') no-repeat center center;}
		.payments_fab{background: url('css/icons/ic_pay.png') no-repeat center center;}

		td a{color: green;}

		.change_this{width:300px;}

		.new_pay{background: url('css/icons/ic_edit.png');background-repeat: no-repeat;padding-left: 18px;padding-right: 18px;}
		.new_pay:hover{cursor: pointer;background-color: #607d8b;}

		.new_sms{background: url('css/icons/ic_sms.png');background-repeat: no-repeat;padding-left: 18px;padding-right: 18px;}
		.new_sms:hover{cursor: pointer;background-color: #607d8b;}


		
		#wrapper_content{margin-left: 65px;margin-right: 65px;}


#grand_total{color:#930a0a;font-size: 22px;margin-bottom: 10px; }

.cust_disp_name{min-width:300px;padding-right: 12px;}
.date_range{color: rgba(0,0,0,0.5);}
.invoice_no{text-align:right;}
.amount_paid{text-align:right;min-width: 50px;padding-right: 15px;}
.invoice_amount{text-align:right;min-width: 150px;padding-right: 15px;}
.invoice_pending{text-align:right;}
.total_text{text-align: right; color: #930a0a;}
.total_r{text-align: right; color: #930a0a;}
.prev_balance{text-align: right; color: #930a0a;padding-right: 15px;}
.prev_b_val{text-align: right; color: #930a0a;padding-right: 15px;}

#total_table td{color: #930a0a;}

td{padding-top: 10px;padding-bottom: 10px;}


		/*change_this*/
		@media only screen and (max-width: 1360px) {			
			/*body{background-color: black;}*/
			.invoice_amount{min-width: 10px;}
			.cust_disp_name{min-width: 10px;width:250px;padding-right: 12px;}
		}
	</style>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			// functions
			function windowScroll(){
				if(scrollFunc){
					var offset = $(this).scrollTop();					

					if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
						$fixedHeader.show();
					}
					else if (offset < tableOffset) {
						$fixedHeader.hide();
					}
				}
			}

			function scrollInit(){
				scrollFunc = true;
				var table = $("#table-1");
				if (table.length) {
				  tableOffset = $("#table-1").offset().top;
				}
				
				$header 	= $("#table-1 > thead").clone();
				$fixedHeader = $("#header-fixed").append($header);
			}

			function init(){
				$('#display').load('display/view_payments.php?year=0&month=0', scrollInit);
			}

			function showSnackBar(message) {
				$('#snackbar').text(message);
				$('#snackbar').animate({'bottom':'0'},function() {
					setTimeout(function(){
						$('#snackbar').animate({'bottom':'-50px'});           
					},2000);
				});
			}

			// globals
			init();
			var scrollFunc = false;
			var tableOffset, $header, $fixedHeader;
			var clicked = false;
			var cust_id = 0;
			var month = $('#month').val();



			// window 
			$(window).bind("scroll", windowScroll);

			// invoice view
			$('body').delegate('.new_pay', 'click', function(){
				var invoice_no = $(this).attr('invoiceno');
				var invoice_amount = $(this).attr('invoiceamount');
				$('#display').load('forms/add_payment.php?invoice_amount='+invoice_amount+'&invoice_no='+invoice_no);
				cust_id = $(this).attr('custid');
			});

			// reminder sms
			$('body').delegate('.new_sms', 'click', function(){

				var total_r = $(this).attr('totalr');
				var cust_id = $(this).attr('custid');

				if (!clicked) {
					
					clicked = true;
					var myObject = {};
					myObject.total_r 				= total_r;
					myObject.cust_id 				= cust_id;

					json_string = JSON.stringify(myObject);

					var url = 'exe/reminder_sms.php';
					console.log(json_string);

					$.ajax({
						url: url,
						type: 'POST',
						contentType: "application/json",
						data:json_string,
						success: function(response){
							var json = $.parseJSON(response);
							showSnackBar(json.msg);
							clicked = false;
						},
						error: function(data, errorThrown){
							showSnackBar(errorThrown);
							clicked = false;
						}
					});
				}
				else{

					showSnackBar('Reload Page, then try!');
					clicked = false;
				}


			});


			// cancel payment
			$('body').delegate('#btn_cancel_payment', 'click', function(){
				if (month != "") {
					var split = month.split('-');
					$('#display').load('display/view_payments.php?year='+split[0]+'&month='+split[1], scrollInit);
				}
			});

			// confirm payment
			$('body').delegate('#btn_confirm_payment', 'click', function(){
				var payment_amount 	= $('#payment_amount').val();
				var payment_date 	= $('#payment_date').val();
				var payment_comment = $('#payment_comment').val();
				var invoice_no 		= $(this).attr('invoiceno');
				var invoice_amount 	= $(this).attr('invoiceamount');
				


				// alert(cust_id);
				// console.log(payment_comment);

				if((payment_amount > 0) &&(cust_id != 0)&& (payment_date != "") &&(!clicked)){
					// add ph_no validation here

					clicked = true;

					var myObject = {};
					myObject.payment_amount = payment_amount;
					myObject.cust_id 		= cust_id;
					myObject.payment_date 	= payment_date;
					myObject.payment_comment= payment_comment;
					myObject.invoice_no 	= invoice_no;
					myObject.invoice_amount = invoice_amount;

					json_string = JSON.stringify(myObject);

					var url = 'api/payments';

					console.log(json_string);

					$.ajax({
						url: url,
						type: 'POST',
						contentType: "application/json",
						data:json_string,
						success: function(response){
							if (month != "") {
								var split = month.split('-');
								$('#display').load('display/view_payments.php?year='+split[0]+'&month='+split[1], scrollInit);
							}
							clicked = false;
						},
						error: function(data, errorThrown){
							showSnackBar(errorThrown);
							clicked = false;
						}
					});
				}
				else{
					console.log('invalid amount or date');
					showSnackBar('INVALID AMOUNT OR DATE');
					clicked = false;
				}
			});
 
			//on month change
			$('body').delegate('#month', 'change', function(){
				month = $(this).val();
				if (month != "") {
					var split = month.split('-');
					
					$('#display').load('display/view_payments.php?year='+split[0]+'&month='+split[1], scrollInit);
				}
							
			});

		});
	</script>
</head>
<body>

<!-- app nav -->
<div id="app_bar">  
	<div id="menu">
		<img src="css/icons/ic_menu.png">
	</div>
	<div id="app_name"><a href="index.php">FuelCam</a></div>
</div>

<!-- side nav -->
<?php 
	$active_page = 'customers';
	require 'nav.php';
?>


<!-- wrapper -->
<div id="wrapper">
	<div id="date_div" style="margin-top: 80px; margin-left: 65px;">
		<input id="month" type="month" value="<?php echo date("Y-m");?>">
	</div>
	<div id="wrapper_content">
		<div id="display"></div>		
	</div>
</div>

<!-- snackbar -->
<div id="snackbar"></div>

<!-- fab -->
<!-- <div id="fab"></div> -->


</body>
</html>