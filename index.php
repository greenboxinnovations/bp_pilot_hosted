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
	<title>FuelCam</title>
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
	<meta name=viewport content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="js/jquery.js"></script>

	<!-- datepicker css -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
	<!-- jqueryUI for datepicker -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

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

			function validateManualTrans(){
				var returnVal = true;

				// if fuel spinner is select
				if( $('#user_id').find(":selected").val() == -1){
					return false;
				}				


				$('input[type=number]:visible:enabled').each(function(){
					if(($(this).val() == "")||($(this).val() == 0)){
						console.log($(this).attr('id'));
						returnVal = false;
						return false;
					}
				});


				$('input[type=text]:visible:enabled').each(function(){
					if(($(this).val() == "")||($(this).val() == 0)){
						console.log($(this).attr('id'));
						returnVal = false;
						return false;
					}
				});

				return returnVal;
			}

			function scrollInit(){
				scrollFunc = true;
				tableOffset = $("#table-1").offset().top;
				$header 	= $("#table-1 > thead").clone();
				$fixedHeader = $("#header-fixed").append($header);
			}

			function checkRates(date,transactions){
			var pump_id = <?php  if(!isset($_SESSION)){session_start();}  if (!isset($_SESSION['pump_id']) || !isset($_SESSION['role'])) {header("location: login.php");} echo json_encode($_SESSION['pump_id']);?>;
				$.ajax({
					url: 'exe/check_rates.php',
					data:{
						date:date,
						pump_id:pump_id
					},
					type: 'GET',
					contentType: "application/json",										
					success: function(response) {						
						var json = JSON.parse(response);
						if(!json.rate_set){
							if(transactions){
								$('#rate_holder').show();
							}
							else{
								$("#petrol_rate").val("");
								$("#diesel_rate").val("");
							}					
							$('#fab').hide();
							p_rate = -1;
							d_rate = -1;
						}
						else{
							p_rate = json.petrol;
							d_rate = json.diesel;
							if(!transactions){
								console.log(p_rate);
								console.log(d_rate);
								if(p_rate != 0.00){
									$("#petrol_rate").val(p_rate);	
								}
								if(d_rate != 0.00){
									$("#diesel_rate").val(d_rate);
								}
							}
						}
					}
				});
			}

			function init(){
				// $('#display').load('display/view_transactions.php', scrollInit);
				checkRates("",true);
				$('#emp_date').datepicker({ dateFormat: 'dd-mm-yy' });
				$('#daily_rate_display').load('display/index/daily_rate_display.php?date=');
				$('#emp_list').load('display/index/emp_list.php?date=&shift=1');
				$('#emp_trans').load('display/index/emp_transactions.php?date=&emp_id=&shift=1');
				
			}
 
			function round(num, decimals){
				var t = Math.pow(10, decimals);   
   				return (Math.round((num * t) + (decimals>0?1:0)*(Math.sign(num) * (10 / Math.pow(100, decimals)))) / t).toFixed(decimals);
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
			var petrol_rate,diesel_rate,user_id;
			var p_rate,d_rate;
			var click = false;

			// window 
			$(window).bind("scroll", windowScroll);




			// submit rates
			$('body').delegate('#btn_submit_rates', 'click', function(){
				var pass = true;
				var myObject = {};
				$('#rate_holder').find('input').each(function(){
					var rate = $(this).val();
					var val = $(this).attr('id').replace('user_','').replace('_rate','');					
					if(rate == ""){
						pass = false;
						showSnackBar("Please enter rates!");
					}
					else{
						myObject[val] = rate;
					}					
				});

				if(pass){
					var json_data = JSON.stringify(myObject);
					console.log(json_data);
					$.ajax({
						url: 'api/transactions/rates',
						type: 'POST',
						contentType: "application/json",
						data:json_data,
						success: function(response) {
							console.log(response);
							var json = JSON.parse(response);
							if(json.success){
								showSnackBar(json.msg);
								$("#user_petrol_rate").val("");
								$("#user_diesel_rate").val("");
								$('#rate_holder').hide();
								$('#fab').show();
								$('#daily_rate_display').load('display/index/daily_rate_display.php?date=');
							}else{
								showSnackBar(json.msg);
								$('#rate_holder').hide();
								$('#fab').show();
								$('#daily_rate_display').load('display/index/daily_rate_display.php?date=');
							}
						}
					});
				}
			});

			// add new rate
			$('body').delegate('#btn_new_rate', 'click', function(){
				$('#rate_holder').show();
			});
			


			// decimal 2 digits validation with 
			$('body').delegate('.single_decimal_twodigit', 'keypress', function(event){

				// if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
				// 	$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) 
				// {
				// 	event.preventDefault();
				// }

				var character = String.fromCharCode(event.keyCode)
			    var newValue = this.value + character;
			    if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
			        event.preventDefault();
			        return false;
			    }
			});
			function hasDecimalPlace(value, x) {
			    var pointIndex = value.indexOf('.');
			    return  pointIndex >= 0 && pointIndex < value.length - x;
			}
			$('body').delegate('.single_decimal_twodigit', 'paste', function(event){
				event.preventDefault();
			});



			// ----------------------------- NEW STUFF ------------------------------

			function loadEmpTable(date, shift, emp_id){
				// console.log(date+" "+shift+" "+emp_id);
				$('#emp_trans').load('display/index/emp_transactions.php?date='+date+'&emp_id='+emp_id+'&shift='+shift);
			}

			function loadEmpList(date, shift){
				$('#emp_list').load('display/index/emp_list.php?date='+date+'&shift='+shift);
			}

			$('body').delegate('table#emp_table tr', 'click', function(){
				
				if(!$(this).hasClass('emp_active')){
					console.log('no class');
					$('#emp_table tr').removeClass('emp_active');
					$(this).addClass('emp_active');

					var emp_id = $(this).attr('empid');						
					var shift = $('.shift_active').text().replace("SHIFT ", "").trim();
					var date = $('#emp_date').val();
					loadEmpTable(date, shift, emp_id);
				}
			});

			$('body').delegate('.shift_single', 'click', function(){
				if(!$(this).hasClass('shift_active')){
					$('.shift_single').removeClass('shift_active');
					$(this).addClass('shift_active');

					var emp_id = $('#emp_table .emp_active').attr('empid');
					var shift = $(this).text().replace("SHIFT ", "").trim();
					var date = $('#emp_date').val();
					loadEmpTable(date, shift, emp_id);
					loadEmpList(date, shift);
					
					$('#daily_rate_display').load('display/index/daily_rate_display.php?date='+date);
				}
			});


			$('body').delegate('#emp_date', 'change', function(){
				var emp_id = $('#emp_table .emp_active').attr('empid');
				var shift = $('.shift_active').text().replace("SHIFT ", "").trim();
				var date = $(this).val();
				loadEmpTable(date, shift, emp_id);
				loadEmpList(date, shift);

				$('#daily_rate_display').load('display/index/daily_rate_display.php?date='+date);
			});

		});
	</script>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
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

		th{text-align: left;}
		th.th_num{text-align: right;padding-left: 10px;}
		/*tr:nth-child(odd){background-color: rgb(207,216,220);}*/
		tr:nth-child(odd){background-color: rgb(222,228,231);}
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


		/*rates*/		
		#rate_holder{
			display: none;

			background-color: #263238;
			color: rgb(221,222,217);position: fixed;width: 200px;height: auto;right: 0;top: 90px;z-index: 99;margin-right: 20px;border-radius: 3px;padding: 10px;
			-webkit-box-shadow: -1px 6px 9px -5px rgba(0,0,0,0.75);
			-moz-box-shadow: -1px 6px 9px -5px rgba(0,0,0,0.75);
			box-shadow: -1px 6px 9px -5px rgba(0,0,0,0.75);	
		}
		.rate_name{display: inline-block;width: 60px;margin-left: 10px;}
		.rate_val{background-color: #263238;border: none;outline: none;border-bottom: 1px solid rgb(221,222,217);color: rgb(221,222,217);width: 100px;}

		/*manual transactions*/
		/*#rbook_input{display: none;}*/
		#form_unknown_car{display: none;}
		input[name=unknown_fuel]{width: 30px;background-color: green;}

		/*daily rate display*/
		#daily_rate_holder{font-family: 'Open Sans';font-weight: 500;color: rgb(100,100,100);}
		.daily_rate_single{display: inline-block;background-color: rgb(220,220,220);padding: 10px 20px;border-radius: 4px;border: 1px solid rgb(200,200,200);}
		.daily_rate_single span{font-weight: 700;}


		/*shift chooser*/
		#shift_chooser{margin-top: 20px;font-weight: 600;}
		.shift_single{display: inline-block;vertical-align: top;padding: 5px 10px;color: rgb(140,140,140);}
		.shift_active{color: rgb(80,80,80);border-bottom: 3px solid rgb(0,162,232);}
		.shift_single:hover{cursor: pointer;}

		/*table divs*/
		#table_display{margin-top: 20px;}
		.table_single{display: inline-block;vertical-align: top;width: 35%;height: 300px;margin-right: -4px;}

		/*emp table*/
		#emp_table{width: 100%;/*font-size: 13px;*/font-weight: 600;color: rgb(80,80,80);}
		.emp_active{color:rgb(0,162,232);}
		#emp_table tr:not(.emp_active):hover{background-color: rgb(200,200,200);}

		.change_this{width:300px;}		
		@media only screen and (max-width: 1360px) {
			.change_this{width: 200px;}
		}


		@media only screen and (min-width: 1050px) {
			/*table{min-width: 900px;}*/
			.c_id{width: 40px;}
			.c_name{}
			.c_cno{width: 150px;}
			.c_amount{text-align: right;padding-right: 30px;width: 150px;}
			.c_date{width: 100px;}
		}
	</style>
</head>
<body>

<!-- app nav -->
<div id="app_bar">  
	<div id="menu">
		<img src="css/icons/ic_menu.png">
	</div>
	<div id="app_name"><a href="#">FuelCam</a></div>
</div>

<!-- side nav -->
<?php 
	$active_page = 'index';
	require 'nav.php';
?>
 

<!-- wrapper -->
<div id="wrapper">
	<div id="wrapper_content">
		<div id="display">
			<!-- daily rate display -->
			<div id="daily_rate_display"></div>

			<!-- shift chooser -->
			<div id="shift_chooser">
				<div class="shift_single shift_active">SHIFT 1</div>
				<div class="shift_single">SHIFT 2</div>
				<div style="display: inline-block;"><input type="text" id="emp_date" value="<?php echo date('d-m-Y');?>"></input></div>
			</div>


			<!-- table display -->
			<div id="table_display">
				<div class="table_single" id="emp_list">
					<!-- <table style="width: 100%;font-size: 13px;font-weight: 600;color: rgb(80,80,80);">
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
						<tr><td>driver name</td></tr>
					</table> -->
				</div>
				<div class="table_single" id="emp_trans">2</div>
			</div>


		</div>
	</div>
</div>

<!-- snackbar -->
<div id="snackbar"></div>

<!-- fab -->
<!-- <div id="fab"></div> -->

<div id="rate_holder">
	<div>
		<div class="rate_name">Petrol</div>
		<div style="display: inline-block;">
			<input class="rate_val single_decimal_twodigit" type="number" id="user_petrol_rate">
		</div>
	</div>
	<div>
		<div class="rate_name">Diesel</div>
		<div style="display: inline-block;">
			<input class="rate_val single_decimal_twodigit" type="number" id="user_diesel_rate">
		</div>
	</div>
	<div style="text-align: right;margin-bottom: 5px;margin-top: 20px;"><div class="mat_btn" id="btn_submit_rates" style="margin-right: 10px;">SAVE</div></div>
</div>




</body>
</html>