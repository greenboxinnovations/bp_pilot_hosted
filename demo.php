<?php
require 'exe/lock.php';
require __DIR__.'/query/conn.php';
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
	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{font-family: 'Open Sans',sans-serif;background-color: rgb(240,240,240);}

		#top_header{
			padding-top: 10px;			
			background-color: #FFCB05;			
			width: 100%;
		}
		#top_header_bar{background-color: #2E66B1;height: 30px;}

		#padding_div{padding: 20px;}

		#cust_details{
			font-size: 20px;
			padding: 20px;
			background-color: white;
			border-radius: 5px;
			border: 1px solid rgb(220,220,220);
			margin-bottom: 30px;
		}
		#cust_display_name{
			font-family: 'Roboto Slab', serif;font-size: 30px;
			/*background-color: green;*/
			display: inline-block;
			vertical-align: top;
			width: 100%;
			margin-bottom: 10px;
		}

		.label{
			font-weight: 700;
			font-size: 15px;
			color: rgba(0,0,0,0.4);
			margin-bottom: 5px;
			margin-top: 5px;
		}

		#button_div{
			/*text-align: right;*/
			display: inline-block;
			vertical-align: top;
			width: 100%;
			margin-top: 5px;
		}

		a{text-decoration: none;color: rgba(255,255,255,0.85);}

		#button_div button{
			/*background-color: green;padding: 0px 10px 0px 10px;*/
			position: relative;

			/*display: block;*/
			/*margin: 30px auto;*/
			padding: 10px;

			overflow: hidden;

			border-width: 0;
			outline: none;
			border-radius: 2px;
			box-shadow: 0 1px 4px rgba(0, 0, 0, .6);
			font-weight: 600;
			background-color: #2E66B1;
			color: #ecf0f1;

			transition: background-color .3s;
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
		

		.center_align{}
		.center_align input{	
			/*margin-top: 20px;*/
			padding: 10px;font-size: 20px;
			padding-right: 20px;
			padding-left: 20px;
			/*border: none;*/
			border: 2px solid rgba(0,0,0,0.4);
			border-radius: 4px;
			margin-bottom: 15px;
		}

		.center_align input:focus {
			outline: none;
			border: 2px solid rgba(0,0,0,0.9);
		}

		#feedback{color: rgb(196,15,24);font-weight: 700;font-size: 18px;margin-bottom: 10px;display: none;}

		@media only screen and (min-width:600px){
			#top_header{padding-top: 5px;}
			#top_header img{height: 60px;width: auto;}
			#top_header_bar{height: 18px;margin-bottom: 30px;}
			/*.img_border{display: inline-block;width: 400px;}*/
			.fluid_containers{
				display: inline-block;
				width: calc(50% - 60px);margin-right: -4px;vertical-align: top;
				padding-left: 30px;
				padding-right: 30px;
			}
			/*.fluid_containers img{height: 320px;width: auto;}*/
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){

			var clicked = false;
			var url = 'exe/send_demo_sms.php';

			$('#send_sms').on('click', function(){
				var cust_num = $('#cust_ph_no').val();
				var cust_name = $('#cust_name').val();
				// console.log(cust_name);
				var proceed = true;
				if(cust_num.length != 10){
					// console.log("enter a valid number");
					$('#feedback').show().text("ENTER A VALID NUMBER");
					proceed = false;
				}
				if(cust_name == ""){
					$('#feedback').show().text("ENTER A CUST NAME");
					proceed = false;	
				}



				if(proceed && !clicked){

					var myObject = {};
					myObject.cust_name = cust_name;
					myObject.cust_num = cust_num;					

					// json_string = JSON.stringify(myObject);

					// console.log(json_string);
					
					clicked = true;
					$.ajax({
						url: url,
						type: 'POST',
						// contentType: "application/json",
						data: {myObject},
						success: function(response){
							console.log(response);
							var json = JSON.parse(response);
							if(json.success){
								console.log("works");
								$('#feedback').show().text(json.msg);
								$('#cust_ph_no').val("");
								setTimeout(function(){
									$('#feedback').hide();	
								}, 2000);								
							}
							else{
								console.log(json.msg);
								$('#feedback').show().text(json.msg);
							}
							
							// $('#pager_content').load('display/cust_cars.php?cust_id='+cust_id, scrollInit);
							// // showSnackBar("New Company Added!");
							// $("#fab").show();
							clicked = false;
						},
						error: function (error) {
							clicked = false;
						}
					});
				}
			});



			$('#cust_ph_no').on('keypress', function(key) {
				if(key.charCode < 48 || key.charCode > 57) return false;
				$('#feedback').hide();
			});

			$('#cust_name').on('keypress', function(key) {				
				$('#feedback').hide();
			});


			$('.center_align input').on('focus',function(){
				$('#feedback').hide();
				if($(this).attr('id') == "cust_ph_no"){
					$('#label_cust_ph_no').css('color','rgba(0,0,0,0.9)');
				}
				else{
					$('#label_cust_name').css('color','rgba(0,0,0,0.9)');
				}
			});

			$('.center_align input').on('focusout',function(){
				$('.label').css('color','rgba(0,0,0,0.6)');
			});

		});
	</script>
</head>
<body>

<div id="top_header">
	<img src="css/cmsg_header.png">
</div>
<div id="top_header_bar"></div>

<div id="padding_div">

<?php
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';



echo '<div class="fluid_containers">';
echo '<div id="cust_details">';

	$display_name 	= "Enter Phone No.";
	echo '<div id="cust_display_name">Demo SMS</div>';
	
	echo '<div class="label" id="label_cust_name">CUSTOMER NAME</div>';
	echo'<div class="container" >';
		echo '<div class="center_align" ><input id="cust_name" type="text"></div>';
	echo '</div>';

	echo '<div class="label" id="label_cust_ph_no">PHONE NUMBER</div>';
	echo'<div class="container" >';
		echo '<div class="center_align"><input id="cust_ph_no" type="text" maxlength="10" size="10"></div>';
	echo '</div>';


	echo '<div id="feedback">ENTER CUSTOMER NAME!</div>';
	echo '<div id="button_div">';
		echo '<button id="view_all" ><a id="send_sms">SEND SMS</a></button>';
	echo '</div>';				
	echo '<div class="clear_both"></div>';

echo '</div>';
echo '</div>';

?>
</div><!-- padding div -->
</body>
</html>