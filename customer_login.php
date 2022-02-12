<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>GreenBox Innovations</title>

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
	<meta name="theme-color" content="#FFCB05">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


	<script type="text/javascript" src="js/user_agent.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			var storage = "";
			var user_agent = "";
			storage = jscd.os +' '+ jscd.osVersion +' '+ jscd.browser +' '+jscd.mobile;
			user_agent = navigator.userAgent;

			var c_list_url        = <?php echo json_encode(Globals::URL_CUST_LIST);?>;
			var cust_login_url = <?php echo json_encode(Globals::URL_CUST_LOGIN);?>;


			function setCookie(key, value, days) {
				var expires = new Date();
				if (days) {
					expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
					document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
				} else {
					document.cookie = key + '=' + value + ';expires=Fri, 30 Dec 9999 23:59:59 GMT;';
				}
			}

			$('body').delegate('#login', 'click', function() {
				  
				var mobile_no 	= $('input:radio[name="mobile_group"]:checked').val();
				var otp    		= $('#otp').val();
				var cust_id 	= $(this).attr('custid');				

				var url = 'exe/login_customer_otp_verify.php';
				var method = 'post';

				if (otp != ("")) {
					$.ajax({
						url: url,
						type: method,
						data:{
							mobile_no : mobile_no,
							cust_id : cust_id,
							otp : otp,
							storage : storage,
							user_agent : user_agent,
							verify_otp : true
						},						
						success: function(response) {
							console.log(response);
							var json = $.parseJSON(response);

							if (json.success == true) {					
								window.location.href = c_list_url + cust_id;
							}else{
								alert('Retry Sending OTP');
							}
					    }	
					});
				}else{
					alert("Please enter OTP");
				}
												
			});



			$('body').delegate('#request_otp', 'click', function(){
				var mobile_no 	= $('input:radio[name="mobile_group"]:checked').val();

				if(mobile_no == undefined){
					$('#feedback').show().text("Please Select Phone number and request OTP");
				}else{

					// alert('Working');
					$.ajax({
						url: 'exe/login_customer_otp_request.php',
						type: 'POST',
						data:{
							mobile_no : mobile_no,
							request_otp: true
						},
						success: function(response) {
							var json = $.parseJSON(response);
							console.log(response);
							if (json.success) {
								window.location.href = cust_list_url + json.cust_id;
							}else{
								alert(json.msg);
							}							
						}
					});
				}				
			}); 

		});
	</script>

	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{font-family: 'Open Sans',sans-serif;background-color: rgb(240,240,240);}

		#top_header{
			padding-top: 10px;
			/*height: 70px;*/
			background-color: #FFCB05;
			/*background-color: #2E66B1;*/
			width: 100%;
		}
		#top_header_bar{background-color: #2E66B1;height: 30px;}
		#padding_div{padding: 15px;}

		#cust_details{font-size: 20px;font-family: 'Open Sans',sans-serif;padding: 10px;}
		#cust_display_name{
			font-family: 'Roboto Slab', serif;font-size: 30px;
			/*background-color: green;*/
			padding-left: 20px;
			padding-top: 20px;
		}
		img {
			display: block;
			vertical-align: bottom;
			height: auto;
			width: 100%;
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
			/*margin-left: auto;		  */
			/*margin-right: auto;*/
			/*margin: 20px;*/
		}

		/*viewpager*/
		#view_pager{
			width: 100%;display: flex;height: 50px;
			font-size: 14px;
			font-weight: 700;line-height: 50px;color: rgb(157,157,171);			
		}
		.pager_single{flex: 1;text-align: center;}
		.pager_active{background-color: #fff;color:rgb(112,157,224);border-top-right-radius: 7px;border-top-left-radius: 7px;
			border:1px solid rgb(220,220,220);
			border-bottom: none;
		}

		#main_container{
			background-color: white;border-radius: 7px;border-top-left-radius: 0px;
			border: 1px solid rgb(220,220,220);
			border-top: none;
			position: relative;
		}
		#left_top {
			margin-left: 3px;
			content: '';
			position: absolute;
			top: 0;
			border-top: 1px solid rgb(220,220,220);
			width: calc(50% - 3px);
			display: none;
		}
		#right_top {
			margin-right: 3px;
			content: '';
			position: absolute;
			top: 0;
			right: 0;
			border-top: 1px solid rgb(220,220,220);
			width: calc(50% - 3px);
		}

		/*#table_holder{padding: 10px;}*/
		table{width: 100%;border-collapse: collapse;}
		div{
			/*background-color: green;*/
			padding-top: 5px;
			padding-bottom: 5px;
			color: rgba(0,0,0,0.8);
			padding-left: 10px;
		}

		tr:nth-child(even):not(.header){background-color: rgb(248,248,248);}
		th{
			text-align: left;padding-top: 5px;
			padding-bottom: 5px;
			font-size: 12px;
			padding-left: 10px;
		}
		.right_text{text-align: right;}		
		.amount{
			width:50px;
			padding-right: 10px;
			/*background-color: green;*/
		}
		.fuel{
			width: 20px;
			text-align: right;
			color: rgb(180,180,180);			
			/*background-color: green;*/
		}
		.date_col{
			width:60px;
			/*color: rgb(80,100,100);*/
		}
		.sr{
			padding-left: 10px;
			width: 25px;
			color: rgb(180,180,180);
			/*background-color: green;*/
		}
		input{
			text-decoration: none;
			border: none;
			border-bottom: 2px solid grey;
			height: 23px;
		}

		.mat_btn{
			display: inline-block;
			
			/*background-color: #4285f4;*/
			font-weight: 500;
			background-color: rgb(100,100,100);
			color: #fff;
			width: 120px;
			height: 32px;
			line-height: 32px;
		
			margin-top: 20px;			
			border-radius: 2px;
			font-size: 0.9em;
			text-align: center;
			transition: box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			transition-delay: 0.2s;
			box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
		}
		.mat_btn:hover{cursor: pointer;}
		.mat_btn:active {
			background-color: rgb(90,90,90);
			box-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2);
			transition-delay: 0s;
		}
		#feedback{color: rgb(196,15,24);font-weight: 700;font-size: 18px;margin-bottom: 10px;display: none;}

		@media only screen and (min-width:600px){			
			#top_header{padding-top: 5px;}
			#top_header img{height: 60px;width: auto;}
			#top_header_bar{height: 18px;}
		}

	</style>

</head>
<body>
<div id="top_header">
	<img src="css/cmsg_header.png">
</div>
<div id="top_header_bar"></div>

<div id="padding_div">

<div class="pager_single pager_active">LOGIN</div>	


<div id="main_container">
	<div id="left_top"></div>
	<div id="right_top"></div>
	<?php



	// COMING FROM MESSAGE
	if(isset($_GET['cust_id'])){

		$cust_id = $_GET['cust_id'];
		
		$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."';";
		$exe = mysqli_query($conn, $sql);
		if(mysqli_num_rows($exe) > 0){
			while($row = mysqli_fetch_assoc($exe)) {

				$phone_no = $row['cust_ph_no'];
				$phone_list = explode("|",$phone_no);
				$count = sizeof($phone_list);
			

				if ($count == 1) {

					echo '<input  type="radio" name="mobile_group"  value="'.$phone_no.'" checked="checked">'.$phone_no;
				}
				else{
					foreach ($phone_list as $value) {
						echo '<div><input type="radio" name="mobile_group" value="'.$value.'">'.$value.'</div>';
					}
				}


				echo '<div class="fuel"><input type="number" id="otp"  value="" placeholder="Enter OTP"></input></div>';

				echo'<div style="text-align:center;margin-bottom:10px;">';
					echo'<button class="mat_btn" id="request_otp" custid="'.$cust_id.'">Request OTP</button>';
				echo'</div>';

				echo'<div style="text-align:center;margin-bottom:10px;">';
					echo'<button class="mat_btn" id="login" custid="'.$cust_id.'">LOGIN</button>';
				echo'</div>';

				echo '<div id="feedback">ENTER CUSTOMER NAME!</div>';
				
			}
		}
		
	}
	// COMING FROM DESKTOP
	else{

		// echo '<table>';
		// echo'<tr class="header">';
		// 	echo '<th class="car">Mobile No</th>';
		// 	echo '<th class="car">OTP</th>';
		// echo '</tr>';

		// echo'<tr>';
		// 	echo '<div id="mobile"><input type="number" id="ph_no"></input></div>';			
		// echo '</tr>';
		// echo '</table>';

		// echo'<div id="login_desktop" style="text-align:center;margin-bottom:10px;">';
		// 	echo'<button class="mat_btn" id="request_otp">Request OTP</button>';
		// echo'</div>';
	}

	?>
</div>

</div><!-- padding div -->
</body>
</html>