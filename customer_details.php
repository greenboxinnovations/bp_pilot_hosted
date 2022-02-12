<?php
require 'exe/lock.php';
if(!isset($_SESSION)) {
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
	<title>Title</title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />	

	
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
		tr:nth-child(odd){background-color: rgb(222,228,231);}
		/*tr:nth-child(odd){background-color: rgb();}*/

		/*.td_num{text-align: right;}*/
		#display{padding-top: 50px;margin-bottom: 30px;display: inline-block;}
		#notes{padding-top: 1px;margin-top: 260px; background-color: rgb(232,235,237); color: #868786; height: 80px; display: none; max-width: 200px; }
		#name{font-family: 'Roboto Slab', serif;font-size: 40px;margin-bottom: 10px;}
		#ph_no{font-weight: 600; color: rgb(100,100,100);}
		#bal{font-weight: 700;color: rgb(100,100,100);}
		/*#bal span{font-weight: 700;color: rgb(100,100,100);}*/


		.edit{
			background: url('css/icons/ic_edit.png') no-repeat center center;
		} 

		.delete_invoice{
			background: url('css/icons/ic_cancel.png') no-repeat center center;
		}

		/*car plate no*/
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none;margin: 0; }
		#in_car_no_plate_state{width: 30px;text-transform:uppercase;}		
		#in_car_no_plate_city{width: 20px;}
		#in_car_no_plate_letter{width: 30px;text-transform:uppercase;}
		/*#in_car_no_plate_number{width: 40px;}*/

		input{padding: 5px;}
		select{padding: 5px;margin-top: 5px;width: 250px;}
		.form_header{font-weight: 700;margin: 10px auto;padding-left: 2px;color: rgb(100,100,100);}


		.transactions_fab{background: url('css/icons/ic_edit.png') no-repeat center center;}
		.cars_fab{background: url('css/icons/ic_car.png') no-repeat center center;}
		.payments_fab{background: url('css/icons/ic_pay.png') no-repeat center center;}
		.receipt_book_fab{background: url('css/icons/ic_receipt.png') no-repeat center center;} 

		#fab .tooltiptext {
		    visibility: hidden;
		    width: 60px;
		    opacity: 0.8;    
		    background-color: #263238;
		    color: #fff;
		    font-size: 13px;
		    text-align: center;
		    border-radius: 3px;
		    padding: 7px 10px;
		    
		    /* Position the tooltip */
		    position: absolute;
		    z-index: 1;
		    top: -40px;
		    right: 5px;    
		}

		.highlight:hover{background-color: rgb(29,182,167);cursor: pointer;}
		.fy_hide{display: none;}
		
		#fab:hover .tooltiptext {
		    visibility: visible;
		} 

		#wrapper{
			  display: flex;
		}

		.right{text-align: right;}

		td.new_pay{background: url('css/icons/ic_edit.png');background-repeat: no-repeat;}
		.delete_invoice:hover, .new_pay:hover{cursor: pointer;background-color: #607d8b;}

		td a{color: green;}
		.change_this{width:300px;}
		/*table{min-width: 900px;}*/
		/*change_this*/
		@media only screen and (max-width: 1360px) {			
			.change_this{width: 200px;}
		}
	</style>
	<script type="text/javascript" src="js/jquery.js"></script>

	<!-- datepicker css -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
	<!-- jqueryUI -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			var msg_url        = <?php echo json_encode(Globals::URL_MSG_VIEW);?>;
			var getUrlParameter = function getUrlParameter(sParam) {
			    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			        sURLVariables = sPageURL.split('&'),
			        sParameterName,
			        i;

			    for (i = 0; i < sURLVariables.length; i++) {
			        sParameterName = sURLVariables[i].split('=');

			        if (sParameterName[0] === sParam) {
			            return sParameterName[1] === undefined ? true : sParameterName[1];
			        }
			    }
			};

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


			function showSnackBar(message) {
				$('#snackbar').text(message);
				$('#snackbar').animate({'bottom':'0'},function() {
					setTimeout(function(){
						$('#snackbar').animate({'bottom':'-50px'});           
					},2000);
				});
			}


			function validateNewCar(){
				var returnVal = true;

				// if fuel spinner is select
				if( $('#select_fuel_type').find(":selected").val() == -1){
					return false;
				}

				// not letter is probably only KASAT specific
				$('#validate_car input').not('#in_car_no_plate_letter').each(function(){
					// console.log($(this).val());
					// var id = $(this).attr('id');
					// console.log(id);

					if($(this).val() == ""){						
						returnVal = false;
						return false;
					}
				});
				
				return returnVal;
			}


			function validateNewTransaction(){
				var returnVal = true;

				// if brand spinner is select
				if( $('#select_car').find(":selected").val() == -1){
					return false;
				}

				if( $('#trans_date').val() == ''){
					return false;
				}


				$('input[type="number"]:visible').each(function(){
					if($(this).val() == ""){
						console.log('asd');
						returnVal = false;
						return false;
					}
				});

				// rbook is not compulsory
				if( $('#rbook_input').val() == ''){
					return true;
				}


				return returnVal;
			}


			function validateNewReceiptBook(){

				var returnVal = true;

				$('input[type="number"]:visible').each(function(){
					if($(this).val() == ""){
						console.log('asd');
						returnVal = false;
						// return false;
					}
				});


				var min = $('#in_rbook_min').val();
				var max = $('#in_rbook_max').val();

				if(max < min){
					returnVal = false;						
				}

				return returnVal;
			}

			function scrollInit(){
				scrollFunc = true;
				var table = $("#table-1");
				if (table.length) {
				  tableOffset = $("#table-1").offset().top;
				}
				$header 	= $("#table-1 > thead").clone();
				$fixedHeader = $("#header-fixed").append($header);


				var t = $('.tab_active').text();
				switch(t){
					case 'TRANSACTIONS':
						$('#date1').datepicker({ dateFormat: 'dd-mm-yy' });
						$('#date2').datepicker({ dateFormat: 'dd-mm-yy' });
						$('#date_invoice').datepicker({ dateFormat: 'dd-mm-yy' });
						break;
					case 'HISTORY':
						$('.date1_h').datepicker({ dateFormat: 'dd-mm-yy' });
						$('.date2_h').datepicker({ dateFormat: 'dd-mm-yy' });
						break;
					case 'STATEMENT':
						$('.date1_s').datepicker({ dateFormat: 'dd-mm-yy' });
						$('.date2_s').datepicker({ dateFormat: 'dd-mm-yy' });
						break;
				}
			}

			function init(){

				$('#display').load('display/cust_details.php?cust_id='+cust_id, function(){
					var name = $('#name').text();
					document.title = name;
					// $( "#date1" ).datepicker({ dateFormat: 'dd-mm-yy' });					
				});
				
				var rbooks = getUrlParameter('tab');
				if(rbooks != undefined){

					$('.tab').removeClass('tab_active');
					$('#receipt_book').addClass('tab_active');
				
				
					$('#fab').attr('class','');
					$('#fab').addClass('receipt_book_fab');

					$('#pager_content').load('display/cust_rbooks.php?cust_id='+cust_id, scrollInit);


					$('#fab span').text('ADD RECEIPT-BOOK');
					$('#fab .tooltiptext').css('width','130px');
					

				}
				else{
					$('#pager_content').load('display/cust_transactions.php?cust_id='+cust_id, scrollInit);
					$('#fab').addClass('transactions_fab');
					$('#fab span').text('ADD TRANSACTION');
					$('#fab .tooltiptext').css('width','120px');
				}				
			}

			function checkRates(){

				var car_fuel_type =  $('#select_car').find(":selected").attr("ftype");
				console.log(car_fuel_type);

				var date = $('#trans_date').val();


				if ((date != "")&&(car_fuel_type != "")) {

					$.ajax({
						url: 'exe/check_rates.php',
						data:{
							date:date},
						type: 'GET',
						contentType: "application/json",										
						success: function(response) {						
							var json = JSON.parse(response);
							if(!json.rate_set){
								$("#rate").val("");	
								$("#rate").prop("disabled" , false);									
							}
							else{

								var p_rate = json.petrol;
								var d_rate = json.diesel;
								var f = null;
								
								if (car_fuel_type == "petrol") {
									f = p_rate;
									
								}else if(car_fuel_type == "diesel"){
									f = d_rate;
								}
								if(f != 0.00){
									$("#rate").val(f);	
									$("#rate").prop("disabled" , true);
									// $("#rs").focus();
								}else{
									$("#rate").val("");	
									$("#rate").prop("disabled" , false);
								}
							}
						}
					});

				}else{
					showSnackBar("Select date First");
				}				
			}

			// globals
			var scrollFunc = false;
			var tableOffset, $header, $fixedHeader;
			var cust_id = getUrlParameter('cust_id');			



			var clicked = false;
			var del_click =false;
			init();

			// window 
			$(window).bind("scroll", windowScroll);

			


			// viewpager toggle
			$('body').delegate('.tab', 'click', function(){
				var id = $(this).attr("id");
				$('#fab').show();
				// toggle tab styles
				$('.tab').removeClass('tab_active');
				$('#'+id).addClass('tab_active');
				$()
				
				$('#fab').attr('class','');
				$('#fab').addClass(id+'_fab');	
				$('#notes').hide();			
				// load display
				switch(id){
					case "transactions":
						$('#pager_content').load('display/cust_transactions.php?cust_id='+cust_id, scrollInit);
						$('#fab span').text('ADD TRANSACTION');
						$('#fab .tooltiptext').css('width','120px');
						break;
					case "cars":
						$('#pager_content').load('display/cust_cars.php?cust_id='+cust_id, scrollInit);
						$('#fab span').text('ADD CAR');
						$('#fab .tooltiptext').css('width','60px');
						break;
					case "payments":
						$('#pager_content').load('display/cust_payments.php?cust_id='+cust_id);
						   $('#fab').hide();
						break;
					case "invoices":
						$('#pager_content').load('display/cust_invoices.php?cust_id='+cust_id, scrollInit);
							$('#fab').hide();
						break;
					case "receipt_book":
						$('#pager_content').load('display/cust_rbooks.php?cust_id='+cust_id, scrollInit);
						$('#fab span').text('ADD RECEIPT-BOOK');
						$('#fab .tooltiptext').css('width','130px');
						break;
					case "history":
						$('#pager_content').load('display/cust_transactions_history.php?cust_id='+cust_id, scrollInit);
						$('#fab').hide();
						break;
					case "statement":
						$('#pager_content').load('display/cust_statement.php?cust_id='+cust_id, scrollInit);
						$('#fab').hide();
						break;
				}
			});


			// ----------------- NEW CAR ---------------------//
			// select brand spinner
			$('body').delegate('#select_brand', 'change', function(){
				$('#in_car_sub_brand').hide();
				var brand = $(this).find(":selected").val();
				if(brand == -1){	// not select
					$('#in_car_brand').hide();
					$('#select_sub_brand').hide();
				}
				else if(brand == 999){
					$('#in_car_brand').show().focus();
					$('#in_car_sub_brand').show();
					$('#select_sub_brand').hide();
				}
				else{

					$('#in_car_brand').hide();
					$('#select_sub_brand').show();
					// $('#select_brand').blur();

					$.ajax({
						url: 'exe/get_sub_brand.php',
						type: 'GET',
						contentType: "application/json",
						data:{
							brand: brand
						},
						success: function(response){
							console.log(response);
							var arr = $.parseJSON(response);
							$('#select_sub_brand').empty();
							$.each(arr, function(key,value) {
								$('#select_sub_brand').append($("<option></option>")
									.attr("value",value)
									.text(value));
							});
							$('#select_sub_brand').append($("<option></option>")
									.attr("value","other")
									.text("Other"));
						}
					});
				}
			});
			// sub brand other
			$('body').delegate('#select_sub_brand', 'change', function(){
				var brand = $(this).find(":selected").val();
				if(brand == 'other'){	// other
					$('#select_sub_brand').hide();
					$('#in_car_sub_brand').show().focus();
				}
			});

			// no plate validation
			$('body').delegate('.only_letter','keydown',function(){
				// Allow controls such as backspace, tab etc.
				var arr = [8,9,16,17,20,35,36,37,38,39,40,45,46];

				// Allow letters
				for(var i = 65; i <= 90; i++){
					arr.push(i);
				}

				// Prevent default if not in array
				if($.inArray(event.which, arr) === -1){
					event.preventDefault();
				}
			});

			// cancel new car
			$('body').delegate('#btn_cancel_new_car', 'click', function(){
				$('#pager_content').load('display/cust_cars.php?cust_id='+cust_id, scrollInit);
				$("#fab").show();
			});
			// submit values and add to DB
			$('body').delegate('#btn_new_car', 'click', function(){

				

				// add ph_no validation here
				if((validateNewCar())&&(!clicked)){

					clicked = true;

					// BRAND
					// if($('#in_car_brand').is(':visible')) {
					// 	var car_brand	 	= $('#in_car_brand').val().toLowerCase();
					// 	if(car_brand == ''){
					// 		car_brand = 'unknown';
					// 	}
					// }
					// else{
					// 	var car_brand	 	= $('#select_brand').find(":selected").val();
					// 	if(car_brand == -1){ car_brand = 'unknown'; }
					// }
					// // console.log(car_brand);

					// // SUB BRAND
					// if($('#in_car_sub_brand').is(':visible')) {
					// 	var car_sub_brand	= $('#in_car_sub_brand').val().toLowerCase();
					// 	if(car_sub_brand == ''){
					// 		car_sub_brand = 'unknown';
					// 	}
					// }
					// else if($('#select_sub_brand').is(':visible')){
					// 	var car_sub_brand	 = $('#select_sub_brand').find(":selected").val().toLowerCase();
					// 	if(car_sub_brand == ''){ car_sub_brand = 'unknown'; }
					// }
					// else{
					// 	var car_sub_brand = 'unknown';
					// }
					// console.log(car_sub_brand);

					var car_brand = 'unknown';
					var car_sub_brand = 'unknown';
					
					// var no_plate_state 	= $('#in_car_no_plate_state').val();
					// var plate_city 		= $('#in_car_no_plate_city').val();
					// var plate_letter 	= $('#in_car_no_plate_letter').val();
					var plate_number 	= $('#in_car_no_plate_number').val();


					// if(plate_letter != ""){
					// 	var car_no_plate	= no_plate_state+" "+plate_city+" "+plate_letter+" "+plate_number;	
					// }
					// else{
					// 	var car_no_plate	= no_plate_state+" "+plate_city+" "+plate_number;	
					// }
					 

					// console.log(car_no_plate);

					var car_fuel_type	= $('#select_fuel_type').find(":selected").val();
					var car_cust_id	 	= $('#in_car_cust_id').val();
					var car_qr_code	 	= $('#in_car_qr_code').val();


					console.log('inputs are valid');

					var myObject = {};
					myObject.car_brand = car_brand;
					myObject.car_sub_brand = car_sub_brand;
					myObject.car_no_plate = plate_number;
					myObject.car_fuel_type = car_fuel_type;
					myObject.car_qr_code = car_qr_code;
					myObject.cust_id = cust_id;
					myObject.pump_id = <?php  if(!isset($_SESSION)){session_start();} echo json_encode($_SESSION['pump_id']);?>;



					json_string = JSON.stringify(myObject);

					var url = 'api/cars';

					console.log(json_string);

					$.ajax({
						url: url,
						type: 'POST',
						contentType: "application/json",
						data:json_string,
						success: function(response){
							console.log(response);
							var json = JSON.parse(response);
							showSnackBar(json.msg);
							
						   // showSnackBar("New Company Added!");
						    $('#pager_content').load('display/cust_cars.php?cust_id='+cust_id, scrollInit);
							$("#fab").show();
							clicked = false;
						},
						error: function (error) {
						    clicked = false;
						}
					});
				}
				else{
					console.log('invalid inputs');
					showSnackBar('Invalid Inputs');
					clicked = false;
				}
			});
			
			// ----------------- INVOICES ---------------------//		
			$('body').delegate('.invoice', 'click', function(){
				var invoice_no = $(this).find('.invoice_no').text();
				var from = $(this).find('.from').text();
				var to = $(this).find('.to').text();
				var cust_id = $(this).attr('custid');
					var pump_id = <?php  if(!isset($_SESSION)){session_start();} echo json_encode($_SESSION['pump_id']);?>;
				var date_invoice = '';
				var type = invoice_no;
				window.open('exe/report.php?cust_id='+cust_id+'&date1='+from+'&date2='+to+'&type='+type+'&date_invoice='+date_invoice+'&invoice_no='+invoice_no+'&pump_id='+pump_id, '_blank');	
			});

			$('body').delegate('.delete_invoice', 'click', function(e) {
				e.stopPropagation(); 
				var in_id = $(this).attr('id');

				if (!del_click) {
					del_click = true;
					if (confirm('Delete Invoice And revert transactions?')) {	
					   
						$.ajax({
							url: 'exe/delete_invoice.php',
							type: 'POST',
							data:{
								in_id : in_id
							},
							success: function(response){
								$('#pager_content').load('display/cust_invoices.php?cust_id='+cust_id, scrollInit);
								$('#display').load('display/cust_details.php?cust_id='+cust_id);
								del_click = false;
							},
							error: function (error) {
							    del_click = false;
							}
						});
					   	
					}else{
						del_click = false;
					}
				}
			});

			$('body').delegate('.fy_btn', 'click', function(){
				fy_year = $(this).attr('fybtn');
				if($('[fy='+fy_year+']').is(':hidden')){
					$('[fy='+fy_year+']').show();	
				}
				else{
					$('[fy='+fy_year+']').hide();
				}
				
			});

			// Alert Input Box
			// $('body').delegate('.new_pay', 'click', function(e){
			// 	e.stopPropagation(); 
			// 	var invoice_no = $(this).attr('invoiceno');
			// 	var invoice_amount = $(this).attr('invoiceamount');
			// 	var payment_value = window.prompt("Enter Payment Amount", "");
			// 	if (payment_value != null) {	

			// 		if (payment_value.match(/^\d+$/)) {
			// 			alert(payment_value);
			// 		}else{
			// 			alert("Please Enter Numeric Value Only");
			// 		}
			// 	}
			
			// });

			$('body').delegate('.new_pay', 'click', function(e){
				e.stopPropagation(); 
				var invoice_no = $(this).attr('invoiceno');
				var invoice_amount = $(this).attr('invoiceamount');
				
				$('#pager_content').load('forms/add_payment.php?cust_id='+cust_id+'&invoice_amount='+invoice_amount+'&invoice_no='+invoice_no);
			
			});

			// cancel payment
			$('body').delegate('#btn_cancel_payment', 'click', function(){
				$('#pager_content').load('display/cust_invoices.php?cust_id='+cust_id, scrollInit);
			});
			
 
			// ----------------- PAYMENTS ---------------------//			
			
			$('body').delegate('.view_comment', 'click', function(){
			
		  		var comment = $(this).attr('comment');
				$('#notes').text(comment);
				$('#notes').show();
			
			});

	

			// confirm payment
			$('body').delegate('#btn_confirm_payment', 'click', function(){
				var payment_amount 	= $('#payment_amount').val();
				var payment_date 	= $('#payment_date').val();
				var payment_comment = $('#payment_comment').val();
				var invoice_no 		= $(this).attr('invoiceno');
				var invoice_amount 		= $(this).attr('invoiceamount');

				// console.log(payment_comment);

				if((payment_amount > 0) && (payment_date != "") &&(!clicked)){
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
							$('#display').load('display/cust_details.php?cust_id='+cust_id);
							$('#pager_content').load('display/cust_payments.php?cust_id='+cust_id, scrollInit);
							$('.tab').removeClass('tab_active');
							$('#payments').addClass('tab_active');
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
			

			// ----------------- TRANSACTIONS ---------------------//
			$('body').delegate('.edit', 'click', function(e) {
				e.stopPropagation(); 
				var trans_id = $(this).attr('id');
				// alert(trans_id);
				$('#pager_content').load('forms/add_transaction.php?cust_id='+cust_id+'&trans_id='+trans_id, function(){
					$('#trans_date').datepicker({ dateFormat: 'dd-mm-yy' });
				});			
			});

			$('body').delegate('.view_transaction', 'click', function(){
				var trans_string = $(this).attr('transstring');
				window.open( msg_url + trans_string, '_blank');	
			});


			$('body').delegate('.trans_key', 'keyup', function(e){
				var id 			= $(this).attr('id');
				var rate_val 	= $('#rate').val();
				var litres_val 	= $('#lit').val();
				var rs_val 		= $('#rs').val();

				if((e.which == 9)&&($(this).val()=='') || ((e.which == 8)&&($(this).val()==''))){
					
				}
				else{
					switch(id){
						case 'rs':
							if(rate_val == ""){
								$(this).val("");
								showSnackBar("Enter Rate");
							}
							else{
								var new_val = rs_val / rate_val;
								$('#lit').val(new_val.toFixed(2));
							}
							break;
						// case 'lit':
						// 	if(rate_val == ""){
						// 		$(this).val("");
						// 		showSnackBar("Enter Rate");
						// 	}
						// 	else{
						// 		var new_val = rate_val * litres_val;
						// 		$('#rs').val(new_val.toFixed(2));
						// 	}
						// 	break;
						case 'rate':
							$('#rs').val('');
							break;
					}
				}
			});
			$('body').delegate('#btn_clear_all', 'click', function(){
				$('#rate').val('');
				$('#lit').val('');
				$('#rs').val('');
			});
			$('body').delegate('#btn_cancel_transaction', 'click', function(){				
				$('#pager_content').load('display/cust_transactions.php?cust_id='+cust_id, scrollInit);
				$("#fab").show();
			});
			$('body').delegate('#btn_new_transaction', 'click', function(){


				if((validateNewTransaction()&&(!clicked))){

					clicked = true;			

					var pump_id 	= -2;				
					var car_id 		= $('#select_car').val();
					var is_postpaid = $('#cust_post_paid').val();
					if (is_postpaid == 'Y') {
						is_postpaid = true;
					}else{
						is_postpaid = false;
					} 
					var amount 		= $('#rs').val();
					var liters 		= $('#lit').val();
					var rate 		= $('#rate').val();
					var shift 		= $('#shift').find(":selected").val();
					var user_id		= $('#user_id').find(":selected").val();
					// var date 		= $('#trans_date').val();
					var date 		= $("#trans_date").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
					$("#trans_date").datepicker("option", "dateFormat", "dd-mm-yy" );		
					var receipt_no 	= $('#rbook_input').val();

					if (receipt_no == "") {
						receipt_no = 0;
					}

					var type 		= $(this).attr('type');
					var trans_id    = $(this).attr('transid');

					var myObject 		= {};
					myObject["pump_id"] = pump_id;
					myObject["cust_id"] = cust_id;
					myObject["car_id"] 	= car_id;
					myObject["user_id"] = user_id;
					myObject["is_postpaid"] = is_postpaid;
					myObject["amount"] 	= amount;
					myObject["liters"] 	= liters;
					myObject["rate"] 	= rate;
					myObject["shift"] 	= shift;
					myObject["date"] 	= date;
					myObject["receipt_no"] = receipt_no;
					myObject["car_no_plate"] = 0;
					myObject["type"] 	= type;
					myObject["trans_id"] 	= trans_id;

					var json_data = JSON.stringify(myObject);

					console.log(json_data);

					$.ajax({
						url: 'api/transactions/regular',
						type: 'POST',
						contentType: "application/json",
						data:json_data,
						success: function(response) {
							console.log(response);
							var json = JSON.parse(response);
							if(json.success){
								if (type == 'new') {
									showSnackBar("New Transaction Added!");
								}
								else{
									showSnackBar("Transaction Updated!");
								}
								
								$('#display').load('display/cust_details.php?cust_id='+cust_id);
								$('#pager_content').load('display/cust_transactions.php?cust_id='+cust_id, scrollInit);
								$('#fab').show();
							}
							else{
								showSnackBar(json.msg);
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
					showSnackBar('Invalid Input!');
				}
			});

			//----------------------HISTORY------------------------------//
			$('body').delegate('#search', 'click', function(){
				var cust_id = $(this).attr('custid');

				var date1 			= $(".date1_h").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var date2 			= $(".date2_h").datepicker("option", "dateFormat", "yy-mm-dd" ).val();

				// $('.date1_h').datepicker({ dateFormat: 'dd-mm-yy' });
				// $('.date2_h').datepicker({ dateFormat: 'dd-mm-yy' });

				if ((date1 != "")&&(date2 != "")) {
					if (date1 <= date2) {
						$('#pager_content').load('display/cust_transactions_history.php?cust_id='+cust_id+'&date1='+date1+'&date2='+date2, scrollInit);
					}else{
						showSnackBar("Make sure FROM date is lower than or equal to TO date");
					}
				}else{
					showSnackBar("Please enter both dates!");
				}


			});

			//----------------------STATEMENT------------------------------//
			$('body').delegate('#search_s', 'click', function(){
				var cust_id 		= $(this).attr('custid');

				var date1 			= $(".date1_s").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var date2 			= $(".date2_s").datepicker("option", "dateFormat", "yy-mm-dd" ).val();

				// $('.date1_h').datepicker({ dateFormat: 'dd-mm-yy' });
				// $('.date2_h').datepicker({ dateFormat: 'dd-mm-yy' });

				if ((date1 != "")&&(date2 != "")) {
					if (date1 <= date2) {
						$('#pager_content').load('display/cust_statement.php?cust_id='+cust_id+'&date1='+date1+'&date2='+date2, scrollInit);
					}else{
						showSnackBar("Make sure FROM date is lower than or equal to TO date");
					}
				}else{
					showSnackBar("Please enter both dates!");
				}


			});


			// ----------------- RECEIPT BOOKS ---------------------//
			$('body').delegate('#btn_new_rbook', 'click', function(){

				var min = $('#in_rbook_min').val();
				var max = $('#in_rbook_max').val();

				if ((min != "")&&(max != "")&&(max > min)&&(!clicked)) {

					clicked = true;

					var myObject = {};
					myObject["cust_id"] = cust_id;
					myObject["min"] = min;
					myObject["max"] = max;
					myObject["pump_id"] = <?php  if(!isset($_SESSION)){session_start();} echo json_encode($_SESSION['pump_id']);?>;

					var json_data = JSON.stringify(myObject);

					$.ajax({
						url: 'api/receiptbook/',
						type: 'POST',
						contentType: "application/json",
						data:json_data,
						success: function(response) {
							console.log(response);
							showSnackBar(response);
							$('#pager_content').load('display/cust_rbooks.php?cust_id='+cust_id, scrollInit);
							$('#fab').show();
							clicked = false;
						},
						error: function(data, errorThrown){
							showSnackBar(errorThrown);				    
				            $('#fab').show();
				            clicked = false;
				        }
					});
				}else{
					showSnackBar("Please enter correct values!");
				}
			
			});
			$('body').delegate('#btn_cancel_rbook', 'click', function(){
				$('#pager_content').load('display/cust_rbooks.php?cust_id='+cust_id, scrollInit);
				$("#fab").show();
			});

			$('body').delegate('.delete', 'click', function(){
				if (confirm('Sure Delete receipt book range?')){	

					var id = $(this).attr("id");
						
					var myObject 		= {};
					myObject["id"] = id;
					var json_data = JSON.stringify(myObject);

					$.ajax({
						url: 'api/receiptbook/delete',
						type: 'POST',
						contentType: "application/json",
						data:json_data,
						success: function(response) {
							// console.log(response);
							showSnackBar("Receipt Book Deleted!");
							// $('#display').load('display/cust_details.php?cust_id='+cust_id);
							$('#pager_content').load('display/cust_rbooks.php?cust_id='+cust_id, scrollInit);
						}
					});
				}
			});

			// fab click
			$('#fab').on('click', function(){
				var mode = $(this).attr('class').replace('_fab','');
				$('#fab').hide();
				$('#notes').hide();
				switch(mode){
					case 'transactions':
						$('#pager_content').load('forms/add_transaction.php?cust_id='+cust_id, function(){
							$('#trans_date').datepicker({ dateFormat: 'dd-mm-yy' });
						});
						break;
					case 'cars':
						$('#pager_content').load('forms/add_car.php');
						break;
					case 'payments':
						$('#pager_content').load('forms/add_payment.php');	
						break;
					case 'receipt_book':
						$('#pager_content').load('forms/add_r_book.php');	
						break;
				}
			});


			// single decimal validation
			$('body').delegate('.single_decimal', 'keypress', function(event){

				if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
					$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) 
				{
					event.preventDefault();
				}
			});
			$('body').delegate('.single_decimal', 'paste', function(event){
				event.preventDefault();
			});

			$('body').delegate('#view_bill', 'click', function(){
				var cust_id = $(this).attr('custid');
				// var date1 = $('#date1').val();
				// var date2 = $('#date2').val();

				var date1 			= $("#date1").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var date2 			= $("#date2").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var date_invoice 	= $("#date_invoice").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				var late_fee 		= $("#late_fee").val();

				if ((late_fee == "")||(late_fee == " ")) {
					late_fee =0;
				}

				$("#date1").datepicker("option", "dateFormat", "dd-mm-yy" );
				$("#date2").datepicker("option", "dateFormat", "dd-mm-yy" );
				$("#date_invoice").datepicker("option", "dateFormat", "dd-mm-yy" );

				// console.log(date1);
				// console.log(date2);

				if ((date1 != "")&&(date2 != "")) {
					window.open('view_bill.php?cust_id='+cust_id+'&date1='+date1+'&date2='+date2+'&date_invoice='+date_invoice+'&invoice_no=0'+'&late_fee='+late_fee, '_blank');
					$('#cars').trigger('click');
				} 
			});

			$('body').delegate('#trans_date', 'change', function(){
				if ($(this).val() != "") {
					$('#select_car').focus();
					checkRates();
				}		
			});

			$('body').delegate('#trans_date', 'keyup', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				event.preventDefault();
				if((keycode == 13)&&($(this).val() != "")){
					$('#select_car').focus();
				}			
			});

			$('body').delegate('#select_car', 'change', function(){
				checkRates();
			});

			$('body').delegate('#select_car', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if((keycode == 13)&&($(this).val() != "")){
					event.preventDefault();
					$('#rate').focus();
					checkRates();
				}
			});

			$('body').delegate('#rate', 'keyup', function(e){
				if((e.which == 13)&&($(this).val() != "")){
					$('#rs').focus();
				}
			});

			$('body').delegate('#rs', 'keyup', function(e){
				if((e.which == 13)&&($(this).val() != "")){
					$('#lit').focus();
				}
			});

			$('body').delegate('#lit', 'keyup', function(e){
				if((e.which == 13)&&($(this).val() != "")){
					$('#rbook_input').focus();
				}
			});

			$('body').delegate('#rbook_input', 'keyup', function(e){
				if(e.which == 13){
					$('#btn_new_transaction').trigger('click');
				}
			});

			// delete car
			$('body').delegate('.del_car', 'click', function(){
				var car_id = $(this).attr('carid');
				var pump_id = <?php  if(!isset($_SESSION)){session_start();} echo json_encode($_SESSION['pump_id']);?>;
				//console.log(car_id);
				
				if (confirm('Confirm Delete Car?')) {	
					$.ajax({
						url: 'exe/del_car.php',
						type: 'POST',
						data:{
							car_id: car_id,
							pump_id : pump_id
						},
						success: function(response){
							$('#pager_content').load('display/cust_cars.php?cust_id='+cust_id, scrollInit);							
						},
						error: function (error) {
						    alert("error deleting car")
						}
					});
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

<?php 
	$active_page = 'customers';
	require 'nav.php';
?>


<!-- wrapper -->
<div id="wrapper">

	<div id="wrapper_content">
		<div id="display"></div>	

		<div id="view_pager">
			<div id="tab_strip" style="padding-top: 20px;">			
				<div id="transactions" class="tab tab_active">TRANSACTIONS</div>
				<div id="cars" class="tab">CARS</div>
				<div id="invoices" class="tab">INVOICES</div>
				<div id="payments" class="tab">PAYMENTS</div>
				<div id="receipt_book" class="tab">RECEIPT-BOOKS</div>
				<div id="history" class="tab">HISTORY</div>
				<div id="statement" class="tab">STATEMENT</div>
			</div>
			<div id="pager_content" style="padding-top: 20px;margin-bottom: 50px;"></div>
		</div>
		
	</div>
	<div id="notes"></div>	
</div>

<!-- snackbar -->
<div id="snackbar"></div>


<?php 
	if(!isset($_SESSION))
	{
	    session_start();
	}
	//if ($_SESSION['role'] != 'manager' ) {
		// <!-- fab -->
		echo'<div id="fab"><span class="tooltiptext">ADD CAR</span></div>';
	//}
?>

</body>
</html>