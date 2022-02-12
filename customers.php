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
	<title>Customers</title>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />	

	<meta name=viewport content="width=device-width, initial-scale=1">
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
		th{text-align: left;}
		th.th_num{text-align: right;padding-left: 10px;}
		/*tr:nth-child(odd){background-color: rgb(207,216,220);}*/
		tr:nth-child(odd){background-color: rgb(222,228,231);}

		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none;margin: 0; }

		input{padding: 7px;width: 200px;margin: 4px auto;width: 200px;}		
		.form_header{font-weight: 700;margin: 10px auto;padding-left: 2px;color: rgb(100,100,100);}
		#fab{background: url('css/icons/ic_new_cust.png') no-repeat center center;background-color: #1aba7a;}

		/*#transactions_table{background-color: yellow;}*/
		
		.tab{display: inline-block;font-weight: 600;padding: 5px;margin-right: 10px;color: rgb(150,150,150);border-bottom: 3px solid transparent;}
		/*.tab_active{border-bottom: 3px solid orange;color: orange;}
		.tab:hover{border-bottom: 3px solid orange;cursor: pointer;}*/
		/*.tab_active{border-bottom: 3px solid rgb(41,169,201);color: rgb(41,169,201);}
		.tab:hover{border-bottom: 3px solid rgb(41,169,201);cursor: pointer;}*/
		.tab_active{border-bottom: 3px solid rgb(29,182,167);color: rgb(29,182,167);}
		.tab:hover{border-bottom: 3px solid rgb(29,182,167);cursor: pointer;}

		#display{padding-top: 50px;margin-bottom: 30px;}
		#name{font-family: 'Roboto Slab', serif;font-size: 40px;margin-bottom: 10px;}
		#ph_no{font-weight: 600; color: rgb(100,100,100);}
		#bal{font-weight: 700;color: rgb(100,100,100);}
		/*#bal span{font-weight: 700;color: rgb(100,100,100);}*/

		/*car plate no*/
		#in_car_no_plate_state{width: 30px;text-transform:uppercase;}		
		#in_car_no_plate_city{width: 20px;}
		#in_car_no_plate_letter{width: 30px;text-transform:uppercase;}
		#in_car_no_plate_number{width: 40px;}

		input{padding: 5px;}
		#select_is_postpaid{width: 215px;}
		select{padding: 5px;margin-top: 5px;width: 250px;}
		.form_header{font-weight: 700;margin: 10px auto;padding-left: 2px;color: rgb(100,100,100);}

		.small_headers{font-size: 12px;font-weight: 600;margin-top: 10px;color: rgba(0,0,0,0.85);}


		.transactions_fab{background: url('css/icons/ic_edit.png') no-repeat center center;}
		.cars_fab{background: url('css/icons/ic_car.png') no-repeat center center;}
		.payments_fab{background: url('css/icons/ic_pay.png') no-repeat center center;}

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

		#fab:hover .tooltiptext {
		    visibility: visible;
		}

		.change_this{width:300px;}

		/*change_this*/
		@media only screen and (max-width: 1360px) {			
			.change_this{width: 200px;}
		}

		.red{color: #ed1275;}

		.right_num{text-align: right;}
		.input_error{background-color: rgb(252,201,202);}		
	</style>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			function init(){
				$('#display').load('display/view_customers.php',scrollInit);
			}

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

			function validateInputs(){
				var returnVal = true;
				$('input[type="text"]:visible:not(#in_cust_gst):not(#in_cust_m_name)').each(function(){
					if($(this).val() == ""){
						console.log($(this).attr('id'));
						$(this).addClass('input_error');
						returnVal = false;
						// return false;
					}
				});
				
				$('input[type="number"]:visible:enabled').each(function(){
					if($(this).val() == ""){
						console.log($(this).attr('id'));
						console.log('asd');
						returnVal = false;
						$(this).addClass('input_error');
						// return false;
					}
				});

				return returnVal;
			}


			function showSnackBar(message) {
				$('#snackbar').text(message);
				$('#snackbar').animate({'bottom':'0'},function() {
					setTimeout(function(){
						$('#snackbar').animate({'bottom':'-50px'});           
					},2000);
				});
			}

			function toggleInputs(choice){
				if(choice == 'Y'){
					$('#in_cust_balance').prop("disabled", true);
					$('#in_cust_outstanding').prop("disabled", false);
					$('#in_cust_credit_limit').prop("disabled", false);
					$('#in_cust_deposit').prop("disabled", false);
					$('#in_cust_balance').val("");
					$('#in_cust_app_limit').val("");
				}
				else{
					$('#in_cust_balance').prop("disabled", false);
					$('#in_cust_outstanding').prop("disabled", true);
					$('#in_cust_credit_limit').prop("disabled", true);
					$('#in_cust_deposit').prop("disabled", true);
					$('#in_cust_outstanding').val("");
					$('#in_cust_credit_limit').val("");
					$('#in_cust_deposit').val("");
					$('#in_cust_app_limit').val("");
				}
			}

			// globals
			var scrollFunc = false;
			var tableOffset, $header, $fixedHeader;
			init();

			// window 
			$(window).bind("scroll", windowScroll);


			// edit cust 
			$('body').delegate('.edit', 'click', function(e) {
				e.stopPropagation(); 
				var cust_id = $(this).attr('custid');
				$('#display').load('forms/add_customer.php?cust_id='+cust_id);			
			});
			

			// click functions
			// table row
			$('body').delegate('tr', 'click', function() {
				var cust_id = $(this).attr("custid");
				window.location.href = 'customer_details.php?cust_id='+cust_id;
				// $.ajax({
				//   url  : 'form/cars/edit_car.php?car_id='+car_id,
				//   type : 'get',
				//   success: function(response){
				//     $('#result').html(response);
				//   }
				// });
			});

			// fab
			$('body').delegate('#fab', 'click', function() {
				// $.ajax({
				// 	url  : 'form/cars/new_car.php',
				// 	type : 'get',
				// 	success: function(response){
				// 		$('#result').html(response);
				// 	}
				// });
				$('#display').load('forms/add_customer.php');
				$(this).hide();
			});

			// new cust cancel
			$('body').delegate('#btn_cancel_cust', 'click', function() {
				$('#fab').show();
				init();
			});



			// new cust submit
			$('body').delegate('#btn_new_cust', 'click', function(){

				var cust_f_name	 	= $('#in_cust_f_name').val();
				var cust_m_name	 	= $('#in_cust_m_name').val();
				var cust_l_name	 	= $('#in_cust_l_name').val();
				// var cust_ph_no	 	= $('#in_cust_ph_no').val();
				var cust_service	= $('#in_cust_service').val();
				var cust_address 	= $('#in_cust_address').val();
				var cust_post_paid	= $('#select_is_postpaid').find(":selected").val();
				var cust_balance	= $('#in_cust_balance').val();
				var cust_outstanding = $('#in_cust_outstanding').val();
				var cust_last_updated = $('#in_cust_last_updated').val();
				var cust_credit_limit = $('#in_cust_credit_limit').val();
				var cust_deposit     = $('#in_cust_deposit').val();
				var cust_company 	= $('#in_cust_comp_name').val();
				var cust_gst 		= $('#in_cust_gst').val();
				var cust_app_limit = $('#in_cust_app_limit').val();

				var type 			=  $(this).attr('type');
				var cust_id 		= null;
				if (type == 'update') {
					cust_id = $(this).attr('custid');
				}

				// ph_no validation
				var ph_bool = false;
				var address_bool = false;
				var cust_ph_no = "";
				var ph_count = 1;				



				$('.in_cust_ph_no').each(function(){


					var ph_val = $(this).val();
					// check if all numbers are 10 digits
					if(ph_val.length != 10){
						$(this).addClass('input_error');
						showSnackBar('Invalid Phone Number');
						ph_bool = false;
						return false;
					}
					else{
						// if first number assign to string
						if(ph_count == 1){
							cust_ph_no = ph_val;
						}
						else{
							cust_ph_no += "|"+ph_val;
						}
						ph_bool = true;
					}
					ph_count++;
				});

				if(cust_address == ""){
					$('#in_cust_address').addClass('input_error');
					showSnackBar('Empty Address');
				}
				else{
					address_bool = true;
				}

				console.log(ph_bool+" "+ph_count);
				if(ph_bool && address_bool){

					if(validateInputs()){
						console.log('inputs are valid');

						var myObject = {};
						myObject.cust_f_name 	= cust_f_name;
						myObject.cust_m_name 	= cust_m_name;
						myObject.cust_l_name 	= cust_l_name;
						myObject.cust_ph_no 	= cust_ph_no;
						myObject.cust_address	= cust_address;
						myObject.cust_post_paid = cust_post_paid;
						myObject.cust_balance 	= cust_balance;
						myObject.cust_outstanding = cust_outstanding;
						myObject.cust_credit_limit = cust_credit_limit;
						myObject.cust_app_limit = cust_app_limit;
						myObject.cust_deposit	= cust_deposit;
						myObject.cust_company	= cust_company;
						myObject.cust_gst 		= cust_gst;
						myObject.cust_service 	= cust_service;
						myObject.cust_id 	 	= cust_id;
						myObject.cust_type  	= type;
						myObject.pump_id  		=<?php  if(!isset($_SESSION)){session_start();} echo json_encode($_SESSION['pump_id']);?>;


						if (myObject.cust_post_paid == 'Y') {
							myObject.cust_balance = 0;
						}else{
							myObject.cust_outstanding = 0;
							myObject.cust_credit_limit = 0;
							myObject.cust_deposit = 0;
						}

						json_string = JSON.stringify(myObject);

						var url = 'api/customers';

						console.log(json_string);

						$('#btn_new_cust').hide();

						$.ajax({
							url: url,
							type: 'POST',
							contentType: "application/json",
							data:json_string,
							success: function(response){
								console.log(response);
								var json = JSON.parse(response);
								if(json.success){
									if (type == 'update') {
										showSnackBar("Customer Updated!");
									}
									else{
										showSnackBar("New Customer Added!");
									}
									$('#btn_new_cust').show();
									init();
									$("#fab").show();
								}
								else{
									showSnackBar(json.msg);
									$('#btn_new_cust').show();
								}
							},
							error: function (request, status, error) {
								console.log(request.responseText);
								$('#btn_new_cust').show();
							}
						});
					}
					else{
						showSnackBar('Empty Input Values!');
						console.log('inputs are invalid');
						$('#btn_new_cust').show();
					}
				}
			});


			// amount validation
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


			// set display limit to 25% of outstanding or balance
			$('body').delegate('#in_cust_credit_limit,#in_cust_balance', 'keyup', function(event){
				var v = $(this).val();
				var disp_limit = v * 0.25;
				console.log(disp_limit);
				if(disp_limit == 0){
					$('#in_cust_app_limit').val("");
				}else{
					$('#in_cust_app_limit').val(disp_limit);
				}				
			});

			// $('body').delegate('#in_cust_ph_no', 'keydown', function(e){
			// 	// console.log(e.keyCode);
			// 	if((e.keyCode == 46)||(e.keyCode == 190)){
			// 		e.preventDefault();
			// 	}
			// });


			// payment type change
			// show limit input
			$('body').delegate('#select_is_postpaid', 'change', function(){
				var choice = $(this).find(":selected").val();
				toggleInputs(choice);
			});

			$('body').delegate('#in_cust_ph_no', 'keyup change input paste', function(e){
				// console.log("working");
			    var $this = $(this);
			    var val = $this.val();
			    var valLength = val.length;
			    var maxCount = $this.attr('maxlength');
			    if(valLength>maxCount){
			        $this.val($this.val().substring(0,maxCount));
			    }
			}); 


			// add phone numbers
			$('body').delegate('.add_ph_no', 'click', function(){				
				$(this).parent().before('<div class="div_ph_no"><input type="number" class="in_cust_ph_no" placeholder="Phone Number" maxlength="10"><button class="add_ph_no">Add Phone Number</button><button class="del_ph_no">Delete</button></div>');
			});

			// delete phone number
			$('body').delegate('.del_ph_no', 'click', function(){
				var num = $(this).parent().find('.in_cust_ph_no').val();
				console.log(num);

				var l = $('.del_ph_no').length;
				if(l > 1){
					$(this).parent().remove();
				}
			});
			

			$('body').delegate('textarea,input', 'focus', function(){
				if($(this).hasClass('input_error')){
					$(this).removeClass('input_error');
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
	<div id="wrapper_content">
		<div id="display"></div>		
	</div>
</div>

<!-- snackbar -->
<div id="snackbar"></div>

<!-- fab -->
<div id="fab"></div>


</body>
</html>