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
	<title>Transactions</title>	
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

			var msg_url        = <?php echo json_encode(Globals::URL_MSG_VIEW);?>;

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
						if($(this).attr('id') == 'new_cashier'){}else{
							returnVal = false;
							return false;
						}
						
					}
				});

				return returnVal;
			}

			function scrollInit(){
				scrollFunc 		= true;
				tableOffset 	= $("#table-1").offset().top;
				$header 		= $("#table-1 > thead").clone();
				$fixedHeader 	= $("#header-fixed").append($header);


				// set main width
				var width = $('#table-1 thead').width();
				var width2 = $('#header-fixed').width(width);


				var originalHeader 	= $('#table-1 thead');
				var c_id 			= originalHeader.find('.c_id');
				var c_receipt 		= originalHeader.find('.c_receipt');
				var c_name 			= originalHeader.find('.c_name');
				var c_cno 			= originalHeader.find('.c_cno');
				var c_amount 		= originalHeader.find('.c_amount');
				var c_date 			= originalHeader.find('.c_date');


				$fixedHeader.find('.c_id').width(c_id.width());
				$fixedHeader.find('.c_receipt').width(c_receipt.width());
				$fixedHeader.find('.c_name').width(c_name.width());
				$fixedHeader.find('.c_cno').width(c_cno.width());
				$fixedHeader.find('.c_amount').width(c_amount.width());
				$fixedHeader.find('.c_date').width(c_date.width());


				// console.log(c_id.width());
				// console.log(c_receipt.width());
				// console.log(c_name.width());
				// console.log(c_cno.width());
				// console.log(c_amount.width());
				// console.log(c_date.width());


				// console.log(width);
				// console.log(width2);				
			}

			function checkRates(date,transactions){
				$.ajax({
					url: 'exe/check_rates.php',
					data:{
						date:date},
					type: 'GET',
					contentType: "application/json",										
					success: function(response) {						
						var json = JSON.parse(response);
						console.log(json);
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
				$('#display').load('display/view_transactions.php', scrollInit);
				checkRates("",true);
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


			function confirmTransaction(){

				if(!click){
					click = true;
					if(validateManualTrans()){
						
						var pump_id 	= -2;		
						var ftype 		= $('#sel_car').find(":selected").attr('ftype');
						var car_id 		= $('#sel_car').val();
						var cust_id 	= $('#cust_post_paid').attr('custid');
						var is_postpaid = $('#cust_post_paid').val();
						var receipt_no 	= $('#rbook_input').val();
						var shift       = $('#shift').find(":selected").val();
						 
						var car_no_plate = "";

						if(ftype == "unknown"){

							ftype = $('input[name=unknown_fuel]:checked').val();

							var plate_number 	= $('#in_car_no_plate_number').val();

							car_no_plate	= plate_number;
						}

						if (is_postpaid == 'Y') {
							is_postpaid = true;
						}else{
							is_postpaid = false;
						}

						if (ftype == "petrol") {
							var rate =  $('#petrol_rate').val();
						}else if(ftype == "diesel"){
							var rate =  $('#diesel_rate').val();
						}

						var amount 		= $('#m_trans_rs').val();
						var liters 		= $('#m_trans_lit').val();
						var user_id     = $('#user_id').find(":selected").val();
						// rate
						// var date 		= $('#date').val();
						var date  		= $("#date").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
						$("#date").datepicker("option", "dateFormat", "dd-mm-yy" );

						var type = 'new';
						var trans_id = 0;

						if (shift == null) {
							shift = 1;
						}
	 
						var myObject 		= {};
						myObject["pump_id"] = pump_id;
						myObject["cust_id"] = cust_id;
						myObject["car_id"] 	= car_id;
						myObject["user_id"] = user_id;
						myObject["is_postpaid"] = is_postpaid;
						myObject["amount"] 	= amount;
						myObject["liters"] 	= liters;
						myObject["date"] 	= date;
						myObject["rate"] 	= rate;
						myObject["receipt_no"] = receipt_no;
						myObject["fuel"] = ftype;
						myObject["car_no_plate"] = car_no_plate;
						myObject["type"] 	= type;
						myObject["trans_id"] 	= trans_id;
						myObject["shift"] 	= shift;
 

						var json_data = JSON.stringify(myObject);

						console.log(json_data);
						$('#m_trans_result').html("");
						$('#rbook_input').val("");

						$('#btn_manual_t_confirm').hide();

						$.ajax({
							url: 'api/transactions/regular',
							type: 'POST',
							contentType: "application/json",
							data:json_data,
							success: function(response) {
								console.log(response);
								var json = JSON.parse(response);
								if(json.success){
									showSnackBar("New Transaction Added!");
									$('#m_trans_result').html("");
									$('#rbook_input').val("");
									$('#user_id').focus();
									$('#btn_manual_t_confirm').show();
								}
								else{
									showSnackBar(json.msg);
									$('#btn_manual_t_confirm').show();
								}
								click = false;
							},
							error: function(data, errorThrown){
								  showSnackBar(errorThrown);
					    		
					              click = false;
					              $('#btn_manual_t_confirm').show();
					        }
						});
						
					}
					else{
						console.log('Invalid inputs');
						showSnackBar('Invalid inputs');
						click = false;
					}
				}
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

			$('body').delegate('.view_transaction', 'click', function(){
				var trans_string = $(this).attr('transstring');
				window.open( msg_url + trans_string, '_blank');	
			});

			// ----------------- MANUAL TRANSACTIONS ---------------------//
			// fab click
			$('body').delegate('#fab', 'click', function(){
				$('#display').load('forms/add_manual_transaction_header.php',function(){

					$( "#date" ).datepicker({ dateFormat: 'dd-mm-yy' });					

					var date = $("#date").datepicker("option", "dateFormat", "yy-mm-dd" ).val();	
					$("#date").datepicker("option", "dateFormat", "dd-mm-yy" );
					checkRates(date, false);
				});
				$(this).hide();



			});
			// date change
			$('body').delegate('#date', 'change', function(){

				var date = $("#date").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				$("#date").datepicker("option", "dateFormat", "dd-mm-yy" );

				console.log(date);

				$("#petrol_rate").val("");
				$("#diesel_rate").val("");	

				checkRates(date, false);
				
					
				
				$("#rbook_num").val("");
				$('#m_trans_result').html("");
				$('#diesel_rate').prop("disabled", false);
				$('#petrol_rate').prop("disabled", false);
				$('#petrol_rate').focus();
			});			
			// user spinner
			// $('body').delegate('#user_id', 'change', function(){
			// 	user_id = $(this).find(":selected").val();
			// 	// petrol_rate = $("#petrol_rate").val();
			// 	// diesel_rate = $("#diesel_rate").val();

			// 	// if ((date != "")&&(petrol_rate != "")&&(diesel_rate != "")) {
			// 	// 	// alert('proceed');
			// 	// 	$('#rbook_input').show();
			// 	// }else{
			// 	// 	alert("please input fuel rate and date");
			// 	// }
			// });
			// unknown car spinner
			$('body').delegate('#sel_car', 'change', function(){
				var ftype = $(this).find(":selected").attr('ftype');

				switch(ftype){
					case 'unknown':
						$('#form_unknown_car').show();
						$('#diesel_rate').prop("disabled", true);
						$('#petrol_rate').prop("disabled", false);
						$('#m_trans_lit').val("");
						$('#m_trans_rs').val("");
						break;
					case 'petrol':
						$('#form_unknown_car').hide();
						$('#diesel_rate').prop("disabled", true);
						$('#petrol_rate').prop("disabled", false);
						break;
					case 'diesel':
						$('#form_unknown_car').hide();
						$('#diesel_rate').prop("disabled", false);
						$('#petrol_rate').prop("disabled", true);
						break;
				}			
			});

			// unknown car radiobox change
			$('body').delegate('input[name=unknown_fuel]', 'change', function(){
				switch(this.value){
					case 'petrol':
						$('#diesel_rate').prop("disabled", true);
						$('#petrol_rate').prop("disabled", false);
						$('#m_trans_lit').val("");
						$('#m_trans_rs').val("");
						break;
					case 'diesel':
						$('#diesel_rate').prop("disabled", false);
						$('#petrol_rate').prop("disabled", true);
						$('#m_trans_lit').val("");
						$('#m_trans_rs').val("");
						break;
				}
			});

			// manual trans rbook number
			$('body').delegate('#rbook_input', 'keyup', function(){

				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					var l = $('#m_trans_result').find('input').length;
					if(l > 0){
						$('#m_trans_result').find('#sel_car').focus();
						// console.log(y);
					}
				}
				else{
					var num = $(this).val();

					if(num != ""){
						
						$.ajax({
							url: 'exe/get_rbook_num.php',
							type: 'GET',						
							data:{
								rbook_num: num
							},
							success: function(response){
								// console.log(response);
								$('#m_trans_result').html(response);
							}
						});
					}
					else{
						$('#m_trans_result').html("");
					}
				}					
			});
			// rupee value keypress
			$('body').delegate('#m_trans_rs', 'keyup', function(e){

				var ftype 		= $('#sel_car').find(":selected").attr('ftype');
				var rate 		= -1;				
				var lit_input 	= $('#m_trans_lit');
				var rs_val 		= $(this).val();

				// console.log(ftype);

				// get fuel type
				if(ftype == "unknown"){
					ftype = $('input[name=unknown_fuel]:checked').val();
				}

				// if not selected
				if(ftype == 'invalid_fuel'){
					$(this).val("");
					showSnackBar('Select a Car');
				}
				else{
					// fuel is valid
						
					// if value is empty do nothing
					if(rs_val == ""){
						lit_input.val("");
					}
					else{						
						// check rate depending on car
						switch(ftype){
							case 'petrol':						
								rate = $('#petrol_rate').val();
								if((rate == "")||(rate < 1)){
									$(this).val("");
									showSnackBar('Invalid Petrol Rate');
								}
								else{
									var lit_val = round((rs_val / rate),2);
									lit_input.val(lit_val);
								}
								break;
							case 'diesel':
								rate = $('#diesel_rate').val();
								if((rate == "")||(rate < 1)){
									$(this).val("");
									showSnackBar('Invalid Diesel Rate');
								}
								else{
									var lit_val = round((rs_val / rate),2);
									// lit_val = round(lit_val);
									lit_input.val(lit_val); 
								}
								break;
						}
					}
				}				
			});

			// litres value keypress
			$('body').delegate('#m_trans_lit', 'keyup', function(){

				var ftype 		= $('#sel_car').find(":selected").attr('ftype');
				var rate 		= -1;				
				var rs_input	= $('#m_trans_rs');
				var lit_val 	= $(this).val();


				// get fuel type
				if(ftype == "unknown"){
					ftype = $('input[name=unknown_fuel]:checked').val();
				}

				// if not selected
				if(ftype == 'invalid_fuel'){
					$(this).val("");
					showSnackBar('Select a Car');
				}
				else{
					// fuel is valid
						
					// if value is empty do nothing
					if(lit_val == ""){
						rs_input.val("");					
					}
					else{
						// check rate depending on car
						switch(ftype){
							case 'petrol':						
								rate = $('#petrol_rate').val();
								if((rate == "")||(rate < 1)){
									$(this).val("");
									showSnackBar('Invalid Petrol Rate');
								}
								else{
									var rs_val = round((lit_val * rate),2);
									rs_input.val(rs_val);
								}
								break;
							case 'diesel':
								rate = $('#diesel_rate').val();
								if((rate == "")||(rate < 1)){
									$(this).val("");
									showSnackBar('Invalid Diesel Rate');
								}
								else{
									var rs_val = round((lit_val * rate),2);
									rs_input.val(rs_val);
								}
								break;							
						}
					}
				}
					
			});
			// cancel manual trans
			$('body').delegate('#btn_manual_t_cancel', 'click', function(){
				$('#m_trans_result').html("");
				$('#rbook_input').val("");
			});
			// confirm manual trans
			$('body').delegate('#btn_manual_t_confirm', 'click', confirmTransaction);

			// cancel manual trans
			$('body').delegate('#save_cashier', 'click', function(){

				var new_cashier =  $('#new_cashier').val();
				if (new_cashier != "") {
					$.ajax({
						url: 'exe/add_cashier.php',
						type: 'POST',
						data:{
							new_cashier : new_cashier
						},
						success: function(response) {						
							showSnackBar(response);
							$('#display').load('forms/add_manual_transaction_header.php',function(){

							$( "#date" ).datepicker({ dateFormat: 'dd-mm-yy' });					

							var date = $("#date").datepicker("option", "dateFormat", "yy-mm-dd" ).val();	
							$("#date").datepicker("option", "dateFormat", "dd-mm-yy" );
							checkRates(date, false);
						});
						}
					});
				}
				
			});


			// UI NAVIGATION
			// petrol
			$('body').delegate('#petrol_rate', 'keypress', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					var isDisabled = $('#diesel_rate').prop('disabled');
					if(isDisabled){
						$('#user_id').focus();
					}
					else{
						$('#diesel_rate').focus();	
					}
					
				}
			});
			// diesel
			$('body').delegate('#diesel_rate', 'keypress', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					$('#user_id').focus();
				}
			});
			// user id
			$('body').delegate('#user_id', 'keypress', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					// $('#user_id').focus();
					// console.log('asdasd');
					event.preventDefault();
					var unknown = $(this).find(":selected").val();
					if(unknown != -1){
						$('#shift').focus();
					}
					
				}
			});
			// rbook is already on keyup
			// select car
			$('body').delegate('#sel_car', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					// $('#user_id').focus();
					// console.log('asdasd');
					event.preventDefault();
					var unknown = $(this).val();
					if(unknown != -1){
						$('#m_trans_rs').focus();
					}
					else{
						$('#in_car_no_plate_number').focus().select();
					}
				}
			});

			//sel shift
			$('body').delegate('#shift', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					// $('#user_id').focus();
					// console.log('asdasd');
					event.preventDefault();
					$('#rbook_input').focus();
					
				}
			});


			// // num state
			// $('body').delegate('#in_car_no_plate_state', 'keydown', function(){
			// 	var keycode = (event.keyCode ? event.keyCode : event.which);
			// 	if(keycode == '13'){
			// 		$('#in_car_no_plate_city').focus().select();	
			// 	}
			// });

			// // num city
			// $('body').delegate('#in_car_no_plate_city', 'keydown', function(){
			// 	var keycode = (event.keyCode ? event.keyCode : event.which);
			// 	if(keycode == '13'){
			// 		$('#in_car_no_plate_letter').focus();
			// 	}
			// });

			// // num letter
			// $('body').delegate('#in_car_no_plate_letter', 'keydown', function(){
			// 	var keycode = (event.keyCode ? event.keyCode : event.which);
			// 	if(keycode == '13'){
			// 		$('#in_car_no_plate_number').focus();
			// 	}
			// });

			// num num
			// num letter
			$('body').delegate("#in_car_no_plate_number", 'keydown', function(e){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					$("input[name=unknown_fuel]:first").focus();
				}
			});

			// fuel radio
			$('body').delegate('input[name=unknown_fuel]', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					$('#m_trans_rs').focus();
				}
			});


			// m_trans_rs
			$('body').delegate('#m_trans_rs', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					$('#m_trans_lit').focus();
				}
			});

			// m_trans_lit
			$('body').delegate('#m_trans_lit', 'keydown', function(){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					confirmTransaction();
				}
			});


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
							var json = JSON.parse(response);
							if(json.success){
								showSnackBar(json.msg);
								$('#rate_holder').hide();
								$('#fab').show();
							}
						}
					});
				}
					
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


		input{padding: 5px;}
		select{padding: 5px;margin-top: 5px;width: 215px;}
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
	<div id="app_name"><a href="index.php">FuelCam</a></div>
</div>

<!-- side nav -->
<?php 
	$active_page = 'transactions';
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


<?php 
	if(!isset($_SESSION))
	{
		session_start();
	}

	// if ($_SESSION['role'] != 'manager' ) {
		// <!-- fab -->
		echo'<div id="fab"></div>';

	// }
?>


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